<?php

namespace App\Http\Controllers;

use App\Models\Empleados;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    /**
     * @description Obtener todos los empleados
     * @author Mario Evenezer
     */
    public function obtenerEmpleados()
    {
        // Obtener todos los empleados
        $empleados = Empleados::all();

        // Verificar si hay empleados registrados
        if ($empleados->isEmpty()) {
            return response()->json(['message' => 'No se encontraron empleados'], 404);
        }

        // Retornar los empleados
        return response()->json($empleados, 200);
    }

    /**
     * @description Obtener un empleado por su ID
     * @author Mario Evenezer
     * @param int $id (ID del empleado)
     */
    public function obtenerEmpleadoPorId($id)
    {
        // Validación del ID
        if (!is_numeric($id)) {
            return response()->json(['message' => 'ID debe ser un número'], 400);
        }

        // Validación de ID positivo
        if($id <= 0) {
            return response()->json(['message' => 'ID debe ser un número positivo'], 400
            );
        }

        // Buscar el empleado por ID
        $empleado = Empleados::find($id);

        // Verificar si el empleado existe
        if (!$empleado) {
            return response()->json(['message' => 'Empleado no encontrado'], 404);
        }

        // Retornar el empleado encontrado
        return response()->json($empleado, 200);
    }

    /**
     * @description Crear un nuevo empleado
     * @author Mario Evenezer
     * @param Request $request (Datos del empleado)
     */
    public function crearEmpleado(Request $request)
    {
        try {
            // Validaciones para creación
            $request->validate([
                'numero_empleado' => 'required|integer|unique:empleados,numero_empleado',
                'nombres' => 'required|string|max:255',
                'apellido_paterno' => 'required|string|max:255',
                'apellido_materno' => 'nullable|string|max:255',
                'rol' => 'required|in:chofer,cargador,auxiliar',
                'tipo_empleado' => 'required|in:interno,subcontratado'
            ]);

            // Crear el empleado
            $empleado = Empleados::create($request->all());
            
            // Retornar respuesta de éxito
            return response()->json([
                'message' => 'Empleado creado exitosamente',
                'data' => $empleado
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) { // Capturar errores de validación
            // Retornar errores de validación
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Illuminate\Database\QueryException $e) { // Capturar errores de base de datos
            // Retornar error de base de datos
            return response()->json([
                'message' => 'Error al actualizar el empleado',
                'error' => $e->getMessage()
            ], 500);
            
        } catch (\Exception $e) { // Capturar cualquier otro error
            // Retornar error inesperado
            return response()->json([
                'message' => 'Error inesperado al crear el empleado',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @description Actualizar un empleado por su ID
     * @author Mario Evenezer
     * @param Request $request (Datos para actualizar)
     * @param int $id (ID del empleado)
     */
    public function actualizarEmpleado(Request $request, $id)
    {
        // Validación del ID
        if (!is_numeric($id)) {
            return response()->json(['message' => 'ID debe ser un número'], 400);
        }

        // Validación de ID positivo
        if ($id <= 0) {
            return response()->json(['message' => 'ID debe ser un número positivo'], 400);
        }

        try {
            // Validación del ID
            $empleado = Empleados::find($id);

            // Verificar si el empleado existe
            if (!$empleado) {
                return response()->json(['message' => 'Empleado no encontrado'], 404);
            }

            // Validaciones para actualización
            $validatedData = $request->validate([
                'numero_empleado' => 'sometimes|required|integer|unique:empleados,numero_empleado,' . $id,
                'nombres' => 'sometimes|required|string|max:255',
                'apellido_paterno' => 'sometimes|required|string|max:255',
                'apellido_materno' => 'sometimes|required|nullable|string|max:255',
                'rol' => 'sometimes|required|in:chofer,cargador,auxiliar',
                'tipo_empleado' => 'sometimes|required|in:interno,subcontratado'
            ]);

            // Actualizar el empleado
            $empleado->update($validatedData);
            
            // Retornar respuesta de éxito
            return response()->json([
                'message' => 'Empleado actualizado exitosamente',
                'data' => $empleado->fresh() // fresh() recarga el modelo desde la BD
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) { // Capturar errores de validación
            // Retornar errores de validación
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Illuminate\Database\QueryException $e) { // Capturar errores de base de datos
            // Retornar error de base de datos
            return response()->json([
                'message' => 'Error al actualizar el empleado',
                'error' => $e->getMessage()
            ], 500);
            
        } catch (\Exception $e) { // Capturar cualquier otro error
            // Retornar error inesperado
            return response()->json([
                'message' => 'Error inesperado al actualizar el empleado',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @description Eliminar un empleado por su ID
     * @author Mario Evenezer
     * @param int $id (ID del empleado)
     */
    public function eliminarEmpleado($id)
    {
        try {
            // Validación del ID
            if (!is_numeric($id)) {
                return response()->json(['message' => 'ID debe ser un número'], 400);
            }
            // Validación de ID positivo
            if($id <= 0) {
                return response()->json(['message' => 'ID debe ser un número positivo'], 400);
            }

            // Buscar el empleado por ID
            $empleado = Empleados::find($id);

            // Verificar si el empleado existe
            if (!$empleado) {
                return response()->json(['message' => 'Empleado no encontrado'], 404);
            }

            // Intentar eliminar el empleado
            $empleado->delete();

            // Retornar respuesta de éxito
            return response()->json(['message' => 'Empleado eliminado con éxito']);
        } catch (\Illuminate\Database\QueryException $e) { // Capturar errores de base de datos
            // Retornar error de base de datos
            return response()->json([
                'message' => 'Error al eliminar el empleado',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
