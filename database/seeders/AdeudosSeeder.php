<?php

namespace Database\Seeders;

use App\Models\Adeudo;
use App\Models\Alumno;
use Illuminate\Database\Seeder;

class AdeudosSeeder extends Seeder
{
    public function run()
    {

        $tiposDeAdedudo = [
            ['concepto' => 'Falta pago del Semestre', 'importe' => 3000],
            ['concepto' => 'Devolución de libro en biblioteca', 'importe' => 500],
            ['concepto' => 'Falta pago de kardex', 'importe' => 100],
            ['concepto' => 'Falta pago de examen de ingles', 'importe' => 500],
        ];

        Adeudo::query()->delete();

        $alumnos = collect(Alumno::query()->inRandomOrder()->get());

        // Adeudos agregados al 10% de los alumnos
        $porcentajeDeAlumnosConAdeudos = 0.1;
        $totalAlumnosConAdeudos = round($porcentajeDeAlumnosConAdeudos * $alumnos->count());

        $alumnosConAdeudos = $alumnos->take($totalAlumnosConAdeudos);

        foreach ($alumnosConAdeudos as $alumno) {
            $numeroDeAdeudos = collect([1, 1, 1, 1, 2, 2, 3])->random();
            $adeudos = collect($tiposDeAdedudo)->random($numeroDeAdeudos);

            for ($i = 0; $i < $numeroDeAdeudos; $i++) {
                Adeudo::query()->insert([
                    'numero_control' => $alumno->numero_control,
                    'descripcion' => $adeudos[$i]['concepto'],
                    'importe' => $adeudos[$i]['importe'],
                ]);
            }

        }

    }
}
