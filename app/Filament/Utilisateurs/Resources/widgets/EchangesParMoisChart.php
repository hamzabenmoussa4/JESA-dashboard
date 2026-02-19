<?php

namespace App\Filament\Utilisateurs\Resources\UtilisateurResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Interlocuteur;
use App\Models\Echange;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EchangesParMoisChart extends ChartWidget
{
    protected static ?string $heading = 'Évolution des échanges par mois';

    protected static ?int $sort = 2;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $userId = Auth::id();

        // Récupérer les interlocuteurs de l'utilisateur
        $interlocuteurIds = Interlocuteur::where('user_id', $userId)->pluck('id');

        // Récupérer les échanges groupés par mois (12 derniers mois)
        $echanges = Echange::whereIn('interlocuteur_id', $interlocuteurIds)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as mois, COUNT(*) as total")
            ->groupBy('mois')
            ->orderBy('mois')
            ->pluck('total', 'mois')
            ->toArray();

        $labels = [];
        $data = [];

        $start = now()->subMonths(11)->startOfMonth();

        for ($i = 0; $i < 12; $i++) {
            $mois = $start->copy()->addMonths($i)->format('Y-m');
            $labels[] = $mois;
            $data[] = $echanges[$mois] ?? 0;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Échanges',
                    'data' => $data,
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
        ];
    }
}



