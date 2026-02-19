<?php

// namespace App\Filament\Utilisateurs\Resources\UtilisateurResource\Widgets;

// use Filament\Widgets\StatsOverviewWidget as BaseWidget;
// use Filament\Widgets\StatsOverviewWidget\Stat;
// use Filament\Support\Enums\IconPosition;
// use App\Models\Interlocuteur;
// use App\Models\Echange;
// use App\Models\Meeting;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Carbon;

// class InterlocuteurBadges extends BaseWidget
// {
//     protected ?string $heading = 'Mes statistiques';

//     // auto-refresh
//     protected static ?string $pollingInterval = '15s';

//     protected function getCards(): array
//     {
//         $userId = Auth::id();

//         // Interlocuteurs de l'utilisateur
//         $interlocuteurIds = Interlocuteur::where('user_id', $userId)->pluck('id');

//         // Totaux
//         $totalInterlocuteurs = $interlocuteurIds->count();
//         $totalEchanges      = Echange::whereIn('interlocuteur_id', $interlocuteurIds)->count();

//         // Meetings de ce mois
//         $totalMeetingsThisMonth = Meeting::where('user_id', $userId)
//             ->whereMonth('date_of_meeting', Carbon::now()->month)
//             ->whereYear('date_of_meeting', Carbon::now()->year)
//             ->count();

//         // ➕ Sparklines
//         $sparkInterlocuteurs = $this->sparklineLastDaysForModel(
//             modelClass: Interlocuteur::class,
//             baseQuery: fn ($q) => $q->where('user_id', $userId),
//             dateColumn: 'created_at',
//             days: 14
//         );

//         $sparkEchanges = $this->sparklineLastDaysForModel(
//             modelClass: Echange::class,
//             baseQuery: fn ($q) => $q->whereIn('interlocuteur_id', $interlocuteurIds),
//             dateColumn: 'created_at',
//             days: 14
//         );

//         $sparkMeetings = $this->sparklineLastDaysForModel(
//             modelClass: Meeting::class,
//             baseQuery: fn ($q) => $q->where('user_id', $userId),
//             dateColumn: 'date_of_meeting',
//             days: 14
//         );

//         $fmt = fn (int $n) => number_format($n, 0, ',', ' ');

//         return [
//             Stat::make('Mes interlocuteurs', $fmt($totalInterlocuteurs))
//                 ->description('Total suivi')
//                 ->descriptionIcon('heroicon-m-users', IconPosition::Before)
//                 ->color('primary')
//                 ->chart($sparkInterlocuteurs)
//                 ->extraAttributes($this->dynamicCardAttrs()),

//             Stat::make('Mes échanges', $fmt($totalEchanges))
//                 ->description('Toutes natures confondues')
//                 ->descriptionIcon('heroicon-m-arrows-right-left', IconPosition::Before)
//                 ->color('success')
//                 ->chart($sparkEchanges)
//                 ->extraAttributes($this->dynamicCardAttrs()),

//             Stat::make('Mes meetings (ce mois)', $fmt($totalMeetingsThisMonth))
//                 ->description('Réunions planifiées')
//                 ->descriptionIcon('heroicon-m-calendar', IconPosition::Before)
//                 ->color('warning')
//                 ->chart($sparkMeetings)
//                 ->extraAttributes($this->dynamicCardAttrs()),
//         ];
//     }

//     /**
//      * Mini-graphique : compte par jour sur N derniers jours.
//      */
//     protected function sparklineLastDaysForModel(
//         string $modelClass,
//         ?callable $baseQuery = null,
//         string $dateColumn = 'created_at',
//         int $days = 14
//     ): array {
//         $points = [];
//         $start = Carbon::today()->subDays($days - 1);
//         for ($i = 0; $i < $days; $i++) {
//             $day = (clone $start)->addDays($i);
//             $q = ($modelClass)::query();
//             if ($baseQuery) {
//                 $baseQuery($q);
//             }
//             $points[] = (clone $q)->whereDate($dateColumn, $day)->count();
//         }
//         return $points;
//     }

