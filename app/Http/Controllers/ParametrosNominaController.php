<?php

namespace App\Http\Controllers;

use App\Models\Parametros_Nomina;
use Illuminate\Http\Request;

class ParametrosNominaController extends Controller
{
    /**
     * @descripción Obtener los parametros de la nomira
     * @author Mario Evenezer
     */
    public function consultarParametrosNomina()
    {
        $parametrosNomina = Parametros_Nomina::all();

        if($parametrosNomina->isEmpty()) {
            return response()->json(['message' => 'No hay parámetros de nómina registrados'], 404);
        }

        return response()->json($parametrosNomina);
    }

    /**
     * @descripción 
     * @author Mario Evenezer
     */
    public function actualizarParametrosNomina(Request $request, $id)
    {
        // Validación del ID
        if (!is_numeric($id) || (int)$id <= 0) {
            return response()->json(['message' => 'ID debe ser un número positivo'], 400);
        }

        try {
            // Buscar el registro de parámetros de nómina por ID
            $parametro = Parametros_Nomina::find($id);

            // Verificar si el registro existe
            if (!$parametro) {
                return response()->json(['message' => 'Parámetro de nómina no encontrado'], 404);
            }

            // Validaciones para actualización
            $datosValidados = $request->validate([
                'turno_hora' => 'sometimes|required|numeric|min:0',
                'pago_hora' => 'sometimes|required|numeric|min:0',
                'bono_entrega' => 'sometimes|required|numeric|min:0',
                'bono_chofer' => 'sometimes|required|numeric|min:0',
                'bono_cargador' => 'sometimes|required|numeric|min:0',
                'vales' => 'sometimes|required|numeric|min:0'
            ]);

            // Actualizar los parámetros de nómina
            $parametro->update($datosValidados);

            // Retornar respuesta de éxito
            return response()->json([
                'message' => 'Parámetros de nómina actualizados exitosamente',
                'data' => $parametro->fresh()
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
                'message' => 'Error al actualizar los parámetros de nómina',
                'error' => $e->getMessage()
            ], 500);

        } catch (\Exception $e) { // Capturar errores inesperados
            // Retornar error inesperado
            return response()->json([
                'message' => 'Error inesperado al actualizar los parámetros de nómina',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
