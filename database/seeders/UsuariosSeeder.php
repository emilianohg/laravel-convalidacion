<?php

namespace Database\Seeders;

use App\Models\Carrera;
use Illuminate\Database\Seeder;

use App\Models\User;
use Illuminate\Support\Facades\DB;

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
            $userAcademia = User::query()->create([
                'nombre'    => 'Academia de ' . $carrera->nombre,
                'email'     => 'academia'.strtolower($carrera->carrera_id).'@itculiacan.edu.mx',
                'password'  => bcrypt('password'),
                'rol_id'    => 'academia',
            ]);

            DB::table('academias')->insert([
                'usuario_id' => $userAcademia->id,
                'carrera_id' => $carrera->carrera_id,
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
