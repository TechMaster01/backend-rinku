<?php

namespace App\Http\Controllers;

use App\Models\Asistencias;
use App\Models\Empleados;
use Illuminate\Http\Request;

class AsistenciasController extends Controller
{
    /**
     * @descripción Obtiene todas las asistencias registradas.
     * @author Mario Evenezer
     */
    public function obtenerAsistencias()
    {
        // Obtener todas las asistencias registradas
        $asistencias = Asistencias::all();

        // Verificar si hay asistencias registradas
        if ($asistencias->isEmpty()) {
            return response()->json(['message' => 'No hay asistencias registradas'], 404);
        }

        // Retornar las asistencias
        return response()->json($asistencias);
    }

    /**
     * @descripción Obtiene una asistencia por su ID.
     * @author Mario Evenezer
     * @param int $id (ID de la asistencia a obtener)
     */
    public function obtenerAsistenciaPorId($id)
    {
        // Validación rápida del ID
        if (!is_numeric($id) || (int)$id <= 0) {
            return response()->json(['message' => 'ID debe ser un número positivo'], 400);
        }

        // Buscar la asistencia por ID
        $asistencia = Asistencias::find($id);

        // Verificar si la asistencia existe
        if (!$asistencia) {
            return response()->json(['message' => 'Asistencia no encontrada'], 404);
        }

        // Retornar la asistencia encontrada
        return response()->json($asistencia);
    }

    /**
     * @description Registrar asistencia de un empleado
     * @author Mario Evenezer
     * @param Request $request (Datos de la asistencia)
     */
    public function crearAsistencia(Request $request)
    {
        try {
            // Validaciones para creación de asistencia
            $request->validate([
                'empleado_id'     => 'required|integer|exists:empleados,id',
                'fecha'           => 'required|date',
                'cubrio_turno'    => 'required|boolean',
                'turno_cubierto'  => 'nullable|in:chofer,cargador',
            ]);

            // Validación cruzada: si cubrió turno debe indicar cuál
            if ($request['cubrio_turno'] && empty($request['turno_cubierto'])) {
                return response()->json([
                    'message' => 'Error de validación',
                    'errors'  => ['turno_cubierto' => ['El campo turno_cubierto es obligatorio cuando cubrio_turno es verdadero.']]
                ], 422);
            }

            // Crear la asistencia
            $asistencia = Asistencias::create($request->all());

            // Retornar respuesta de éxito
            return response()->json([
                'message' => 'Asistencia registrada exitosamente',
                'data'    => $asistencia
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) { // Capturar errores de validación
            // Retornar errores de validación
            return response()->json([
                'message' => 'Error de validación',
                'errors'  => $e->errors()
            ], 422);

        } catch (\Illuminate\Database\QueryException $e) { // Capturar errores de base de datos
            // Retornar error de base de datos
            return response()->json([
                'message' => 'Error al registrar la asistencia',
                'error'   => $e->getMessage()
            ], 500);

        } catch (\Exception $e) { // Capturar errores inesperados
            // Retornar error inesperado
            return response()->json([
                'message' => 'Error inesperado al registrar la asistencia',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @description Actualizar una asistencia por su ID
     * @author Mario Evenezer
     * @param Request $request (Datos para actualizar)
     * @param int $id (ID de la asistencia)
     */
    public function actualizarAsistencia(Request $request, $id)
    {
        // Validación rápida del ID
        if (!is_numeric($id) || (int)$id <= 0) {
            return response()->json(['message' => 'ID debe ser un número positivo'], 400);
        }

        try {
            // Buscar asistencia
            $asistencia = Asistencias::find($id);

            // Verificar si la asistencia existe
            if (!$asistencia) {
                return response()->json(['message' => 'Asistencia no encontrada'], 404);
            }

            // Validaciones para actualización
            $datosValidados = $request->validate([
                'empleado_id'    => 'sometimes|required|integer|exists:empleados,id',
                'fecha'          => 'sometimes|required|date',
                'cubrio_turno'   => 'sometimes|required|boolean',
                'turno_cubierto' => 'sometimes|nullable|in:chofer,cargador',
            ]);

            // Validación cruzada: si cubrió turno debe indicar cuál
            if ($datosValidados['cubrio_turno'] && empty($datosValidados['turno_cubierto'])) {
                return response()->json([
                    'message' => 'Error de validación',
                    'errors'  => ['turno_cubierto' => ['El campo turno_cubierto es obligatorio cuando cubrio_turno es verdadero.']]
                ], 422);
            }

            // Actualizar asistencia
            $asistencia->update($datosValidados);

            return response()->json([
                'message' => 'Asistencia actualizada exitosamente',
                'data'    => $asistencia->fresh()
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors'  => $e->errors()
            ], 422);

        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Error al actualizar la asistencia',
                'error'   => $e->getMessage()
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error inesperado al actualizar la asistencia',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @descripción Elimina una asistencia por su ID.
     * @param int $id (ID de la asistencia a eliminar)
     * @author Mario Evenezer
     */
    public function eliminarAsistencia($id)
    {
        // Validación rápida del ID
        if (!is_numeric($id) || (int)$id <= 0) {
            return response()->json(['message' => 'ID debe ser un número positivo'], 400);
        }
        
        // buscar la asistencia por ID
        $asistencia = Asistencias::find($id);

        // verificar si la asistencia existe
        if (!$asistencia) {
            return response()->json(['message' => 'Asistencia no encontrada'], 404);
        }

        // eliminar la asistencia
        $asistencia->delete();

        // retornar una respuesta de éxito
        return response()->json(['message' => 'Asistencia eliminada con éxito']);
    }

    /**
     * @descripción Obtiene las asistencias de un empleado específico.
     * @param int $empleado_id (ID del empleado cuyas asistencias se desean obtener)
     * @author Mario Evenezer
     */
    public function obtenerAsistenciasPorEmpleado($empleado_id)
    {
        // verificar que el ID del empleado es un número
        if (!is_numeric($empleado_id) || (int)$empleado_id <= 0) {
            return response()->json(['message' => 'ID de empleado debe ser un número positivo'], 400);
        }

        //verificar que el empleado existe
        $empleado = Empleados::find($empleado_id);
        if (!$empleado) {
            return response()->json(['message' => 'Empleado no encontrado'], 404);
        }

        // obtener las asistencias del empleado
        $asistencias = Asistencias::where('empleado_id', $empleado_id)->get();

        // verificar si hay asistencias registradas
        if ($asistencias->isEmpty()) {
            return response()->json(['message' => 'No hay asistencias registradas para este empleado'], 404);
        }

        // retornar las asistencias del empleado
        return response()->json($asistencias);
    }
}
