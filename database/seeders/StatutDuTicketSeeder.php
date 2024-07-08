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
            ['id' => StatutDuTicket::ASSIGNE, 'name' => 'Assigné(e)'],
            ['id' => StatutDuTicket::EN_COURS, 'name' => 'En cours'],
            ['id' => StatutDuTicket::EN_ATTENTE, 'name' => 'En attente'],
            ['id' => StatutDuTicket::ESCALADE, 'name' => 'Escaladé'],
            ['id' => StatutDuTicket::EN_ATTENTE_DE_LA_REPONSE_DU_CLIENT, 'name' => 'En attente de la réponse du client'],
            ['id' => StatutDuTicket::RESOLU, 'name' => 'Résolu'],
            ['id' => StatutDuTicket::FERME, 'name' => 'Fermé'],
        ]);
    }
}
