<?php

namespace App\Http\Controllers;

use App\Models\Empleados;
use App\Models\Entregas;
use Illuminate\Http\Request;

class EntregasController extends Controller
{
    /**
     * @descripción Obtiene todas las entregas registradas.
     * @author Mario Evenezer
     */
    public function obtenerEntregas()
    {
        // Obtener todas las entregas registradas
        $entregas = Entregas::all();

        // Verificar si hay entregas registradas
        if ($entregas->isEmpty()) {
            return response()->json(['message' => 'No hay entregas registradas'], 404);
        }

        // Retornar las entregas
        return response()->json($entregas, 200);
    }

    /**
     * @descripción Obtiene un registro de entregas por su ID.
     * @author Mario Evenezer
     * @param int $id (ID del registro de entregas a obtener)
     */
    public function obtenerEntregaPorId($id)
    {
        // Validación rápida del ID
        if (!is_numeric($id) || (int)$id <= 0) {
            return response()->json(['message' => 'ID debe ser un número positivo'], 400
            );
        }

        // Buscar la el registro de entregas por ID
        $entrega = Entregas::find($id);

        // Verificar si el registro existe
        if (!$entrega) {
            return response()->json(['message' => 'Registro de entregas no encontrado'], 404);
        }

        // Retornar la entrega encontrada
        return response()->json($entrega, 200);
    }

    /**
     * @description Crear un nuevo registro de entrega
     * @author Mario Evenezer
     * @param Request $request (Datos de la entrega)
     */
    public function crearEntrega(Request $request)
    {
        try {
            // Validaciones para creación de entrega
            $request->validate([
                'empleado_id'        => 'required|integer|exists:empleados,id',
                'fecha'             => 'required|date',
                'cantidad_entregas' => 'required|integer|min:0',
            ]);

            // Crear la entrega
            $entrega = Entregas::create($request->all());

            // Retornar respuesta de éxito
            return response()->json([
                'message' => 'Entrega registrada exitosamente',
                'data'    => $entrega
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
                'message' => 'Error al registrar la entrega',
                'error'   => $e->getMessage()
            ], 500);

        } catch (\Exception $e) { // Capturar errores inesperados
            // Retornar error inesperado
            return response()->json([
                'message' => 'Error inesperado al registrar la entrega',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @description Actualizar un registro de entrega por su ID
     * @author Mario Evenezer
     * @param Request $request (Datos para actualizar)
     * @param int $id (ID de la entrega)
     */
    public function actualizarEntrega(Request $request, $id)
    {
        // Validación rápida del ID
        if (!is_numeric($id) || (int)$id <= 0) {
            return response()->json(['message' => 'ID debe ser un número positivo'], 400);
        }

        try {
            // Buscar entrega
            $entrega = Entregas::find($id);

            // Verificar si la entrega existe
            if (!$entrega) {
                return response()->json(['message' => 'Registro de entregas no encontrado'], 404);
            }

            // Validaciones para actualización
            $datosValidados = $request->validate([
                'empleado_id'        => 'sometimes|required|integer|exists:empleados,id',
                'fecha'             => 'sometimes|required|date',
                'cantidad_entregas' => 'sometimes|required|integer|min:0',
            ]);

            // Actualizar entrega
            $entrega->update($datosValidados);

            return response()->json([
                'message' => 'Entrega actualizada exitosamente',
                'data'    => $entrega->fresh()
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors'  => $e->errors()
            ], 422);

        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Error al actualizar la entrega',
                'error'   => $e->getMessage()
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error inesperado al actualizar la entrega',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @descripción Elimina un registro de entregas por su ID.
     * @author Mario Evenezer
     * @param int $id (ID del registro de entregas a eliminar)
     */
    public function eliminarEntrega($id)
    {
        // Validación rápida del ID
        if (!is_numeric($id) || (int)$id <= 0) {
            return response()->json(['message' => 'ID debe ser un número positivo'], 400
            );
        }

        // Buscar el registro de entregas por ID
        $entrega = Entregas::find($id);

        // Verificar si el registro existe
        if (!$entrega) {
            return response()->json(['message' => 'Registro de entregas no encontrado'], 404);
        }

        // Eliminar la entrega
        $entrega -> delete();

        // Responder
        return response()->json(['message' => 'Registro de entregas eliminado con éxito']);
    }

    /**
     * @descripcion Obtener entregas por ID de empleado
     * @author Mario Evenezer
     * @param int $empleado_id (ID del empleado)
     */
    public function obtenerEntregasPorEmpleado($empleado_id)
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

        // Obtener entregas del empleado
        $entregas = Entregas::where('empleado_id', $empleado_id)->get();

        // Verificar si se encontraron entregas
        if ($entregas->isEmpty()) {
            return response()->json(['message' => 'No se encontraron entregas para este empleado'], 404);
        }

        // Retornar las entregas encontradas
        return response()->json($entregas);
    }
}