//     protected function dynamicCardAttrs(array $extra = []): array
//     {
//         return array_merge([
//             'class' => 'transition transform hover:scale-105 hover:shadow-lg cursor-pointer',
//         ], $extra);
//     }
// }



namespace App\Filament\Utilisateurs\Resources\UtilisateurResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Support\Enums\IconPosition;
use App\Models\Interlocuteur;
use App\Models\Echange;
use App\Models\Meeting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class InterlocuteurBadges extends BaseWidget
{
    protected ?string $heading = 'My statistics';

    // auto-refresh
    protected static ?string $pollingInterval = '15s';

    protected function getCards(): array
    {
        $userId = Auth::id();

        // User's contacts (Interlocuteurs)
        $interlocuteurIds = Interlocuteur::where('user_id', $userId)->pluck('id');

        // Totals
        $totalInterlocuteurs = $interlocuteurIds->count();
        $totalEchanges      = Echange::whereIn('interlocuteur_id', $interlocuteurIds)->count();

        // Meetings this month
        $totalMeetingsThisMonth = Meeting::where('user_id', $userId)
            ->whereMonth('date_of_meeting', Carbon::now()->month)
            ->whereYear('date_of_meeting', Carbon::now()->year)
            ->count();

        // ➕ Sparklines
        $sparkInterlocuteurs = $this->sparklineLastDaysForModel(
            modelClass: Interlocuteur::class,
            baseQuery: fn ($q) => $q->where('user_id', $userId),
            dateColumn: 'created_at',
            days: 14
        );

        $sparkExchanges = $this->sparklineLastDaysForModel(
            modelClass: Echange::class,
            baseQuery: fn ($q) => $q->whereIn('interlocuteur_id', $interlocuteurIds),
            dateColumn: 'created_at',
            days: 14
        );

        $sparkMeetings = $this->sparklineLastDaysForModel(
            modelClass: Meeting::class,
            baseQuery: fn ($q) => $q->where('user_id', $userId),
            dateColumn: 'date_of_meeting',
            days: 14
        );

        $fmt = fn (int $n) => number_format($n, 0, ',', ' ');

        return [
            Stat::make('My contacts', $fmt($totalInterlocuteurs))
                ->description('Total tracked')
                ->descriptionIcon('heroicon-m-users', IconPosition::Before)
                ->color('primary')
                ->chart($sparkInterlocuteurs)
                ->extraAttributes($this->dynamicCardAttrs()),

            Stat::make('My exchanges', $fmt($totalEchanges))
                ->description('All types combined')
                ->descriptionIcon('heroicon-m-arrows-right-left', IconPosition::Before)
                ->color('success')
                ->chart($sparkExchanges)
                ->extraAttributes($this->dynamicCardAttrs()),

            Stat::make('My meetings (this month)', $fmt($totalMeetingsThisMonth))
                ->description('Scheduled meetings')
                ->descriptionIcon('heroicon-m-calendar', IconPosition::Before)
                ->color('warning')
                ->chart($sparkMeetings)
                ->extraAttributes($this->dynamicCardAttrs()),
        ];
    }

    /**
     * Mini-chart: count per day over the last N days.
     */
    protected function sparklineLastDaysForModel(
        string $modelClass,
        ?callable $baseQuery = null,
        string $dateColumn = 'created_at',
        int $days = 14
    ): array {
        $points = [];
        $start = Carbon::today()->subDays($days - 1);
        for ($i = 0; $i < $days; $i++) {
            $day = (clone $start)->addDays($i);
            $q = ($modelClass)::query();
            if ($baseQuery) {
                $baseQuery($q);
            }
            $points[] = (clone $q)->whereDate($dateColumn, $day)->count();
        }
        return $points;
    }

    protected function dynamicCardAttrs(array $extra = []): array
    {
        return array_merge([
            'class' => 'transition transform hover:scale-105 hover:shadow-lg cursor-pointer',
        ], $extra);
    }
}
