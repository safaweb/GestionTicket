<?php

namespace Database\Seeders;

use App\Models\Projet;
use Illuminate\Database\Seeder;

class ProjetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Projet::insert([
            ['name' => 'Gestion Commerciale', 'pays_id' => 1, 'societe_id' =>1],
            ['name' => 'Production', 'pays_id' => 1, 'societe_id' =>2],
            ['name' => 'Application mobile', 'pays_id' => 1, 'societe_id' =>2],
            ['name' => 'Site Web', 'pays_id' => 1, 'societe_id' =>1],
        ]);
    }
}
