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
            ['name' => 'SOTUFAB', 'pays_id' => 1],
            ['name' => 'Coprofa', 'pays_id' => 1],
        ]);
    }
}
