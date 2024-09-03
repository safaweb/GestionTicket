<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromCollection;

class TicketExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Ticket::all();
    }
}
