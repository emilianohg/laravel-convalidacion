<?php

namespace Database\Factories;

use App\Models\Alumno;
use App\Models\PlanEstudio;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlumnoFactory extends Factory
{
    public function definition()
    {

        $numeroControl = $this->faker->numerify('1#######');

        $alumnoExistente = Alumno::query()->where('numero_control', '=', $numeroControl)->first();
        while($alumnoExistente != null) {
            $numeroControl = $this->faker->numerify('1#######');
        }

        $user = User::factory()->state([
            'rol_id'    => 'alumno',
            'email'     => $numeroControl . '@itculiacan.edu.mx'
        ])->create();

        return [
            'numero_control'    => $numeroControl,
            'usuario_id'        => $user->id,
            'plan_estudio_id'   => PlanEstudio::all()->random()->clave,
            'semestre'          => $this->faker->numberBetween(1, 10),
        ];
    }

}
