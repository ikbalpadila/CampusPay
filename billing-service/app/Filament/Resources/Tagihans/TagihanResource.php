<?php

namespace App\Filament\Resources\Tagihans;

use App\Filament\Resources\Tagihans\Pages\CreateTagihan;
use App\Filament\Resources\Tagihans\Pages\EditTagihan;
use App\Filament\Resources\Tagihans\Pages\ListTagihans;
use App\Filament\Resources\Tagihans\Pages\ViewTagihan;
use App\Models\PaymentType;
use App\Models\Tagihan;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;

class TagihanResource extends Resource
{
    protected static ?string $model = Tagihan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $navigationLabel = 'Kelola Tagihan';

    protected static ?string $recordTitleAttribute = 'mahasiswa_nama';

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return 'Keuangan';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Data Tagihan')
                ->schema([
                    TextInput::make('mahasiswa_id')
                        ->label('ID Mahasiswa')
                        ->required()
                        ->numeric(),

                    TextInput::make('mahasiswa_nim')
                        ->label('NIM')
                        ->required(),

                    TextInput::make('mahasiswa_nama')
                        ->label('Nama Mahasiswa')
                        ->required(),

                    Select::make('payment_type_id')
                        ->label('Jenis Pembayaran')
                        ->options(
                            fn () => PaymentType::where('is_aktif', true)
                                               ->pluck('nama', 'id')
                                               ->toArray()
                        )
                        ->required(),

                    TextInput::make('semester_id')
                        ->label('Semester ID')
                        ->required()
                        ->numeric(),

                    TextInput::make('semester_nama')
                        ->label('Nama Semester')
                        ->required(),

                    TextInput::make('nominal')
                        ->label('Nominal')
                        ->required()
                        ->numeric()
                        ->prefix('Rp'),

                    DatePicker::make('jatuh_tempo')
                        ->label('Jatuh Tempo')
                        ->required(),

                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'belum_bayar' => 'Belum Bayar',
                            'pending'     => 'Pending',
                            'lunas'       => 'Lunas',
                        ])
                        ->default('belum_bayar')
                        ->required(),

                    Textarea::make('catatan')
                        ->label('Catatan')
                        ->nullable(),

                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('mahasiswa_nim')
                    ->label('NIM')
                    ->searchable(),

                TextColumn::make('mahasiswa_nama')
                    ->label('Nama')
                    ->searchable(),

                TextColumn::make('paymentType.nama')
                    ->label('Jenis'),

                TextColumn::make('semester_nama')
                    ->label('Semester'),

                TextColumn::make('nominal')
                    ->label('Nominal')
                    ->formatStateUsing(
                        fn ($state) =>
                        'Rp ' . number_format($state, 0, ',', '.')
                    ),

                TextColumn::make('jatuh_tempo')
                    ->label('Jatuh Tempo')
                    ->date('d/m/Y'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(
                        fn (string $state): string => match ($state) {
                            'belum_bayar' => 'danger',
                            'pending'     => 'warning',
                            'lunas'       => 'success',
                            default       => 'gray',
                        }
                    )
                    ->formatStateUsing(
                        fn ($state) => match ($state) {
                            'belum_bayar' => 'Belum Bayar',
                            'pending'     => 'Pending',
                            'lunas'       => 'Lunas',
                            default       => $state,
                        }
                    ),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'belum_bayar' => 'Belum Bayar',
                        'pending'     => 'Pending',
                        'lunas'       => 'Lunas',
                    ]),

                SelectFilter::make('payment_type_id')
                    ->label('Jenis Pembayaran')
                    ->options(
                        fn () => PaymentType::pluck('nama', 'id')->toArray()
                    ),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListTagihans::route('/'),
            'create' => CreateTagihan::route('/create'),
            'view'   => ViewTagihan::route('/{record}'),
            'edit'   => EditTagihan::route('/{record}/edit'),
        ];
    }
}