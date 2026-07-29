<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Http;
use Filament\Notifications\Notification;
use App\Filament\Pages\ImportMahasiswaPage;

class MahasiswaPage extends Page
{
    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedUsers;

    protected static ?string $navigationLabel =
        'Data Mahasiswa';

    protected static ?string $title =
        'Data Mahasiswa';
    
    protected static ?int $navigationSort = 3;

    protected string $view =
        'filament.pages.mahasiswa-page';

    public static function getNavigationGroup(): ?string
        {
            return 'Data Akademik';
        }

    public array $mahasiswas = [];

    public array $semesters = [];

    public string $search = '';

    public string $semesterFilter = '';

    public bool $showModal = false;

    public string $nim = '';

    public string $nama = '';

    public string $prodi = '';

    public string $fakultas = '';

    public string $kelas = '';

    public string $semester_id = '';

    public bool $showEditModal = false;

    public ?int $editId = null; 

    public bool $status_aktif = true;

    public bool $showDetailModal = false;

    public ?int $deleteId = null;

    public bool $showDeleteModal = false;

    public ?array $selectedMahasiswa = null;

    public function mount(): void
    {
        $this->loadSemesters();

        $this->loadMahasiswa();
    }

    public function loadSemesters(): void
    {
        try {

            $response = Http::get(
                env('STUDENT_SERVICE_URL')
                . '/api/semesters'
            );

            if ($response->successful()) {

                $this->semesters =
                    $response->json('data', []);
            }

        } catch (\Exception $e) {

            $this->semesters = [];
        }
    }

    public function loadMahasiswa(): void
    {
        try {

            $response = Http::get(
                env('STUDENT_SERVICE_URL')
                . '/api/students',
                [
                    'search'      => $this->search,
                    'semester_id' => $this->semesterFilter,
                ]
            );

            if ($response->successful()) {

                $this->mahasiswas =
                    $response->json('data.data', []);
            }

        } catch (\Exception $e) {

            $this->mahasiswas = [];
        }
    }

    public function getImportUrl(): string
    {
        return ImportMahasiswaPage::getUrl();
    }

    public function updatedSearch(): void
    {
        $this->loadMahasiswa();
    }

    public function updatedSemesterFilter(): void
    {
        $this->loadMahasiswa();
    }

    public function viewMahasiswa(int $id): void
    {
        try {

            $response = Http::get(
                env('STUDENT_SERVICE_URL')
                . "/api/students/{$id}"
            );

            if ($response->successful()) {

                $this->selectedMahasiswa =
                    $response->json('data');

                $this->showDetailModal = true;
            }

        } catch (\Exception $e) {

            $this->showDetailModal = false;
        }
    }

    public function openCreateModal(): void
    {
        $this->resetForm();

        $this->showModal = true;
    }

    protected function resetForm(): void
    {
        $this->nim = '';
        $this->nama = '';
        $this->prodi = '';
        $this->fakultas = '';
        $this->kelas = '';
        $this->semester_id = '';
        $this->status_aktif = true;
    }

    public function createMahasiswa(): void
    {
        $this->validate([
            'nim' => 'required',
            'nama' => 'required',
            'prodi' => 'required',
            'fakultas' => 'required',
            'kelas' => 'required',
            'semester_id' => 'required',
        ]);

        try {

            $response = Http::post(
                env('STUDENT_SERVICE_URL')
                . '/api/students',
                [
                    'nim'          => $this->nim,
                    'nama'         => $this->nama,
                    'prodi'        => $this->prodi,
                    'fakultas'     => $this->fakultas,
                    'kelas'        => $this->kelas,
                    'semester_id'  => $this->semester_id,
                    'status_aktif' => $this->status_aktif,
                ]
            );

            if (! $response->successful()) {

                Notification::make()
                    ->title('Gagal menambah mahasiswa')
                    ->danger()
                    ->send();

                return;
            }

            Notification::make()
                ->title('Mahasiswa berhasil ditambahkan')
                ->success()
                ->send();

            $this->showModal = false;

            $this->resetForm();

            $this->loadMahasiswa();

        } catch (\Exception $e) {

            Notification::make()
                ->title($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function editMahasiswa(int $id): void
    {
        try {

            $response = Http::get(
                env('STUDENT_SERVICE_URL')
                . "/api/students/{$id}"
            );

            if (! $response->successful()) {

                Notification::make()
                    ->title('Data mahasiswa tidak ditemukan')
                    ->danger()
                    ->send();

                return;
            }

            $mhs = $response->json('data');

            $this->editId = $mhs['id'];

            $this->nim = $mhs['nim'];

            $this->nama = $mhs['nama'];

            $this->prodi = $mhs['prodi'];

            $this->fakultas = $mhs['fakultas'];

            $this->kelas = $mhs['kelas'];

            $this->semester_id = $mhs['semester_id'];

            $this->status_aktif = $mhs['status_aktif'];

            $this->showEditModal = true;

        } catch (\Exception $e) {

            Notification::make()
                ->title($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function updateMahasiswa(): void
    {
        $this->validate([
            'nim' => 'required',
            'nama' => 'required',
            'prodi' => 'required',
            'fakultas' => 'required',
            'kelas' => 'required',
            'semester_id' => 'required',
        ]);

        try {

            $response = Http::put(
                env('STUDENT_SERVICE_URL')
                . "/api/students/{$this->editId}",
                [
                    'nim'          => $this->nim,
                    'nama'         => $this->nama,
                    'prodi'        => $this->prodi,
                    'fakultas'     => $this->fakultas,
                    'kelas'        => $this->kelas,
                    'semester_id'  => $this->semester_id,
                    'status_aktif' => $this->status_aktif,
                ]
            );

            if (! $response->successful()) {

                Notification::make()
                    ->title('Gagal update mahasiswa')
                    ->danger()
                    ->send();

                return;
            }

            Notification::make()
                ->title('Mahasiswa berhasil diupdate')
                ->success()
                ->send();

            $this->showEditModal = false;

            $this->resetForm();

            $this->loadMahasiswa();

        } catch (\Exception $e) {

            Notification::make()
                ->title($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;

        $this->showDeleteModal = true;
    }

    public function deleteMahasiswa(): void
    {
        try {

            $response = Http::delete(
                env('STUDENT_SERVICE_URL')
                . "/api/students/{$this->deleteId}"
            );

            if (! $response->successful()) {

                Notification::make()
                    ->title('Gagal menghapus mahasiswa')
                    ->danger()
                    ->send();

                return;
            }

            Notification::make()
                ->title('Mahasiswa berhasil dihapus')
                ->success()
                ->send();

            $this->showDeleteModal = false;

            $this->deleteId = null;

            $this->loadMahasiswa();

        } catch (\Exception $e) {

            Notification::make()
                ->title($e->getMessage())
                ->danger()
                ->send();
        }
    }
}