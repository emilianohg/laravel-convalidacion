<?php

namespace Database\Seeders;

use App\Models\Carrera;
use Illuminate\Database\Seeder;

use App\Models\User;

class UsuariosSeeder extends Seeder
{
    public function run()
    {
        User::query()
            ->whereIn('rol_id', ['jefe', 'academia', 'psicologo', 'servicios'])
            ->delete();

        User::query()->create([
            'nombre'    => 'Jefe de Division de Estudios',
            'email'     => 'jefe@itculiacan.edu.mx',
            'password'  => bcrypt('password'),
            'rol_id'    => 'jefe',
        ]);

        $carreras = Carrera::query()->get();

        foreach ($carreras as $carrera) {
            User::query()->create([
                'nombre'    => 'Academia de ' . $carrera->nombre,
                'email'     => 'academia'.strtolower($carrera->carrera_id).'@itculiacan.edu.mx',
                'password'  => bcrypt('password'),
                'rol_id'    => 'academia',
            ]);
        }

        User::query()->create([
            'nombre'    => 'El Psicologo',
            'email'     => 'psicologo@itculiacan.edu.mx',
            'password'  => bcrypt('password'),
            'rol_id'    => 'psicologo',
        ]);

        User::query()->create([
            'nombre'    => 'Servicios Escolares',
            'email'     => 'servicios@itculiacan.edu.mx',
            'password'  => bcrypt('password'),
            'rol_id'    => 'servicios',
        ]);
    }
}
