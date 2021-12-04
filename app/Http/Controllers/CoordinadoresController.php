<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CoordinadoresController extends Controller
{
    public function index(): JsonResponse
    {
        $carreras = Carrera::query()
            ->select([
                'coordinadores.usuario_id',
                'carreras.*',
            ])
            ->select()
            ->join('coordinadores', 'carreras.carrera_id', '=', 'coordinadores.carrera_id')
            ->get();

        $coordinadores = User::query()->where('rol_id', '=', 'coordinador')->get();

        foreach ($coordinadores as $coordinador) {
            $coordinador->carreras = collect($carreras)->where('usuario_id', '=', $coordinador->id)->all();
        }

        return response()->json($coordinadores);
    }
}
