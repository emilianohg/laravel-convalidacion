<?php

namespace Database\Seeders;

use App\Models\Alumno;
use App\Models\Asignatura;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlumnosSeeder extends Seeder
{
    public function run()
    {

        $asignaturas = Asignatura::query()->get();

        Alumno::query()->delete();
        Alumno::factory(100)->create()->each(function ($alumno) use ($asignaturas) {
            $numeroMaterias = $alumno->semestre * 4;

            $asignaturasCursadas = $asignaturas->where('plan_estudio_clave', '=', $alumno->plan_estudio_id)
                ->random($numeroMaterias)
                ->map(function ($asignatura) use ($alumno) {
                    return [
                        'numero_control'    => $alumno->numero_control,
                        'asignatura_id'     => $asignatura->asignatura_id,
                    ];
                })
                ->toArray();

            DB::table('asignaturas_cursadas')->insert($asignaturasCursadas);
        });

    }
}
