<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolSeeder::class,
            InstitutoConfigurationSeeder::class,
            CoordinadoresSeeder::class,
            StatusSeeder::class,
        ]);
    }
}
