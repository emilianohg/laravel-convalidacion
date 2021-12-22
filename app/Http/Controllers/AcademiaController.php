<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AcademiaController extends Controller
{
    public function index() {
        $academias = User::query()
            ->select([
                'usuarios.*',
                'carreras.*',
            ])
            ->join('academias', 'usuarios.id', '=', 'academias.usuario_id')
            ->join('carreras', 'academias.carrera_id', '=', 'carreras.carrera_id')
            ->where('usuarios.rol_id', '=', 'academia')
            ->get();

        return response()->json($academias);
    }
}
