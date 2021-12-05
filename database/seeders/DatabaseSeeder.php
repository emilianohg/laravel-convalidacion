<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RolSeeder::class,
            // UsuariosSeeder::class,
            InstitutoConfigurationSeeder::class,
            CoordinadoresSeeder::class,
            StatusSeeder::class,
            AlumnosSeeder::class,
            AdeudosSeeder::class,
        ]);
    }
}
