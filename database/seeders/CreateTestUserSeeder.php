<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class CreateTestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuário de teste
        User::firstOrCreate(
            ['email' => 'admin@financas.com'],
            [
                'name' => 'Admin Financas',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(),
            ]
        );

        // Criar usuário adicional
        User::firstOrCreate(
            ['email' => 'user@test.com'],
            [
                'name' => 'Usuário Teste',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
    }
}
