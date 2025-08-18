<?php

use Illuminate\Support\Facades\Route;

Route::get('/ping', fn () => ['pong' => true]);

Route::prefix('empleados')->group(function () {
    Route::get('/', [App\Http\Controllers\EmpleadoController::class, 'obtenerEmpleados']);
    Route::get('/{id}', [App\Http\Controllers\EmpleadoController::class, 'obtenerEmpleadoPorId']);
    Route::post('/', [App\Http\Controllers\EmpleadoController::class, 'crearEmpleado']);
    Route::put('/{id}', [App\Http\Controllers\EmpleadoController::class, 'actualizarEmpleado']);
    Route::delete('/{id}', [App\Http\Controllers\EmpleadoController::class, 'eliminarEmpleado']);
});

Route::prefix('asistencias')->group(function () {
    Route::get('/', [App\Http\Controllers\AsistenciasController::class, 'obtenerAsistencias']);
    Route::get('/{id}', [App\Http\Controllers\AsistenciasController::class, 'obtenerAsistenciaPorId']);
    Route::post('/', [App\Http\Controllers\AsistenciasController::class, 'crearAsistencia']);
    Route::put('/{id}', [App\Http\Controllers\AsistenciasController::class, 'actualizarAsistencia']);
    Route::delete('/{id}', [App\Http\Controllers\AsistenciasController::class, 'eliminarAsistencia']);
    Route::get('/empleado/{empleado_id}', [App\Http\Controllers\AsistenciasController::class, 'obtenerAsistenciasPorEmpleado']);
});