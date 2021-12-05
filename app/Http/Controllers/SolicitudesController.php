<?php

namespace App\Http\Controllers;

use App\Http\Requests\CrearSolicitudRequest;
use App\Models\Adeudo;
use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\Solicitud;
use App\Models\SolicitudHistorial;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SolicitudesController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = User::query()->find(auth()->id());

        $carreraId  = $request->get('carrera_id');
        $statusId   = $request->get('status_id');

        if ($user->rol_id == 'coordinador') {

            if ($carreraId == null) {
                return response()->json([
                    'message' => 'Un coordinador necesita especificar la carrera que busca'
                ], 422);
            }

            $carreaDeCoordinador = Carrera::query()
                ->select([
                    'coordinadores.usuario_id',
                    'carreras.*',
                ])
                ->select()
                ->join('coordinadores', 'carreras.carrera_id', '=', 'coordinadores.carrera_id')
                ->where('coordinadores.usuario_id', '=', $user->id)
                ->where('coordinadores.carrera_id', '=', $carreraId)
                ->first();

            if ($carreaDeCoordinador == null) {
                return response()->json([
                    'message' => 'El coordinador no tiene permiso de acceder a las solicitudes de la carrera especificada'
                ], 422);
            }
        }

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
            $queryBuilder->where('carrera_cursada.carrera_id', '=', $carreraId);
        }

        if ($statusId != null) {
            $queryBuilder->where('solicitudes.status_id', '=', $statusId);
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

    public function store(CrearSolicitudRequest $request): JsonResponse
    {
        $userId = auth()->id();
        $alumno = Alumno::query()->where('usuario_id', '=', $userId)->first();
        $statusId = 'registrada';

        $carreraId = $request->get('carrera_id');

        $solicitudExistente = Solicitud::query()
            ->where('numero_control', '=', $alumno->numero_control)
            ->first();

        $adeudos = Adeudo::query()
            ->where('numero_control', '=', $alumno->numero_control)
            ->get();

        if ($adeudos != null and count($adeudos) > 0) {
            return response()->json([
                'message' => 'No puedes solicitar una convalidación de estudios si cuentas con adeudos',
                'cause' => [
                    'adeudos' => $adeudos,
                ],
            ]);
        }

        if ($solicitudExistente != null) {
            return response()->json([
                'message'   => 'Ya tienes una solicitud de convalidación creada',
                'cause'     => [
                    'solicitud' => $solicitudExistente,
                ],
            ],422);
        }

        $solicitud = Solicitud::query()->create([
            'carrera_id'        => $carreraId,
            'numero_control'    => $alumno->numero_control,
            'status_id'         => $statusId,
        ]);

        SolicitudHistorial::query()->create([
            'solicitud_id' => $solicitud->solicitud_id,
            'usuario_id'    => $alumno->usuario_id,
            'status_id'     => $statusId,
            'fecha'         => now(),
            'comentario'    => null,
        ]);

        return response()->json($solicitud);
    }


}
