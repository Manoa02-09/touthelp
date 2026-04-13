<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ExpertiseSeeder extends Seeder
{
    public function run(): void
    {
        $expertises = [
            [
                'nom' => 'Formations inter-entreprises',
                'slug' => 'formations-inter-entreprises',
                'description' => 'Nos formations interentreprises sont conçues pour permettre à vos collaborateurs de développer leurs compétences dans un cadre structuré, tout en bénéficiant déchanges avec des professionnels issus de différents horizons.',
                'icone' => 'fa-people-group',
                'ordre' => 1,
                'actif' => true,
            ],
            [
                'nom' => 'Formations intra-entreprise',
                'slug' => 'formations-intra-entreprise',
                'description' => 'Chaque organisation a ses propres réalités, ses contraintes et ses objectifs. Nos formations intra-entreprise sont conçues sur mesure, pour répondre précisément à vos besoins et accompagner vos équipes dans leur montée en compétence.',
                'icone' => 'fa-building',
                'ordre' => 2,
                'actif' => true,
            ],
            [
                'nom' => 'Accompagnement & Audit',
                'slug' => 'accompagnement-audit',
                'description' => 'Structurer son organisation et en évaluer l efficacité vont de pair. Nous vous accompagnons à chaque étape, de la mise en place de vos systèmes jusqu à l évaluation de vos pratiques.',
                'icone' => 'fa-magnifying-glass-chart',
                'ordre' => 3,
                'actif' => true,
            ],
        ];

        foreach ($expertises as $expertise) {
            DB::table('expertises')->insert([
                'nom' => $expertise['nom'],
                'slug' => $expertise['slug'],
                'description' => $expertise['description'],
                'icone' => $expertise['icone'],
                'ordre' => $expertise['ordre'],
                'actif' => $expertise['actif'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}