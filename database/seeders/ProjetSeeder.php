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
            ['name' => 'Sales Department'],
            ['name' => 'Technical Support'],
            ['name' => 'Billing Support'],
        ]);
    }
}
