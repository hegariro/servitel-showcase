<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'     => 'Admin Servitel',
            'email'    => 'admin@servitel.dev',
            'password' => Hash::make('Qwerty098..'), // Usa una clave que recuerdes
        ]);
    }
}
