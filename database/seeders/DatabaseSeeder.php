<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RolSeeder::class,
            StatusSeeder::class,
            InstitutoConfigurationSeeder::class,
            UsuariosSeeder::class,
            CoordinadoresSeeder::class,
            AlumnosSeeder::class,
            AdeudosSeeder::class,
            SolicitudesSeeder::class,
        ]);
    }
}
