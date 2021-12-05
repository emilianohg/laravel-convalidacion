<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\SolicitudHistorial;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SolicitudesController extends Controller
{
    public function index(Request $request): JsonResponse
    {

        $carreraId  = $request->get('carrera_id');
        $statusId   = $request->get('status_id');

        $queryBuilder = Solicitud::query()
            ->select([
                'solicitudes.solicitud_id',
                'solicitudes.numero_control',
                'solicitudes.status_id',
                'status.nombre as status',
                'carrera_cursada.carrera_id as carrera_cursada_id',
                'carrera_cursada.nombre as carrera_cursada',
                'carrera_convalidar.carrera_id as carrera_convalidar_id',
                'carrera_convalidar.nombre as carrera_convalidar',
                'usuarios.nombre as alumno',
                'solicitudes_historial.fecha as fecha_registro',
            ])
            ->join('alumnos', 'solicitudes.numero_control', '=', 'alumnos.numero_control')
            ->join('usuarios', 'alumnos.usuario_id', '=', 'alumnos.usuario_id')
            ->join('planes_estudio', 'alumnos.plan_estudio_id', '=', 'planes_estudio.clave')
            ->join('carreras as carrera_convalidar', 'solicitudes.carrera_id', '=', 'carrera_convalidar.carrera_id')
            ->join('carreras as carrera_cursada', 'planes_estudio.carrera_id', '=', 'carrera_cursada.carrera_id')
            ->join('status', 'solicitudes.status_id', '=', 'status.status_id')
            ->join('solicitudes_historial', function ($join) {
                $join->on('solicitudes_historial.solicitud_id', '=', 'solicitudes.solicitud_id')
                    ->where('solicitudes_historial.status_id', '=', 'registrada');
            });

        if ($carreraId != null) {
            $queryBuilder->where('carrera_id', '=', $carreraId);
        }

        if ($statusId != null) {
            $queryBuilder->where('status_id', '=', $statusId);
        }

        $solicitudes = $queryBuilder->paginate();

        return response()->json($solicitudes);
    }

    public function show($solicitudId) {
        $solicitud = Solicitud::query()
            ->select([
                'solicitudes.solicitud_id',
                'solicitudes.numero_control',
                'solicitudes.status_id',
                'status.nombre as status',
                'carrera_cursada.carrera_id as carrera_cursada_id',
                'carrera_cursada.nombre as carrera_cursada',
                'carrera_convalidar.carrera_id as carrera_convalidar_id',
                'carrera_convalidar.nombre as carrera_convalidar',
                'usuarios.nombre as alumno',
                'solicitudes_historial.fecha as fecha_registro',
            ])
            ->where('solicitudes.solicitud_id', '=', $solicitudId)
            ->join('alumnos', 'solicitudes.numero_control', '=', 'alumnos.numero_control')
            ->join('usuarios', 'alumnos.usuario_id', '=', 'alumnos.usuario_id')
            ->join('planes_estudio', 'alumnos.plan_estudio_id', '=', 'planes_estudio.clave')
            ->join('carreras as carrera_convalidar', 'solicitudes.carrera_id', '=', 'carrera_convalidar.carrera_id')
            ->join('carreras as carrera_cursada', 'planes_estudio.carrera_id', '=', 'carrera_cursada.carrera_id')
            ->join('status', 'solicitudes.status_id', '=', 'status.status_id')
            ->join('solicitudes_historial', function ($join) {
                $join->on('solicitudes_historial.solicitud_id', '=', 'solicitudes.solicitud_id')
                    ->where('solicitudes_historial.status_id', '=', 'registrada');
            })
            ->first();

        $historial = SolicitudHistorial::query()->where('solicitud_id', '=', $solicitudId)->get();

        $solicitud->historial = $historial;

        return response()->json($solicitud);
    }

    public function store(Request $request) {
        
    }


}
