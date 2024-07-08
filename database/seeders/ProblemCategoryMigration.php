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
                'name' => ' Dev Devalto',
            ],
            [
                'projet_id' => 1,
                'name' => 'Dev Web',
            ],
            [
                'projet_id' => 2,
                'name' => 'Dev Mobile',
            ],
        ]);
    }
}
