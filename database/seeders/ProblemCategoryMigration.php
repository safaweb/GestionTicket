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
                'name' => ' Divalto Commerce & Logistique',
            ],
            [
                'name' => ' Divalto Production',
            ],
            [
                'name' => ' Divalto C.R.M',
            ],
            [
                'name' => ' Divalto Affaire',
            ],
            [
                'name' => ' Divalto Comptabilité',
            ],
            [
                'name' => ' Divalto Règlement',
            ],
            [
                'name' => ' Divalto Paie',
            ],
            [
                'name' => ' Divalto Qualité',
            ],
            [
                'name' => ' Divalto Documentation',
            ],
            [
                'name' => ' Divalto Contrôle',
            ],
            [
                'name' => ' Divalto Ressources Matérielles',
            ],
            [
                'name' => ' Divalto Processus',
            ],
            [
                'name' => ' Divalto Point de vente',
            ],
            [
                'name' => ' Divalto Travaux & Maintenance',
            ],
            [
                'name' => ' Système',
            ],
            [
                'name' => ' Développement Web',
            ],
            [
                'name' => ' Développement Mobile',
            ],
            [
                'name' => ' Autre',
            ],
        ]);
    }
}
