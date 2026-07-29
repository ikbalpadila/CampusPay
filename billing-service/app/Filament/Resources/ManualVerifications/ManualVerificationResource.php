<?php

namespace App\Filament\Resources\ManualVerifications;

use App\Filament\Resources\ManualVerifications\Pages\ListManualVerifications;
use App\Models\Tagihan;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Http;

class ManualVerificationResource extends Resource
{
    protected static ?string $model = Tagihan::class;

    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedClipboardDocumentCheck;

    protected static ?string $navigationLabel = 'Verifikasi Pembayaran';

    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): ?string
    {
        return 'Keuangan';
    }

    // Badge merah di sidebar jika ada yang pending
    public static function getNavigationBadge(): ?string
    {
        $count = Tagihan::where('status', 'pending')->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                Tagihan::query()
                    ->where('status', 'pending')
                    ->with('paymentType')
            )
            ->columns([
                TextColumn::make('id')
                    ->label('ID Tagihan')
                    ->sortable(),

                TextColumn::make('mahasiswa_nim')
                    ->label('NIM')
                    ->searchable(),

                TextColumn::make('mahasiswa_nama')
                    ->label('Nama Mahasiswa')
                    ->searchable(),

                TextColumn::make('paymentType.nama')
                    ->label('Jenis Pembayaran'),

                TextColumn::make('semester_nama')
                    ->label('Semester'),

                TextColumn::make('nominal')
                    ->label('Nominal')
                    ->formatStateUsing(
                        fn ($state) =>
                        'Rp ' . number_format($state, 0, ',', '.')
                    ),

                // Tampilkan path bukti dari catatan
                // Ganti kolom catatan
                TextColumn::make('catatan')
                    ->label('Bukti Transfer')
                    ->formatStateUsing(function ($state) {
                        if (!$state) return '—';
                        if (str_starts_with($state, 'BUKTI_FILE:')) {
                            $path = str_replace('BUKTI_FILE:', '', $state);
                            return '📎 ' . basename($path);
                        }
                        return $state;
                    })
                    ->limit(40),

                TextColumn::make('updated_at')
                    ->label('Waktu Upload')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->recordActions([
                // Tambahkan action lihat bukti
                // Ganti action lihat_bukti
                Action::make('lihat_bukti')
                    ->label('Lihat Bukti')
                    ->icon(Heroicon::OutlinedEye)
                    ->color('info')
                    ->url(function (Tagihan $record) {
                        if ($record->catatan &&
                            str_starts_with($record->catatan, 'BUKTI_FILE:')) {
                            $path = str_replace('BUKTI_FILE:', '', $record->catatan);
                            return asset('storage/' . $path);
                        }
                        return '#';
                    })
                    ->openUrlInNewTab()
                    ->visible(
                        fn (Tagihan $record) =>
                        $record->catatan &&
                        str_starts_with($record->catatan, 'BUKTI_FILE:')
                    ),
                        
                Action::make('approve')
                    ->label('Setujui')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Pembayaran')
                    ->modalDescription('Apakah bukti transfer valid dan nominal sesuai?')
                    ->action(function (Tagihan $record) {
                        self::processVerification($record, 'approve', null);
                    }),

                Action::make('reject')
                    ->label('Tolak')
                    ->icon(Heroicon::OutlinedXCircle)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Pembayaran')
                    ->form([
                        Textarea::make('alasan')
                            ->label('Alasan Penolakan')
                            ->required()
                            ->placeholder('Contoh: Nominal tidak sesuai, bukti tidak jelas'),
                    ])
                    ->action(function (Tagihan $record, array $data) {
                        self::processVerification(
                            $record, 'reject', $data['alasan']
                        );
                    }),
            ])
            ->defaultSort('updated_at', 'desc')
            ->poll('15s') // Auto refresh tiap 15 detik
            ->emptyStateHeading('Tidak ada pembayaran pending')
            ->emptyStateDescription('Semua pembayaran sudah diverifikasi.');
    }

    private static function processVerification(
        Tagihan $tagihan,
        string  $action,
        ?string $alasan
    ): void {
        try {
            if ($action === 'approve') {
                // Update langsung di billing database
                $tagihan->update([
                    'status'  => 'lunas',
                    'catatan' => 'Disetujui admin pada ' .
                                 now()->format('d/m/Y H:i'),
                ]);
    
                // Coba notifikasi via Notification Service
                try {
                    Http::timeout(3)->post(
                        env('NOTIFICATION_SERVICE_URL',
                            'http://127.0.0.1:8007') .
                        '/api/notifications/send',
                        [
                            'user_id' => $tagihan->mahasiswa_id,
                            'type'    => 'pembayaran_berhasil',
                            'title'   => '✅ Pembayaran Disetujui',
                            'message' => 'Pembayaran ' .
                                         ($tagihan->paymentType->nama ?? '') .
                                         ' ' . $tagihan->semester_nama .
                                         ' sebesar Rp ' .
                                         number_format($tagihan->nominal, 0, ',', '.') .
                                         ' telah LUNAS.',
                        ]
                    );
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::warning(
                        'Notif service timeout: ' . $e->getMessage()
                    );
                }
    
                // Coba catat transaksi di Transaction Service
                try {
                    Http::timeout(3)->post(
                        env('TRANSACTION_SERVICE_URL',
                            'http://127.0.0.1:8006') .
                        '/api/transactions',
                        [
                            'tagihan_id'   => $tagihan->id,
                            'mahasiswa_id' => $tagihan->mahasiswa_id,
                            'nominal'      => $tagihan->nominal,
                            'metode'       => 'transfer_manual',
                            'status'       => 'success',
                        ]
                    );
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::warning(
                        'Transaction service timeout: ' . $e->getMessage()
                    );
                }
    
                Notification::make()
                    ->title('✅ Pembayaran ' .
                            $tagihan->mahasiswa_nama .
                            ' berhasil disetujui!')
                    ->success()
                    ->send();
    
            } else {
                // Tolak — update status kembali ke belum_bayar
                $tagihan->update([
                    'status'  => 'belum_bayar',
                    'catatan' => 'Ditolak admin: ' . $alasan,
                ]);
    
                // Kirim notifikasi penolakan
                try {
                    Http::timeout(3)->post(
                        env('NOTIFICATION_SERVICE_URL',
                            'http://127.0.0.1:8007') .
                        '/api/notifications/send',
                        [
                            'user_id' => $tagihan->mahasiswa_id,
                            'type'    => 'pembayaran_ditolak',
                            'title'   => '❌ Pembayaran Ditolak',
                            'message' => 'Bukti transfer ditolak admin. ' .
                                         'Alasan: ' . $alasan .
                                         '. Silakan upload ulang.',
                        ]
                    );
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::warning(
                        'Notif service timeout: ' . $e->getMessage()
                    );
                }
    
                Notification::make()
                    ->title('❌ Pembayaran ditolak')
                    ->body('Alasan: ' . $alasan)
                    ->warning()
                    ->send();
            }
    
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }


    public static function getPages(): array
    {
        return [
            'index' => ListManualVerifications::route('/'),
        ];
    }
}