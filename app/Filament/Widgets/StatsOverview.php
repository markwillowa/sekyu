<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Security Guards', '0')
                ->description('Registered applicant accounts'),

            Stat::make('Agencies', '0')
                ->description('Registered security agencies'),

            Stat::make('Open Jobs', '0')
                ->description('Active job listings'),

            Stat::make('Applications', '0')
                ->description('Submitted applications'),
        ];
    }
}
