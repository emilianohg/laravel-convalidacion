<?php

namespace Database\Seeders;

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

        $institutoId = 1;

        Instituto::query()->create([
            'instituto_id'  => $institutoId,
            'nombre'        => 'Instituto Tecnológico de Culiacán',
            'direccion'     => 'Calle Juan de Dios Batiz No. 310 pte, Guadalupe, 80220 Culiacán Rosales, Sin.'
        ]);

        $carrerasCSV = Reader::createFromPath(storage_path('seeders/carreras.csv'))
            ->setHeaderOffset(0);

        $carreras = [];

        foreach ($carrerasCSV as $carrera) {
            $carreras[] = [
                'carrera_id'    => $carrera['carrera_id'],
                'instituto_id'  => $institutoId,
                'nombre'        => $carrera['nombre'],
            ];
        }

        Carrera::query()->insert($carreras);

    }
}
