<?php

namespace App\Filament\SuperAdmin\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Http;

class SemesterPage extends Page
{
    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedAcademicCap;

    protected static ?string $navigationLabel = 'Kelola Semester';

    protected static ?string $title = 'Manajemen Semester';
    
    protected static ?int $navigationSort = 3;

    protected string $view =
        'filament.superadmin.pages.semester-page';

    public array $semesters = [];

    public string $message = '';

    public bool $showEditModal = false;

    public ?int $editId = null;

    public string $editNama = '';

    public string $editTahunAjaran = '';

    public bool $showCreateModal = false;

    public string $nama = '';

    public string $tahun_ajaran = '';

    public static function getNavigationGroup(): ?string
    {
        return 'Manajemen Akademik';
    }

    public function mount(): void
    {
        $this->loadSemesters();
    }

    public function loadSemesters(): void
    {
        try {

            $response = Http::timeout(5)->get(
                env('STUDENT_SERVICE_URL', 'http://127.0.0.1:8002')
                . '/api/semesters'
            );

            if ($response->successful()) {

                $this->semesters =
                    $response->json('data', []);

            }

        } catch (\Exception $e) {

            $this->message =
                'Student Service tidak tersedia';

        }
    }

    public function setAktif(int $id): void
    {
        try {

            $response = Http::post(
                env('STUDENT_SERVICE_URL', 'http://127.0.0.1:8002')
                . "/api/semesters/{$id}/set-aktif"
            );

            if ($response->successful()) {

                $this->message =
                    'Semester berhasil diaktifkan';

                $this->loadSemesters();

            }

        } catch (\Exception $e) {

            $this->message =
                'Gagal mengaktifkan semester';

        }
    }

    public function editSemester(int $id): void
    {
        $semester = collect($this->semesters)
            ->firstWhere('id', $id);

        if (!$semester) {
            return;
        }

        $this->editId = $semester['id'];

        $this->editNama = $semester['nama'];

        $this->editTahunAjaran =
            $semester['tahun_ajaran'];

        $this->showEditModal = true;
    }

    public function updateSemester(): void
    {
        try {

            $response = Http::put(
                env('STUDENT_SERVICE_URL', 'http://127.0.0.1:8002')
                . "/api/semesters/{$this->editId}",
                [
                    'nama' => $this->editNama,
                    'tahun_ajaran' => $this->editTahunAjaran,
                ]
            );

            if ($response->successful()) {

                $this->message =
                    'Semester berhasil diperbarui';

                $this->showEditModal = false;

                $this->loadSemesters();
            }

        } catch (\Exception $e) {

            $this->message =
                'Gagal update semester';

        }
    }

    public function deleteSemester(int $id): void
    {
        try {

            Http::delete(
                env('STUDENT_SERVICE_URL', 'http://127.0.0.1:8002')
                . "/api/semesters/{$id}"
            );

            $this->message =
                'Semester berhasil dihapus';

            $this->loadSemesters();

        } catch (\Exception $e) {

            $this->message =
                'Gagal menghapus semester';

        }
    }

    public function openCreateModal(): void
    {
        $this->nama = '';
        $this->tahun_ajaran = '';
        $this->showCreateModal = true;
    }

    public function createSemester(): void
    {
        try {

            $response = Http::post(
                env('STUDENT_SERVICE_URL', 'http://127.0.0.1:8002')
                . '/api/semesters',
                [
                    'nama' => $this->nama,
                    'tahun_ajaran' => $this->tahun_ajaran,
                ]
            );

            if (! $response->successful()) {

                $this->message = 'Gagal menambah semester';

                return;
            }

            $this->message = 'Semester berhasil ditambahkan';

            $this->showCreateModal = false;

            $this->nama = '';

            $this->tahun_ajaran = '';

            $this->loadSemesters();

        } catch (\Exception $e) {

            $this->message = $e->getMessage();

        }
    }
}