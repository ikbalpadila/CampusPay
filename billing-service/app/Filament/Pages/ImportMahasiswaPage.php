<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Filament\Pages\MahasiswaPage;

class ImportMahasiswaPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedArrowUpTray;

    protected static ?string $navigationLabel = 'Import Mahasiswa Excel';
    protected static ?string $title           = 'Import Data Mahasiswa dari Excel';
    protected static ?int    $navigationSort  = 5;
    protected string  $view            = 'filament.pages.import-mahasiswa';

    public static function getNavigationGroup(): ?string
    {
        return 'Data Akademik';
    }

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                FileUpload::make('file')
                    ->label('File Excel (.xlsx / .xls)')
                    ->required()
                    ->acceptedFileTypes([
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'application/vnd.ms-excel',
                    ])
                    ->maxSize(2048)
                    ->helperText('Maksimal 2MB. Format kolom: nim, nama, prodi, fakultas, semester, kelas'),
            ]);
    }

    public function import()
    {
        $data = $this->form->getState();

        if (empty($data['file'])) {
            Notification::make()
                ->title('Pilih file Excel terlebih dahulu')
                ->warning()
                ->send();

            return;
        }

        try {

            $file = $data['file'];

            // Jika FileUpload mengembalikan array
            if (is_array($file)) {
                $file = reset($file);
            }

            if (! Storage::disk('local')->exists($file)) {

                Notification::make()
                    ->title('File tidak ditemukan')
                    ->body("Path: {$file}")
                    ->danger()
                    ->send();

                return;
            }

            $fullPath = Storage::disk('local')->path($file);

            $response = Http::timeout(120)
                ->attach(
                    'file',
                    fopen($fullPath, 'r'),
                    basename($fullPath)
                )
                ->post(
                    env('STUDENT_SERVICE_URL', 'http://127.0.0.1:8002')
                    . '/api/students/import'
                );

                if ($response->successful()) {

                    Notification::make()
                        ->title('Import berhasil')
                        ->body('Data mahasiswa berhasil diimport.')
                        ->success()
                        ->send();
                
                        return redirect()->to(
                            MahasiswaPage::getUrl()
                        );                
                    
        } else {

            Notification::make()
                ->title('Import gagal')
                ->body($response->body())
                ->danger()
                ->send();

        }

        } catch (\Throwable $e) {

            Notification::make()
                ->title('Error')
                ->body($e->getMessage())
                ->danger()
                ->send();

        }
    }

}