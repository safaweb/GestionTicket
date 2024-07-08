<?php

namespace App\Filament\Widgets;

use App\Models\StatutDuTicket;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class StatutsDesTicketsChart extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static string $chartId = 'statutsDesTicketsChart';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Statuts Des Tickets';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $statutsDesTickets = StatutDuTicket::select('id', 'name')->withCount(['tickets'])->get();
        return [
            'chart' => [
                'type' => 'pie',
                'height' => 300,
            ],
            'series' => $statutsDesTickets->pluck('tickets_count')->toArray(),
            'labels' => $statutsDesTickets->pluck('name')->toArray(),
            'legend' => [
                'labels' => [
                    'colors' => '#9ca3af',
                    'fontWeight' => 600,
                ],
            ],
        ];
    }
}
