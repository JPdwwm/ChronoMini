<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    // Création de l'admin
    User::create([
        'first_name' => 'administrateur',
        'last_name' => 'César',
        'password' => Hash::make('Azerty88@'),
        'email' => 'admin@jp.fr',
        'email_verified_at' => now(),
        'remember_token' => Str::random(10),
        'role_id' => 1
    ]);
    
    // Création d'un user parent de test
    User::create([
        'first_name' => 'Utilisateur',
        'last_name' => 'Parent',
        'password' => Hash::make('Azerty88@'),
        'email' => 'parent@jp.fr',
        'city' => 'Niort',
        'address' => '76 avenue du Maréchal de Lattre de Tassigny',
        'zip_code' => '79000',
        'email_verified_at' => now(),
        'remember_token' => Str::random(10),
        'role_id' => 2
    ]);

    // Création d'un user asmat parent de test
    User::create([
        'first_name' => 'Utilisateur',
        'last_name' => 'Asmat',
        'password' => Hash::make('Azerty88@'),
        'email' => 'asmat@jp.fr',
        'email_verified_at' => now(),
        'remember_token' => Str::random(10),
        'role_id' => 3
    ]);
    }
}
