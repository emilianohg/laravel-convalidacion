<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LogoutController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        auth()->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sesión cerrada con exito']);
    }
}
