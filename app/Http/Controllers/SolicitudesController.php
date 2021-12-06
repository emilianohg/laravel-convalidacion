<?php

namespace App\Http\Controllers;

use App\Http\Requests\CrearSolicitudRequest;
use App\Models\Adeudo;
use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\Solicitud;
use App\Models\SolicitudHistorial;
use App\Models\User;
use App\Utils\ValidarUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SolicitudesController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = User::query()->find(auth()->id());

        $carrerasId = $request->get('carreras_id');
        $statusId   = $request->get('status_id');
        $search     = $request->get('search');

        if ($carrerasId != null) {
            $carrerasId = explode(',', $carrerasId);
        }

        if ($statusId != null) {
            $statusId = explode(',', $statusId);
        }

        if ($user->rol_id == 'coordinador' && $carrerasId != null) {

            $totalCarrerasDeCoordinador = Carrera::query()
                ->select([
                    'coordinadores.usuario_id',
                    'carreras.*',
                ])
                ->join('coordinadores', 'carreras.carrera_id', '=', 'coordinadores.carrera_id')
                ->where('coordinadores.usuario_id', '=', $user->id)
                ->whereIn('coordinadores.carrera_id', $carrerasId)
                ->count();

            if ($totalCarrerasDeCoordinador != count($carrerasId)) {
                return response()->json([
                    'message' => 'El coordinador no tiene permiso de acceder a las solicitudes de una carrera especificada'
                ], 401);
            }

        }

        if ($user->rol_id == 'coordinador' && $carrerasId == null) {
            $carrerasId = DB::table('coordinadores')
                ->where('coordinadores.usuario_id', '=', $user->id)
                ->get()
                ->map(fn ($coordinador) => $coordinador->carrera_id)
                ->toArray();
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
            ->join('usuarios', 'alumnos.usuario_id', '=', 'usuarios.id')
            ->join('planes_estudio', 'alumnos.plan_estudio_id', '=', 'planes_estudio.clave')
            ->join('carreras as carrera_convalidar', 'solicitudes.carrera_id', '=', 'carrera_convalidar.carrera_id')
            ->join('carreras as carrera_cursada', 'planes_estudio.carrera_id', '=', 'carrera_cursada.carrera_id')
            ->join('status', 'solicitudes.status_id', '=', 'status.status_id')
            ->join('solicitudes_historial', function ($join) {
                $join->on('solicitudes_historial.solicitud_id', '=', 'solicitudes.solicitud_id')
                    ->where('solicitudes_historial.status_id', '=', 'pendiente');
            });

        if ($carrerasId != null) {
            $queryBuilder->whereIn('carrera_cursada.carrera_id', $carrerasId);
        }

        if ($statusId != null) {
            $queryBuilder->whereIn('solicitudes.status_id', $statusId);
        }

        if ($search != null) {
            $queryBuilder->where(
                DB::raw('CONCAT(usuarios.nombre, " ", alumnos.numero_control)'),
                'LIKE',
                '%'.$search.'%'
            );
        }

        $solicitudes = $queryBuilder->paginate(50);

        return response()->json($solicitudes);
    }

    public function show($solicitudId): JsonResponse
    {
        $user = User::query()->find(auth()->id());

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
            ->join('usuarios', 'alumnos.usuario_id', '=', 'usuarios.id')
            ->join('planes_estudio', 'alumnos.plan_estudio_id', '=', 'planes_estudio.clave')
            ->join('carreras as carrera_convalidar', 'solicitudes.carrera_id', '=', 'carrera_convalidar.carrera_id')
            ->join('carreras as carrera_cursada', 'planes_estudio.carrera_id', '=', 'carrera_cursada.carrera_id')
            ->join('status', 'solicitudes.status_id', '=', 'status.status_id')
            ->join('solicitudes_historial', function ($join) {
                $join->on('solicitudes_historial.solicitud_id', '=', 'solicitudes.solicitud_id')
                    ->where('solicitudes_historial.status_id', '=', 'pendiente');
            })
            ->first();

        if ($user->rol_id == 'coordinador') {
            $esValido = ValidarUtils::validarCoordinacion($user, $solicitud->carrera_cursada_id);

            if (!$esValido) {
                return response()->json([
                    'message' => 'No tienes acceso a esta solicitud',
                ], 401);
            }
        }

        $historial = SolicitudHistorial::query()
            ->select([
                'solicitudes_historial_id',
                'solicitud_id',
                'solicitudes_historial.usuario_id',
                'usuarios.nombre as usuario',
                'roles.nombre as rol',
                'status.nombre as status',
                'fecha',
                'comentario',
            ])
            ->join('usuarios', 'solicitudes_historial.usuario_id', '=', 'usuarios.id')
            ->join('roles', 'usuarios.rol_id', '=', 'roles.rol_id')
            ->join('status', 'solicitudes_historial.status_id', '=', 'status.status_id')
            ->where('solicitud_id', '=', $solicitudId)
            ->get();

        $solicitud->historial = $historial;

        return response()->json($solicitud);
    }

    public function store(CrearSolicitudRequest $request): JsonResponse
    {
        $userId = auth()->id();
        $alumno = Alumno::query()->where('usuario_id', '=', $userId)->first();
        $statusId = 'pendiente';

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
            ], 422);
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

    public function cancel(Request $request): JsonResponse
    {
        $user = User::query()->find(auth()->id());
        $solicitudId = $request->get('solicitud_id');
        $solicitud = Solicitud::query()->findOrFail($solicitudId);

        if ($solicitud->status_id == 'cancelada') {
            return response()->json(['message' => 'La solicitud ya es encuentra cancelada'], 422);
        }

        $coincidenciasRol = collect(['coordinador', 'jefe', 'alumno'])->filter(function ($rol) use ($user) {
            return $user->rol_id == $rol;
        })->count();

        if ($coincidenciasRol == 0) {
            return response()->json(['message' => 'No autorizado'], 422);
        }

        if ($user->rol_id == 'alumno') {
            $alumno = Alumno::query()->where('usuario_id', '=', $user->id)->first();

            if ($solicitud->numero_control != $alumno->numero_control) {
                return response()->json(['message' => 'No autorizado'], 422);
            }
        }

        $statusId = 'cancelada';

        $solicitud->status_id = $statusId;
        $solicitud->save();

        SolicitudHistorial::query()->create([
            'solicitud_id' => $solicitudId,
            'usuario_id' => $user->id,
            'status_id' => $statusId,
            'fecha' => now(),
            'comentario' => null,
        ]);

        return response()->json([
            'message'   => 'Solicitud cancelada',
            'solicitud' => $solicitud,
        ]);

    }

}
