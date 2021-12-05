<?php

namespace Database\Seeders;

use App\Models\Alumno;
use App\Models\Asignatura;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlumnosSeeder extends Seeder
{
    /**
     * @var array|Collection|Builder[]
     */
    private array|Collection $asignaturas;

    public function __construct()
    {
        $this->asignaturas = Asignatura::query()->get();
    }


    public function run()
    {
        Alumno::query()->delete();

        $user = User::query()->create([
            'nombre'    => 'Emiliano Hernández Guerrero',
            'email'     => '18170410@itculiacan.edu.mx',
            'password'  => bcrypt('password'),
            'rol_id'    => 'alumno',
        ]);

        $alumno = Alumno::query()->create([
            'usuario_id'        => $user->id,
            'numero_control'    => '18170410',
            'plan_estudio_id'   => 'PLANISIC',
            'semestre'          => 3,
        ]);

        $this->registrar_asignaturas($alumno);

        Alumno::factory(100)->create()->each(function ($alumno) {
            $this->registrar_asignaturas($alumno);
        });

    }

    private function registrar_asignaturas(Alumno $alumno) {
        $numeroMaterias = $alumno->semestre * 4;

        $asignaturasCursadas = $this->asignaturas->where('plan_estudio_clave', '=', $alumno->plan_estudio_id)
            ->random($numeroMaterias)
            ->map(function ($asignatura) use ($alumno) {
                return [
                    'numero_control'    => $alumno->numero_control,
                    'asignatura_id'     => $asignatura->asignatura_id,
                    'calificacion'      => rand(70, 100),
                ];
            })
            ->toArray();

        DB::table('asignaturas_cursadas')->insert($asignaturasCursadas);
    }
}
