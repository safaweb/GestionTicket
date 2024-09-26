<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. create a super admin
        $superAdmin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
        ]);
        $superAdmin->syncRoles('Super Admin');

        // 2. create a Admin Projet
        $adminProjet = User::factory()->create([
            'name' => 'Chef Projet',
            'email' => 'chefprojet@example.com',
        ]);
        $adminProjet ->syncRoles('Chef Projet');

        // 3. create a staff du Projet
        $staffProjet = User::factory()->create([
            'name' => 'Collaborateur',
            'email' => 'staffprojet@example.com',
        ]);
        $staffProjet ->syncRoles('Collaborateur');

        // 4. create a user
        $userProjet  = User::factory()->create([
            'name' => 'Client',
            'email' => 'user@example.com',
            'pays_id' => 1,
        ]);
        $userProjet ->syncRoles('Client');
    }
}
