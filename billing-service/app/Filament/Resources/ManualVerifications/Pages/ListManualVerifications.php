<?php

namespace App\Filament\Resources\ManualVerifications\Pages;

use App\Filament\Resources\ManualVerifications\ManualVerificationResource;
use Filament\Resources\Pages\ListRecords;


class ListManualVerifications extends ListRecords
{
    protected static string $resource = ManualVerificationResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}