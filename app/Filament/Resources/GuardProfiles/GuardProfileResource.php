<?php

namespace App\Filament\Resources\GuardProfiles;

use App\Filament\Resources\GuardProfiles\Pages\CreateGuardProfile;
use App\Filament\Resources\GuardProfiles\Pages\EditGuardProfile;
use App\Filament\Resources\GuardProfiles\Pages\ListGuardProfiles;
use App\Filament\Resources\GuardProfiles\Schemas\GuardProfileForm;
use App\Filament\Resources\GuardProfiles\Tables\GuardProfilesTable;
use App\Models\GuardProfile;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GuardProfileResource extends Resource
{
    protected static ?string $model = GuardProfile::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return GuardProfileForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GuardProfilesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGuardProfiles::route('/'),
            'create' => CreateGuardProfile::route('/create'),
            'edit' => EditGuardProfile::route('/{record}/edit'),
        ];
    }
}
