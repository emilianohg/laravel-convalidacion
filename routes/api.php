<?php

use App\Http\Controllers\AlumnosController;
use App\Http\Controllers\AsignaturasController;
use App\Http\Controllers\CarrerasController;
use App\Http\Controllers\CoordinadoresController;

use Illuminate\Support\Facades\Route;

Route::get('carreras', [CarrerasController::class, 'index']);
Route::get('asignaturas', [AsignaturasController::class, 'index']);
Route::get('coordinadores', [CoordinadoresController::class, 'index']);
Route::get('alumnos', [AlumnosController::class, 'index']);
