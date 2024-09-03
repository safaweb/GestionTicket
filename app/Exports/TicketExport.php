<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TicketExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Ticket::query()
            ->select([
                'tickets.title',
                'projets.name as projet_name',
                'owners.name as owner_name',
                'responsibles.name as responsible_name',
                'pays.name as pay_name',
                'validation.name as validation_name',
                'statuts_des_tickets.name as statut_name',
                'tickets.created_at'
            ])
            ->join('projets', 'tickets.projet_id', '=', 'projets.id')
            ->join('users as owners', 'tickets.owner_id', '=', 'owners.id')
            ->leftJoin('users as responsibles', 'tickets.responsible_id', '=', 'responsibles.id')
            ->join('pays', 'projets.pays_id', '=', 'pays.id')
            ->join('validation', 'tickets.validation_id', '=', 'validation.id')
            ->join('statuts_des_tickets', 'tickets.statuts_des_tickets_id', '=', 'statuts_des_tickets.id')
            ->get();
    }
    public function headings(): array
    {
        return [
            'Titre',
            'Projet',
            'Client',
            'Responsable',
            'Pay',
            'Validation',
            'Statut',
            'Date de creation',
        ];
    }
}
