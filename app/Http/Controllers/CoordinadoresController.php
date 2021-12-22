<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CoordinadoresController extends Controller
{
    public function index(): JsonResponse
    {
        $carreras = Carrera::query()
            ->select([
                'coordinadores.usuario_id',
                'carreras.*',
            ])
            ->join('coordinadores', 'carreras.carrera_id', '=', 'coordinadores.carrera_id')
            ->get();

        $coordinadores = User::query()
            ->select([
                'usuarios.id',
                'usuarios.nombre',
                'usuarios.email',
                'usuarios.rol_id',
                DB::raw('COUNT(solicitudes.solicitud_id) as solicitudes_activas')
            ])
            ->join('coordinadores', 'usuarios.id', '=', 'coordinadores.usuario_id')
            ->join('planes_estudio', function($join) {
                $join->on('coordinadores.carrera_id', '=', 'planes_estudio.carrera_id')
                    ->where('planes_estudio.es_vigente', '=', 1);
            })
            ->join('alumnos', 'planes_estudio.clave', '=', 'alumnos.plan_estudio_id')
            ->join('solicitudes', function($join) {
                $join->on('alumnos.numero_control', '=', 'solicitudes.numero_control')
                    ->whereNotIn('solicitudes.status_id', ['cancelada', 'rechazada', 'aprobada']);
            })
            ->where('rol_id', '=', 'coordinador')
            ->groupBy([
                'usuarios.id',
                'usuarios.nombre',
                'usuarios.email',
                'usuarios.rol_id',
            ])
            ->get();

        foreach ($coordinadores as $coordinador) {
            $coordinador->carreras = collect($carreras)->where('usuario_id', '=', $coordinador->id)->values()->toArray();
        }

        return response()->json($coordinadores);
    }
}
