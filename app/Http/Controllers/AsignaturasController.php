<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Models\Asignatura;

class AsignaturasController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $clavePlanEstudio = $request->get('clave_plan_estudio');
        $esVigente = $request->get('es_vigente');

        $queryBuilder = Asignatura::query()
            ->select([
                'carreras.carrera_id',
                'asignaturas.asignatura_id',
                'asignaturas.plan_estudio_clave',
                'carreras.nombre as carrera',
                'asignaturas.nombre',
                'asignaturas.creditos',
                'planes_estudio.es_vigente'
            ])
            ->join('planes_estudio', 'asignaturas.plan_estudio_clave', '=', 'planes_estudio.clave')
            ->join('carreras', 'planes_estudio.carrera_id', '=', 'carreras.carrera_id');

        if ($clavePlanEstudio != null) {
            $queryBuilder->where('asignaturas.plan_estudio_clave', '=', $clavePlanEstudio);
        }

        if ($esVigente != null) {
            $queryBuilder->where('planes_estudio.es_vigente', '=', $esVigente);
        }

        $asignaturas = $queryBuilder->get();

        return response()->json($asignaturas);
    }
}
