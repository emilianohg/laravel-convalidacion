<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsuariosSeeder extends Seeder
{
    public function run()
    {
        User::query()->delete();

        User::factory(10)->create();
    }
}
