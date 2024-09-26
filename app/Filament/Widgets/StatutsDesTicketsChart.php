<?php

namespace App\Filament\Widgets;

use App\Models\StatutDuTicket;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class StatutsDesTicketsChart extends ApexChartWidget
{
    /** Chart Id
     * @var string*/
    protected static string $chartId = 'statutsDesTicketsChart';

    /** Widget Title
     * @var string|null*/
    protected static ?string $heading = 'Statuts Des Tickets';

    /**Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     * @return array */
    protected function getOptions(): array
    {
        $user = Auth::user();

        // Initialize query for StatutDuTicket
        $query = StatutDuTicket::query();

        // Check the user's role and filter data accordingly
        if ($user->hasRole('Super Admin')) {
            // Super Admins can see all tickets
            $statutsDesTickets = $query->withCount('tickets')->get();
        } elseif ($user->hasRole('Chef Projet')) {
        $statutsDesTickets = $query->withCount('tickets')->get();
         /*   // Get the IDs of the projects related to the user
            $projectIds = $user->projects()->pluck('id')->toArray();
        
            // Query to filter tickets based on the projects associated with the user
            $statutsDesTickets = $query->whereHas('tickets', function ($q) use ($projectIds) {
                $q->whereIn('project_id', $projectIds);
            })->withCount(['tickets' => function ($q) use ($projectIds) {
                $q->whereIn('project_id', $projectIds);
            }])->get();*/
        } elseif ($user->hasRole('Collaborateur')) {
            // Collaborateur can only see tickets where they are the responsible_id
            $statutsDesTickets = $query->whereHas('tickets', function ($q) use ($user) {
                $q->where('responsible_id', $user->id);
            })->withCount(['tickets' => function ($q) use ($user) {
                $q->where('responsible_id', $user->id);
            }])->get();
        } else {
            // Clients or other roles can only see tickets they created (as owner)
            $statutsDesTickets = $query->whereHas('tickets', function ($q) use ($user) {
                $q->where('owner_id', $user->id);
            })->withCount(['tickets' => function ($q) use ($user) {
                $q->where('owner_id', $user->id);
            }])->get();
        }

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
        /*$statutsDesTickets = StatutDuTicket::select('id', 'name')->withCount(['tickets'])->get();
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
        ];*/
    }
}

