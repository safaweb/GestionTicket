<?php

namespace Database\Seeders;

use App\Models\StatutDuTicket;
use Illuminate\Database\Seeder;

class StatutDuTicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StatutDuTicket::insert([
            ['id' => StatutDuTicket::OPEN, 'name' => 'Open'],
            ['id' => StatutDuTicket::ASSIGNED, 'name' => 'Assigned'],
            ['id' => StatutDuTicket::IN_PROGRESS, 'name' => 'In Progress'],
            ['id' => StatutDuTicket::ON_HOLD, 'name' => 'On Hold'],
            ['id' => StatutDuTicket::ESCALATED, 'name' => 'Escalated'],
            ['id' => StatutDuTicket::PENDING_CUSTOMER_RESPONSE, 'name' => 'Pending Customer Response'],
            ['id' => StatutDuTicket::RESOLVED, 'name' => 'Resolved'],
            ['id' => StatutDuTicket::CLOSED, 'name' => 'Closed'],
        ]);
    }
}
