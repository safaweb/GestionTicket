<?php

namespace Database\Seeders;

use App\Models\Societe;
use Illuminate\Database\Seeder;

class SocieteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Societe::insert([
            ['id' => 1,'name' => 'SOTUFAB'],
            ['id' => 2,'name' => 'Coprofa'],
        ]);
    }
}
