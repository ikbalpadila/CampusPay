<?php

namespace App\Filament\Resources\Tagihans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TagihanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('mahasiswa_id')
                    ->required()
                    ->numeric(),
                TextInput::make('mahasiswa_nim')
                    ->required(),
                TextInput::make('mahasiswa_nama')
                    ->required(),
                TextInput::make('payment_type_id')
                    ->required()
                    ->numeric(),
                TextInput::make('semester_id')
                    ->required()
                    ->numeric(),
                TextInput::make('semester_nama')
                    ->required(),
                TextInput::make('nominal')
                    ->required()
                    ->numeric(),
                DatePicker::make('jatuh_tempo')
                    ->required(),
                Select::make('status')
                    ->options(['belum_bayar' => 'Belum bayar', 'pending' => 'Pending', 'lunas' => 'Lunas'])
                    ->default('belum_bayar')
                    ->required(),
                Textarea::make('catatan')
                    ->columnSpanFull(),
            ]);
    }
}
