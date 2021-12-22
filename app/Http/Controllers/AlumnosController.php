<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Utils\ValidarUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Models\Alumno;
use App\Models\Adeudo;
use Illuminate\Support\Facades\DB;

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

        $alumnos = $queryBuilder->paginate(50);

        return response()->json($alumnos);
    }

    public function me(): JsonResponse
    {
        $user = User::query()->find(auth()->id());
        if ($user->rol_id != 'alumno') {
            return response()->json([
                'message'   => 'No eres un alumno',
            ],422);
        }

        $alumnoPorUsuarioId = Alumno::where('usuario_id', '=', auth()->id())->first();

        return $this->show($alumnoPorUsuarioId->numero_control);

    }

    public function show($numeroControl): JsonResponse
    {
        $user = User::query()->find(auth()->id());
        if ($user->rol_id == 'alumno') {
            $alumnoPorUsuarioId = Alumno::where('usuario_id', '=', auth()->id())->first();

            if ($alumnoPorUsuarioId->numero_control != $numeroControl) {
                return response()->json([
                    'message'   => 'No puedes acceder a los datos de otro alumno',
                ],401);
            }
        }

        $columns = [
            'alumnos.usuario_id',
            'alumnos.numero_control',
            'usuarios.nombre',
            'usuarios.email',
            'carreras.carrera_id',
            'carreras.nombre as carrera',
            'alumnos.plan_estudio_id as clave_plan_estudio',
            'alumnos.semestre',
            'solicitudes.solicitud_id',
            DB::raw('IF(solicitudes.solicitud_id IS NULL, 0, 1) as tiene_solicitud'),
            DB::raw('SUM(asignaturas.creditos) as total_creditos'),
            DB::raw('planes_estudio.total_creditos - SUM(asignaturas.creditos) as creditos_restantes'),
            DB::raw('FORMAT(AVG(asignaturas_cursadas.calificacion), 2) as promedio_general'),
        ];

        $alumno = Alumno::query()
            ->select($columns)
            ->join('usuarios', 'alumnos.usuario_id', '=', 'usuarios.id')
            ->join('planes_estudio', 'alumnos.plan_estudio_id', '=', 'planes_estudio.clave')
            ->join('carreras', 'planes_estudio.carrera_id', '=', 'carreras.carrera_id')
            ->join('asignaturas_cursadas', 'alumnos.numero_control', '=', 'asignaturas_cursadas.numero_control')
            ->join('asignaturas', 'asignaturas_cursadas.asignatura_id', '=', 'asignaturas.asignatura_id')
            ->leftJoin('solicitudes', 'alumnos.numero_control', '=', 'solicitudes.numero_control')
            ->where('alumnos.numero_control', '=', $numeroControl)
            ->groupBy([
                'alumnos.usuario_id',
                'alumnos.numero_control',
                'usuarios.nombre',
                'usuarios.email',
                'carreras.carrera_id',
                'carreras.nombre',
                'alumnos.plan_estudio_id',
                'alumnos.semestre',
                'planes_estudio.total_creditos',
                'solicitudes.solicitud_id',
            ])
            ->withCasts([
                'total_creditos' => 'integer',
                'creditos_restantes' => 'integer',
                'promedio_general' => 'double',
                'tiene_solicitud' => 'boolean',
            ])
            ->first();



        if ($user->rol_id == 'coordinador') {
            $esValido = ValidarUtils::validarCoordinacion($user, $alumno->carrera_id);

            if (!$esValido) {
                return response()->json([
                    'message' => 'No tienes acceso a este alumno',
                ], 401);
            }
        }

        $asignaturasCursadas = Alumno::query()
            ->select([
                'asignaturas.asignatura_id',
                'asignaturas.nombre as asignatura',
                'asignaturas.creditos',
                'asignaturas_cursadas.calificacion',
            ])
            ->where('alumnos.numero_control', '=', $numeroControl)
            ->join('asignaturas_cursadas', 'alumnos.numero_control', '=', 'asignaturas_cursadas.numero_control')
            ->join('asignaturas', 'asignaturas_cursadas.asignatura_id', '=', 'asignaturas.asignatura_id')
            ->get();

        $adeudos = Adeudo::query()
            ->where('numero_control', '=', $numeroControl)
            ->withCasts([
                'importe' => 'double'
            ])
            ->get(['descripcion', 'importe']);

        $alumno->asignaturas_cursadas = $asignaturasCursadas;
        $alumno->adeudos = $adeudos;

        return response()->json($alumno);
    }
}
