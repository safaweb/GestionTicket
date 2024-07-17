<?php

namespace Database\Seeders;

use App\Models\Qualification;
use Illuminate\Database\Seeder;

class QualificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Qualification::insert([
            ['name' => 'Ticket Support'],
            ['name' => 'Demande De Formation'],
            ['name' => 'Demande D\'Information'],
        ]);
    }
}
