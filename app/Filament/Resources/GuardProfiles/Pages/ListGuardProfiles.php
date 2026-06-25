<?php

namespace App\Filament\Resources\GuardProfiles\Pages;

use App\Filament\Resources\GuardProfiles\GuardProfileResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGuardProfiles extends ListRecords
{
    protected static string $resource = GuardProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
