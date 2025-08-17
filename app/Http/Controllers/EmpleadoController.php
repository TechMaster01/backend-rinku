<?php

namespace App\Http\Controllers;

use App\Models\Empleados;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    //
    public function obtenerEmpleados()
    {
        $empleados = Empleados::all();

        if ($empleados->isEmpty()) {
            return response()->json(['message' => 'No se encontraron empleados'], 404);
        }

        return response()->json($empleados, 200);
    }

    public function obtenerEmpleadoPorId($id)
    {
        $empleado = Empleados::find($id);

        if (!$empleado) {
            return response()->json(['message' => 'Empleado no encontrado'], 404);
        }

        return response()->json($empleado, 200);
    }

    public function crearEmpleado(Request $request)
    {
        try {
            $request->validate([
                'numero_empleado' => 'required|integer|unique:empleados,numero_empleado',
                'nombres' => 'required|string|max:255',
                'apellido_paterno' => 'required|string|max:255',
                'apellido_materno' => 'nullable|string|max:255',
                'rol' => 'required|in:chofer,cargador,auxiliar',
                'tipo_empleado' => 'required|in:interno,subcontratado'
            ]);

            $empleado = Empleados::create($request->all());
            
            return response()->json([
                'message' => 'Empleado creado exitosamente',
                'data' => $empleado
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Error al actualizar el empleado',
                'error' => $e->getMessage()
            ], 500);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error inesperado al crear el empleado',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function actualizarEmpleado(Request $request, $id)
    {
        try {
            $empleado = Empleados::find($id);

            if (!$empleado) {
                return response()->json(['message' => 'Empleado no encontrado'], 404);
            }

            // Validaciones para actualización
            $validatedData = $request->validate([
                'numero_empleado' => 'sometimes|required|integer|unique:empleados,numero_empleado,' . $id,
                'nombres' => 'sometimes|required|string|max:255',
                'apellido_paterno' => 'sometimes|required|string|max:255',
                'apellido_materno' => 'nullable|string|max:255',
                'rol' => 'sometimes|required|in:chofer,cargador,auxiliar',
                'tipo_empleado' => 'sometimes|required|in:interno,subcontratado'
            ]);

            $empleado->update($validatedData);
            
            return response()->json([
                'message' => 'Empleado actualizado exitosamente',
                'data' => $empleado->fresh() // fresh() recarga el modelo desde la BD
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Error al actualizar el empleado',
                'error' => $e->getMessage()
            ], 500);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error inesperado al actualizar el empleado',
                'error' => $e->getMessage()
            ], 500);
        }
    }

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

            $empleado = Empleados::find($id);

            if (!$empleado) {
                return response()->json(['message' => 'Empleado no encontrado'], 404);
            }

            $empleado->delete();
            return response()->json(['message' => 'Empleado eliminado con éxito']);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Error al eliminar el empleado',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
