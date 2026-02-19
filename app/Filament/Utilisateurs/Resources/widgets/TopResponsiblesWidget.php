<?php

namespace App\Filament\Utilisateurs\Resources\Widgets;

use App\Models\Minute;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class UserTopResponsiblesWidget extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    /** Supprimer le heading de la carte du widget */
    protected static ?string $heading = null;
    protected function hasHeading(): bool { return false; }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query(function (): Builder {
                return Minute::query()
                    ->select([
                        DB::raw('responsible'),
                        DB::raw('COUNT(*) as total'),
                        DB::raw('MIN(id) as id'),
                    ])
                    ->groupBy('responsible')
                    ->orderByDesc('total')
                    ->limit(5);
            })

            ->columns([
                TextColumn::make('rank')
                    ->label('')
                    ->rowIndex()
                    ->formatStateUsing(function (?string $state) {
                        $i = (int) $state;
                        return match ($i) {
                            1 => 'Leading Responsible',
                            2 => '2nd Leading',
                            3 => '3rd Leading',
                            default => '#' . $i,
                        };
                    })
                    ->alignCenter()
                    ->sortable(false)
                    ->extraAttributes(['class' => 'w-10 text-sm text-gray-700']),

                TextColumn::make('responsible')
                    ->label('Responsible')
                    ->formatStateUsing(function ($state) {
                        $initials = strtoupper(mb_substr(trim((string) $state), 0, 2));
                        return <<<HTML
<div class="flex items-center gap-3">
  <div class="inline-flex items-center justify-center h-7 w-7 rounded-full bg-gray-200 text-gray-700 text-[11px] font-semibold">
    {$initials}
  </div>
  <span class="text-gray-900 font-medium">{$state}</span>
</div>
HTML;
                    })
                    ->html()
                    //->searchable()
                    ->sortable(),

                BadgeColumn::make('total')
                    ->label('Minutes')
                    ->colors([
                        'success' => fn ($state) => $state >= 10,
                        'warning' => fn ($state) => $state >= 5 && $state < 10,
                        'gray'    => fn ($state) => $state < 5,
                    ])
                    ->formatStateUsing(fn ($state) => (string) $state)
                    ->sortable(),
            ])

            ->striped()
            ->paginated(false)
            ->defaultSort('total', 'desc')

            ->emptyStateHeading('No responsible yet')
            ->emptyStateDescription('Minutes will appear here once recorded.');
    }
}
