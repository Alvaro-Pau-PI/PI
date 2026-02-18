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

        // Crear alguns usuaris de prova
        User::factory(5)->create();

        // Executar ProductSeeder
        $this->call([
            ProductSeeder::class,
        ]);
    }
}
