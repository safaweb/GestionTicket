<?php

namespace Database\Seeders;

use App\Models\Ticket;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ticket::create([
            'priority_id' => 1,
            'pays_id' => 1,
            'projet_id'   => 1,
            'owner_id'  => 1,
            'problem_category_id' => 1,
            'title' => 'This is a sample ticket',
            'description' => 'This is a descriptions',
            'statuts_des_tickets_id' => '1',
        ]);
    }
}
