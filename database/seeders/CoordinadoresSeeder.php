<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

use App\Models\User;
use App\Models\Carrera;

class CoordinadoresSeeder extends Seeder
{
    public function run()
    {

        $coordinadoresCSV = Reader::createFromPath(storage_path('seeders/coordinadores.csv'))
            ->setHeaderOffset(0);

        DB::table('coordinadores')->delete();
        User::query()->where('rol_id', '=', 'coordinador')->delete();

        $coordinadoresRegistrados = [];

        foreach ($coordinadoresCSV as $coordinador) {

            $coordinadorUser = collect($coordinadoresRegistrados)
                ->filter(fn($coord) => $coord->email == $coordinador['correo'])
                ->first();

            if (!$coordinadorUser) {
                $coordinadorUser = User::query()->create([
                    'nombre'    => $coordinador['nombre'],
                    'email'     => $coordinador['correo'],
                    'password'  => bcrypt('password'),
                    'rol_id'    => 'coordinador',
                ]);

                $coordinadoresRegistrados[] = $coordinadorUser;
            }

            $coordinadorUser->carreras()->attach($coordinador['carrera_id']);
        }

    }
}
