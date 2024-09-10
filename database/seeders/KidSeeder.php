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
     // Création d'un enfant'
    Kid::create([
        'first_name' => 'César',
        'birth_date' => Carbon::create('2021', '05', '16'),
    ]);   
    }
}
