<?php
/*
Summary:
This Filament widget displays a user statistics overview in the admin dashboard.  
It always excludes the user with email admin@admin.com.  
Three statistics are calculated and displayed:  
- Total users (excluding admin)  
- Number of users created in the current month  
- Number of users created today  
Each stat has a color and a description for clarity.  
*/

namespace App\Filament\Resources\AdminResource\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Support\Enums\IconPosition;
use App\Models\User;
use Illuminate\Support\Carbon;

class UserStatsWidget extends StatsOverviewWidget
{
    // Auto-refresh the cards (without page reload)
    protected static ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        $totalUsers = User::where('email', '!=', 'admin@admin.com')->count();

        $monthUsers = User::where('email', '!=', 'admin@admin.com')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        $todayUsers = User::where('email', '!=', 'admin@admin.com')
            ->whereDate('created_at', Carbon::today())
            ->count();

        // Sparkline data (for visual trends, doesn’t affect main numbers)
        $spark7Days   = $this->sparklineLastDays(7);
        $spark30Days  = $this->sparklineLastDays(30, groupByMonth: false);
        $sparkToday   = $this->sparklineTodayHours();

        return [
            Stat::make('Total Users', $totalUsers)
                ->description('Excludes admin@admin.com')
                ->descriptionIcon('heroicon-m-user-group', IconPosition::Before)
                ->color('success')
                ->chart($spark30Days) // 30-day trend
                ->extraAttributes([
                    'class' => 'transition transform hover:scale-105 hover:shadow-lg cursor-pointer',
                ]),

            Stat::make('This Month', $monthUsers)
                ->description('Users added in ' . Carbon::now()->translatedFormat('F'))
                ->descriptionIcon('heroicon-m-calendar-days', IconPosition::Before)
                ->color('info')
                ->chart($spark7Days) // last 7 days trend
                ->extraAttributes([
                    'class' => 'transition transform hover:scale-105 hover:shadow-lg',
                ]),

            Stat::make('Today', $todayUsers)
                ->description("Created today")
                ->descriptionIcon('heroicon-m-bolt', IconPosition::Before)
                ->color('warning')
                ->chart($sparkToday) // hourly activity today
                ->extraAttributes([
                    'class' => 'transition transform hover:scale-105 hover:shadow-lg',
                ]),
        ];
    }

    /**
     * Sparkline: number of users created per day for the last N days.
     * (Excludes admin, doesn’t affect main stats)
     */
    protected function sparklineLastDays(int $days = 7, bool $groupByMonth = false): array
    {
        $points = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $count = User::where('email', '!=', 'admin@admin.com')
                ->whereDate('created_at', $date)
                ->count();
            $points[] = $count;
        }
        return $points;
    }

    /**
     * Sparkline of today’s creations, hour by hour (0–23).
     */
    protected function sparklineTodayHours(): array
    {
        $points = [];
        $start = Carbon::today();
        for ($h = 0; $h < 24; $h++) {
            $from = (clone $start)->addHours($h);
            $to   = (clone $from)->addHour();
            $count = User::where('email', '!=', 'admin@admin.com')
                ->whereBetween('created_at', [$from, $to])
                ->count();
            $points[] = $count;
        }
        return $points;
    }
}
