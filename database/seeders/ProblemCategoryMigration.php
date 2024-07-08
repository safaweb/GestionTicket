<?php

namespace Database\Seeders;

use App\Models\ProblemCategory;
use Illuminate\Database\Seeder;

class ProblemCategoryMigration extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProblemCategory::insert([
            [
                'projet_id' => 1,
                'name' => 'Problem One at Sales Department',
            ],
            [
                'projet_id' => 1,
                'name' => 'Problem Two at Sales Department',
            ],
            [
                'projet_id' => 2,
                'name' => 'Problem One at Technical Support',
            ],
            [
                'projet_id' => 3,
                'name' => 'Problem One at Billing Support',
            ],
        ]);
    }
}
