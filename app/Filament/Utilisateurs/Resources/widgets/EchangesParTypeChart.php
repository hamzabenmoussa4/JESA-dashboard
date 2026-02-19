<?php

namespace App\Filament\Utilisateurs\Resources\UtilisateurResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Interlocuteur;
use App\Models\Echange;
use Illuminate\Support\Facades\Auth;

class EchangesParTypeChart extends ChartWidget
{
    protected static ?string $heading = 'Répartition des échanges par type';

    protected static ?int $sort = 1;

    protected function getType(): string
    {
        return 'doughnut'; // tu peux aussi mettre 'bar', 'pie', 'line', etc.
    }

    protected function getData(): array
    {
        $userId = Auth::id();

        // Obtenir les ID des interlocuteurs liés à l'utilisateur connecté
        $interlocuteurIds = Interlocuteur::where('user_id', $userId)->pluck('id');

        // Regrouper les échanges par type
        $echangesParType = Echange::whereIn('interlocuteur_id', $interlocuteurIds)
            ->selectRaw('type, COUNT(*) as total')
            ->groupBy('type')
            ->pluck('total', 'type')
            ->toArray();

        // Types possibles
        $types = ['appel', 'email', 'réunion'];
        $labels = [];
        $data = [];

        foreach ($types as $type) {
            $labels[] = ucfirst($type); // ex: "Appel", "Email", "Réunion"
            $data[] = $echangesParType[$type] ?? 0;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Nombre d’échanges',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                    ],
                ],
            ],
        ];
    }
}
