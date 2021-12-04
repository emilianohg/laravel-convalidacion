<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use Illuminate\Http\JsonResponse;

class CarrerasController extends Controller
{
    public function index(): JsonResponse
    {
        $carreras = Carrera::query()
            ->select([
                'carreras.carrera_id',
                'carreras.instituto_id',
                'coordinadores.usuario_id as coordinador_id',
                'carreras.nombre as carrera',
                'usuarios.email as correo_cordinador',
                'usuarios.nombre as coordinador',
            ])
            ->join('coordinadores', 'carreras.carrera_id', '=', 'coordinadores.carrera_id')
            ->join('usuarios', 'coordinadores.usuario_id', '=', 'usuarios.id')
            ->get();

        return response()->json($carreras);
    }
}
