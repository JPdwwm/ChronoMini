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
        // Crée 10 enfants aléatoires
        $kids = Kid::factory()->count(10)->create();

        // Récupère les utilisateurs avec les IDs 2 et 3
        $users = User::whereIn('id', [2, 3])->get();

        // Associe chaque enfant aux utilisateurs 2 et 3 dans la table pivot
        foreach ($users as $user) {
            $user->kids()->attach($kids->pluck('id')->toArray());
        }
    }
}
