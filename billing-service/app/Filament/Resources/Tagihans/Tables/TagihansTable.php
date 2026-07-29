<?php

namespace App\Filament\Resources\Tagihans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TagihansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('mahasiswa_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('mahasiswa_nim')
                    ->searchable(),
                TextColumn::make('mahasiswa_nama')
                    ->searchable(),
                TextColumn::make('payment_type_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('semester_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('semester_nama')
                    ->searchable(),
                TextColumn::make('nominal')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('jatuh_tempo')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
