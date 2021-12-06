<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Status;

class StatusSeeder extends Seeder
{
    public function run()
    {

        Status::query()->delete();

        Status::create([
            'status_id' => 'cancelada',
            'nombre'    => 'Cancelada'
        ]);

        Status::create([
            'status_id' => 'pendiente',
            'nombre'    => 'Pendiente'
        ]);

        Status::create([
            'status_id' => 'revisando',
            'nombre'    => 'En revisión'
        ]);

        Status::create([
            'status_id' => 'dictaminada',
            'nombre'    => 'Dictaminada'
        ]);

        Status::create([
            'status_id' => 'aprobada',
            'nombre'    => 'Aprobada'
        ]);

    }
}
