<?php

namespace App\Filament\Utilisateurs\Resources\MinuteResource\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets\TopResponsiblesWidget;
use App\Filament\Widgets\MinutesPerMonthChart;

class MinutesAnalytics extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static string $view = 'filament.pages.minutes-analytics';

    protected static ?string $title = 'Minutes Analytics';
    protected static ?string $navigationLabel = 'Analytics';

    public function getWidgets(): array
    {
        return [
           // MinutesPerMonthChart::class,
            TopResponsiblesWidget::class,
        ];
    }
}
