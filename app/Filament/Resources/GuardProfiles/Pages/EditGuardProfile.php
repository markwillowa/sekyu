<?php

namespace App\Filament\Resources\GuardProfiles\Pages;

use App\Filament\Resources\GuardProfiles\GuardProfileResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGuardProfile extends EditRecord
{
    protected static string $resource = GuardProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
