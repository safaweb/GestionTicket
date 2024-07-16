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
                'name' => ' Divalto Commerce & Logistique',
            ],
            [
                'projet_id' => 1,
                'name' => ' Divalto Production',
            ],
            [
                'projet_id' => 1,
                'name' => ' Divalto C.R.M',
            ],
            [
                'projet_id' => 1,
                'name' => ' Divalto Affaire',
            ],
            [
                'projet_id' => 1,
                'name' => ' Divalto Comptabilité',
            ],
            [
                'projet_id' => 1,
                'name' => ' Divalto Règlement',
            ],
            [
                'projet_id' => 1,
                'name' => ' Divalto Paie',
            ],
            [
                'projet_id' => 1,
                'name' => ' Divalto Qualité',
            ],
            [
                'projet_id' => 1,
                'name' => ' Divalto Documentation',
            ],
            [
                'projet_id' => 1,
                'name' => ' Divalto Contrôle',
            ],
            [
                'projet_id' => 1,
                'name' => ' Divalto Ressources Matérielles',
            ],
            [
                'projet_id' => 1,
                'name' => ' Divalto Processus',
            ],
            [
                'projet_id' => 1,
                'name' => ' Divalto Point de vente',
            ],
            [
                'projet_id' => 1,
                'name' => ' Divalto Travaux & Maintenance',
            ],
            [
                'projet_id' => 1,
                'name' => ' Système',
            ],
            [
                'projet_id' => 1,
                'name' => ' Développement Web',
            ],
            [
                'projet_id' => 1,
                'name' => ' Développement Mobile',
            ],
            [
                'projet_id' => 1,
                'name' => ' Autre',
            ],
        ]);
    }
}
