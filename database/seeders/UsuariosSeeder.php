<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;

class UsuariosSeeder extends Seeder
{
    public function run()
    {
        User::query()->delete();
    }
}
