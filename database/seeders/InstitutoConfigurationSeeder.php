<?php

namespace Database\Seeders;

use App\Models\Asignatura;
use App\Models\PlanEstudio;
use Illuminate\Database\Seeder;

use League\Csv\Reader;

use App\Models\Carrera;
use App\Models\Instituto;

class InstitutoConfigurationSeeder extends Seeder
{
    public function run()
    {

        Instituto::query()->delete();
        Carrera::query()->delete();
        PlanEstudio::query()->delete();

        $institutoId = 1;

        Instituto::query()->create([
            'instituto_id'  => $institutoId,
            'nombre'        => 'Instituto Tecnológico de Culiacán',
            'direccion'     => 'Calle Juan de Dios Batiz No. 310 pte, Guadalupe, 80220 Culiacán Rosales, Sin.'
        ]);

        $carrerasCSV = Reader::createFromPath(storage_path('seeders/carreras.csv'))
            ->setHeaderOffset(0);

        $asignaturasCSV = Reader::createFromPath(storage_path('seeders/asignaturas.csv'))
            ->setDelimiter("\t")
            ->setHeaderOffset(0);

        $carreras       = [];
        $planesEstudio  = [];

        foreach ($carrerasCSV as $carrera) {
            $carreraId = $carrera['carrera_id'];

            $carreras[] = [
                'carrera_id'    => $carreraId,
                'instituto_id'  => $institutoId,
                'nombre'        => $carrera['nombre'],
            ];

            $totalCreditos = collect($asignaturasCSV)->where('carrera_id', $carreraId)->sum('creditos');

            $planesEstudio[] = [
                'clave'             => 'PLAN' . $carrera['carrera_id'],
                'carrera_id'        => $carrera['carrera_id'],
                'total_creditos'    => $totalCreditos,
                'es_vigente'        => true,
            ];

        }

        Carrera::query()->insert($carreras);
        PlanEstudio::query()->insert($planesEstudio);

        $asignaturas    = [];

        foreach ($asignaturasCSV as $asignatura) {
            $asignaturas[] = [
                'plan_estudio_clave'    => 'PLAN' . $asignatura['carrera_id'],
                'nombre'                => ucfirst($asignatura['nombre']),
                'creditos'              => $asignatura['creditos'],
            ];
        }

        Asignatura::query()->insert($asignaturas);

    }
}
