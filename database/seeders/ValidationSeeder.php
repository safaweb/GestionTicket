<?php

namespace Database\Seeders;

use App\Models\Validation;
use Illuminate\Database\Seeder;

class ValidationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Validation::insert([
            ['id' => Validation::ACCEPTER, 'name' => 'Accepter'],
            ['id' => Validation::REFUSER, 'name' => 'Refuser'],
            ['id' => Validation::TERMINER, 'name' => 'Terminer'],
            ['id' => Validation::RIEN, 'name' => '-'],
        ]);
    }
}
