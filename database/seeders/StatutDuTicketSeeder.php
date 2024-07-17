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
            ['id' => StatutDuTicket::OUVERT, 'name' => 'Ouvert'],
            ['id' => StatutDuTicket::EN_COURS, 'name' => 'En cours'],
            ['id' => StatutDuTicket::RESOLU, 'name' => 'Résolu'],
            ['id' => StatutDuTicket::NONRESOLU, 'name' => 'Non Résolu'],
        ]);
    }
}
