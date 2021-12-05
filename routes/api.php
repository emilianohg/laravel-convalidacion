<?php

use App\Http\Controllers\AlumnosController;
use App\Http\Controllers\AsignaturasController;
use App\Http\Controllers\CarrerasController;
use App\Http\Controllers\CoordinadoresController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SolicitudesController;

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('carreras', [CarrerasController::class, 'index']);
    Route::get('asignaturas', [AsignaturasController::class, 'index']);
    Route::get('coordinadores', [CoordinadoresController::class, 'index']);
    Route::get('alumnos', [AlumnosController::class, 'index']);
    Route::get('alumnos/me', [AlumnosController::class, 'me']);
    Route::get('alumnos/{numero_control}', [AlumnosController::class, 'show']);
    Route::get('solicitudes', [SolicitudesController::class, 'index']);
    Route::post('solicitudes', [SolicitudesController::class, 'store']);
    Route::get('solicitudes/{solicitud_id}', [SolicitudesController::class, 'show']);
});

Route::post('login', LoginController::class);