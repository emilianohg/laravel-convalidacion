<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Rol;

class RolSeeder extends Seeder
{
    public function run()
    {

        Rol::query()->delete();

        Rol::create([
            'rol_id' => 'jefe',
            'nombre' => 'Jefe de División de Estudios'
        ]);

        Rol::create([
            'rol_id' => 'coordinador',
            'nombre' => 'Coordinador'
        ]);

        Rol::create([
            'rol_id' => 'alumno',
            'nombre' => 'Alumno'
        ]);

        Rol::create([
            'rol_id' => 'servicios',
            'nombre' => 'Servícios Escolares'
        ]);

        Rol::create([
            'rol_id' => 'academia',
            'nombre' => 'Academia'
        ]);

        Rol::create([
            'rol_id' => 'psicologo',
            'nombre' => 'Psicólogo'
        ]);
    }
}
