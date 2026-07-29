<?php

namespace App\Filament\Pages;

use App\Models\PaymentType;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GenerateTagihanMassal extends Page
{
    protected static ?string $title = 'Generate Tagihan Massal';
    protected static ?string $navigationLabel = 'Generate Tagihan Massal';
    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.generate-tagihan-massal';

    public ?array $data = [];

    public static function getNavigationGroup(): ?string
        {
            return 'Keuangan';
        }

    public function mount(): void
    {
        $this->data = [];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([

                Select::make('payment_type_id')
                    ->label('Jenis Pembayaran')
                    ->options(
                        PaymentType::where('is_aktif', true)
                            ->pluck('nama', 'id')
                            ->toArray()
                    )
                    ->required(),

                Select::make('semester_id')
                    ->label('Semester')
                    ->options($this->getSemesterOptions())
                    ->required(),

                TextInput::make('nominal')
                    ->label('Nominal')
                    ->numeric()
                    ->required(),

                DatePicker::make('jatuh_tempo')
                    ->label('Jatuh Tempo')
                    ->required(),
            ]);
    }

    private function getSemesterOptions(): array
    {
        try {
            $response = Http::get(config('services.student.url') . '/api/semesters');

            if ($response->successful()) {
                return collect($response->json('data'))
                    ->pluck('nama', 'id')
                    ->toArray();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        return [];
    }

    public function generate(): void
    {
        $data = $this->data;

        if (empty($data['payment_type_id']) || empty($data['semester_id']) || empty($data['nominal']) || empty($data['jatuh_tempo'])) {
            Notification::make()
                ->title('Form Belum Lengkap')
                ->body('Mohon lengkapi seluruh field yang tersedia.')
                ->danger()
                ->send();
            return;
        }

        try {
            $studentClient = app(\App\Services\StudentServiceClient::class);
            $mahasiswas = $studentClient->getMahasiswaAktifBySemester((int)$data['semester_id']);

            if (empty($mahasiswas)) {
                Notification::make()
                    ->title('Tidak Ada Mahasiswa')
                    ->body('Tidak ditemukan mahasiswa aktif pada semester yang dipilih.')
                    ->warning()
                    ->send();
                return;
            }

            $semesters = $studentClient->getSemesters();
            $semester  = collect($semesters)->firstWhere('id', (int)$data['semester_id']);
            $semesterNama = $semester['nama'] ?? 'Semester ' . $data['semester_id'];

            \Illuminate\Support\Facades\DB::beginTransaction();

            $existing = \App\Models\Tagihan::where('payment_type_id', $data['payment_type_id'])
                ->where('semester_id', $data['semester_id'])
                ->pluck('mahasiswa_id')
                ->toArray();

            $insertData = [];
            $created = 0;
            $skipped = 0;
            $newlyCreatedMahasiswaIds = [];

            foreach ($mahasiswas as $mhs) {
                if (in_array($mhs['id'], $existing)) {
                    $skipped++;
                    continue;
                }

                $insertData[] = [
                    'mahasiswa_id'    => $mhs['id'],
                    'mahasiswa_nim'   => $mhs['nim'],
                    'mahasiswa_nama'  => $mhs['nama'],
                    'payment_type_id' => $data['payment_type_id'],
                    'semester_id'     => $data['semester_id'],
                    'semester_nama'   => $semesterNama,
                    'nominal'         => $data['nominal'],
                    'jatuh_tempo'     => $data['jatuh_tempo'],
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ];

                $newlyCreatedMahasiswaIds[] = $mhs['id'];
                $created++;

                if (count($insertData) >= 200) {
                    \App\Models\Tagihan::insert($insertData);
                    $insertData = [];
                }
            }

            if (!empty($insertData)) {
                \App\Models\Tagihan::insert($insertData);
            }

            \Illuminate\Support\Facades\DB::commit();

            // Publish event ke RabbitMQ untuk notifikasi mikroservis
            if ($created > 0) {
                \App\Jobs\PublishBillingEvent::dispatch('tagihan_dibuat', [
                    'payment_type_id' => $data['payment_type_id'],
                    'semester_id'     => $data['semester_id'],
                    'semester_nama'   => $semesterNama,
                    'created_count'   => $created,
                    'mahasiswa_ids'   => $newlyCreatedMahasiswaIds,
                ]);
            }

            Notification::make()
                ->title('Berhasil Generate Tagihan')
                ->body("Sukses membuat {$created} tagihan baru (Dilewati: {$skipped} karena sudah ada). Data langsung tersimpan & event dipublish ke RabbitMQ.")
                ->success()
                ->send();

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            Log::error('Generate Tagihan Massal Error: ' . $e->getMessage());

            Notification::make()
                ->title('Error Server')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}