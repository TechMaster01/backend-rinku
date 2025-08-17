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