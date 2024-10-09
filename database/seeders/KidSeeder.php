<?php

namespace Database\Seeders;

use App\Models\Kid;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crée 15 enfants aléatoires
        $kids = Kid::factory()->count(15)->create();

        // Récupère les utilisateurs avec les IDs 2 et 3
        $users = User::whereIn('id', [2, 3])->get();

        // Associe chaque enfant aux utilisateurs 2 et 3 dans la table pivot
        foreach ($kids as $kid) {
            $kid->users()->attach($users->pluck('id')->toArray());
        }
    }
}
