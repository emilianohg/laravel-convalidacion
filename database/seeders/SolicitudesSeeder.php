<?php

namespace Database\Seeders;

use App\Models\Adeudo;
use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\SolicitudHistorial;
use Illuminate\Database\Seeder;

use App\Models\Solicitud;

class SolicitudesSeeder extends Seeder
{
    public function run()
    {

        Solicitud::query()->delete();

        $alumnosConAdeudo = Adeudo::query()->get()->map(fn ($adeudo) => $adeudo->numero_control);

        $alumnos = Alumno::query()
            ->select([
                'alumnos.usuario_id',
                'alumnos.numero_control',
                'planes_estudio.carrera_id',
            ])
            ->join('planes_estudio', 'alumnos.plan_estudio_id', '=', 'planes_estudio.clave')
            ->whereNotIn('numero_control', $alumnosConAdeudo)
            ->inRandomOrder()
            ->limit(20)
            ->get();

        $carreras = Carrera::query()->get();

        foreach ($alumnos as $alumno) {
            $statusId = 'registrada';
            $carreraConvalidar = $carreras->where('carrera_id', '<>', $alumno->carrera_id)->random();

            $solicitud = Solicitud::query()->create([
                'carrera_id'        => $carreraConvalidar->carrera_id,
                'numero_control'    => $alumno->numero_control,
                'status_id'         => $statusId,
            ]);

            SolicitudHistorial::query()->create([
                'solicitud_id' => $solicitud->solicitud_id,
                'usuario_id' => $alumno->usuario_id,
                'status_id' => $statusId,
                'fecha' => now(),
                'comentario' => null,
            ]);
        }



    }
}
