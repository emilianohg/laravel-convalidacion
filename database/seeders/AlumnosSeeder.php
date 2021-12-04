<?php

namespace Database\Seeders;

use App\Models\Alumno;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;


class AlumnosSeeder extends Seeder
{
    public function run()
    {

        Alumno::query()->delete();

        Alumno::factory(100)->create();

    }
}
