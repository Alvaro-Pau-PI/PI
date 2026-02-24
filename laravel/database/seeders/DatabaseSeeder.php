<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear usuari admin de prova
        User::updateOrCreate(
            ['email' => 'admin@admin.test'],
            [
                'name' => 'Admin',
                'role' => 'admin',
                'password' => bcrypt('password'),
            ]
        );

        // Crear alguns usuaris de prova (Estàtics per evitar dependència de Faker en producció)
        $testUsers = [
            ['name' => 'Pau Albero', 'email' => 'pau@example.test'],
            ['name' => 'Alvaro Perez', 'email' => 'alvaro@example.test'],
            ['name' => 'User Test', 'email' => 'user@example.test'],
        ];

        foreach ($testUsers as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => bcrypt('password'),
                ]
            );
        }

        // Executar ProductSeeder
        $this->call([
            ProductSeeder::class,
        ]);
    }
}
