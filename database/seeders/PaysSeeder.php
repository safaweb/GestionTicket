<?php

namespace Database\Seeders;

use App\Models\Pays;
use Illuminate\Database\Seeder;

class PaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pays::insert([
            ['name' => 'Tunisie'],
            ['name' => 'Maghreb'],
            ['name' => 'Sénégal'],
        ]);
    }
}
