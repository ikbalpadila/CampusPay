<?php

namespace App\Filament\Resources\Tagihans\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TagihanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('mahasiswa_id')
                    ->numeric(),
                TextEntry::make('mahasiswa_nim'),
                TextEntry::make('mahasiswa_nama'),
                TextEntry::make('payment_type_id')
                    ->numeric(),
                TextEntry::make('semester_id')
                    ->numeric(),
                TextEntry::make('semester_nama'),
                TextEntry::make('nominal')
                    ->numeric(),
                TextEntry::make('jatuh_tempo')
                    ->date(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('catatan')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
