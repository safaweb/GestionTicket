<?php

namespace Database\Seeders;

use App\Models\Priority;
use Illuminate\Database\Seeder;

class PrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Priority::create(['id' => Priority::CRITICAL, 'name' => 'Urgent']);
        Priority::create(['id' => Priority::HIGHT, 'name' => 'Élevé']);
        Priority::create(['id' => Priority::MEDIUM, 'name' => 'Moyen']);
        Priority::create(['id' => Priority::LOW, 'name' => 'Faible']);
        Priority::create(['id' => Priority::ENHANCEMENT, 'name' => 'Amélioration / Demande de fonctionnalité']);
    }
}
