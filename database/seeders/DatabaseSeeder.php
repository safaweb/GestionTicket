<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            ProjetSeeder::class,
            UserSeeder::class,
            PrioritySeeder::class,
            StatutDuTicketSeeder::class,
            ProblemCategoryMigration::class,
            TicketSeeder::class,
            PaysSeeder::class,
            QualificationSeeder::class,
            SocieteSeeder::class,
            ValidationSeeder::class,
        ]);
    }
}
