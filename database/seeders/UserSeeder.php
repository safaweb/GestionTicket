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
            'name' => 'Admin Projet',
            'email' => 'adminprojet@example.com',
            'projet_id' => 1,
<<<<<<< HEAD
          'pays_id' => 1,
=======
            'pays_id' => 1,
>>>>>>> 865ad8f3f674164c2c87147e641963bdc26c69a8
        ]);
        $adminProjet ->syncRoles('Admin Projet');

        // 3. create a staff du Projet
        $staffProjet = User::factory()->create([
            'name' => 'Staff Projet',
            'email' => 'staffprojet@example.com',
            'projet_id' => 1,
            'pays_id' => 1,
        ]);
        $staffProjet ->syncRoles('Staff Projet');

        // 4. create a user
        $userProjet  = User::factory()->create([
            'name' => 'Client',
            'email' => 'user@example.com',
        ]);
        $userProjet ->syncRoles('Client');
    }
}
