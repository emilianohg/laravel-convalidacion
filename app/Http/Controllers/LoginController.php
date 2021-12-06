<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {

        $request->validate([
            'email'     => 'required|email',
            'password'  => 'required',
            'device'    => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = User::find(auth()->id());

        $response = [
            'token'     => $request->user()->createToken($request->device)->plainTextToken,
            'user'      => auth()->user(),
            'message'   => 'Success'
        ];

        if ($user->rol_id == 'academia') {
            $carreras = Carrera::query()
                ->select([
                    'carreras.carrera_id',
                ])
                ->join('academias', 'carreras.carrera_id', '=', 'academias.carrera_id')
                ->where('academias.usuario_id', '=', $user->id)
                ->get()
                ->map(fn ($_carrera) => $_carrera->carrera_id)
                ->toArray();

            $response['carreras'] = $carreras;
        }

        if ($user->rol_id == 'coordinador') {
            $carreras = Carrera::query()
                ->select([
                    'carreras.carrera_id',
                ])
                ->join('coordinadores', 'carreras.carrera_id', '=', 'coordinadores.carrera_id')
                ->where('coordinadores.usuario_id', '=', $user->id)
                ->get()
                ->map(fn ($_carrera) => $_carrera->carrera_id)
                ->toArray();

            $response['carreras_id'] = $carreras;

        }

        return response()->json($response);
    }
}
