<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Models\Alumno;

class AlumnosController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $carreraId      = $request->get('carrera_id');
        $numeroControl  = $request->get('numero_control');
        $nombre         = $request->get('nombre');

        $queryBuilder = Alumno::query()
            ->select([
                'alumnos.usuario_id',
                'alumnos.numero_control',
                'usuarios.nombre',
                'usuarios.email',
                'carreras.carrera_id',
                'carreras.nombre as carrera',
                'alumnos.plan_estudio_id as clave_plan_estudio',
                'alumnos.semestre',
            ])
            ->join('usuarios', 'alumnos.usuario_id', '=', 'usuarios.id')
            ->join('planes_estudio', 'alumnos.plan_estudio_id', '=', 'planes_estudio.clave')
            ->join('carreras', 'planes_estudio.carrera_id', '=', 'carreras.carrera_id');

        if ($carreraId != null) {
            $queryBuilder->where('carreras.carrera_id', '=', $carreraId);
        }

        if ($numeroControl != null) {
            $queryBuilder->where('alumnos.numero_control', 'LIKE', '%'.$numeroControl.'%');
        }

        if ($nombre != null) {
            $queryBuilder->where('usuarios.nombre', 'LIKE', '%'.$nombre.'%');
        }

        $alumnos = $queryBuilder->paginate();

        return response()->json($alumnos);
    }
}
