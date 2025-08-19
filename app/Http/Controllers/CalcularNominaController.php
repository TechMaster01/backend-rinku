<?php

namespace App\Http\Controllers;

use App\Models\Asistencias;
use App\Models\Empleados;
use App\Models\Entregas;
use App\Models\Parametros_Nomina;

class CalcularNominaController extends Controller
{
    //
    public function calcularNomina($empleado_id, $año, $mes)
    {
        // Buscamos la informacion del empleado
        $empleado = Empleados::find($empleado_id);

        // Verificamos si se encontró el empleado
        if (!$empleado) {
            return response()->json(['message' => 'Empleado no encontrado'], 404);
        }

        // Obtenemos información del periodo de nómina
        $periodoNomina = [
            'año' => $año,
            'mes' => $mes,
        ];

        // Obtenemos parámetros de nómina
        $parametros = Parametros_Nomina::first();

        // Contamos asistencias del empleado en el mes y año especificado
        $asistencias = Asistencias::where('empleado_id', $empleado_id)->whereYear('fecha', $año)->whereMonth('fecha', $mes)->count();

        // Calculamos sueldo base: asistencias × horas_turno × pago_por_hora
        $sueldoBase = $asistencias * $parametros->turno_hora * $parametros->pago_hora;

        // Contamos las entregas del empleado en el mes y año especificado
        $entregas = Entregas::where('empleado_id', $empleado_id)->whereYear('fecha', $año)->whereMonth('fecha', $mes)->sum('cantidad_entregas');

        // Calculamos el bono por entregas: entregas * bono_entrega
        $bonoEntregas = $entregas * $parametros->bono_entrega;

        // Calculamos horas trabajadas
        $horasTrabajadas = $asistencias * $parametros->turno_hora;
        
        // Calculamos el bono por cargo basado en el rol del empleado
        $bonoCargo = 0;
        switch ($empleado->rol) {
            case 'chofer':
                $bonoCargo = $horasTrabajadas * $parametros->bono_chofer;
                break;
            case 'cargador':
                $bonoCargo = $horasTrabajadas * $parametros->bono_cargador;
                break;
            case 'auxiliar':
                $bonoCargo = $horasTrabajadas * $parametros->bono_auxiliar;
                break;
            default:
                $bonoCargo = 0;
        }

        // Calculamos bono por cobertura (solo para auxiliares)
        $bonoCobertura = 0;

        if ($empleado->rol === 'auxiliar') {
            // Contar cuántas veces cubrió como chofer
            $coberturaChofer = Asistencias::where('empleado_id', $empleado_id)->whereYear('fecha', $año)->whereMonth('fecha', $mes)->where('cubrio_turno', true)->where('turno_cubierto', 'chofer')->count();

            // Contar cuántas veces cubrió como cargador
            $coberturaCargador = Asistencias::where('empleado_id', $empleado_id)->whereYear('fecha', $año)->whereMonth('fecha', $mes)->where('cubrio_turno', true)->where('turno_cubierto', 'cargador')->count();

            // Calculamos horas cubiertas por cada rol
            $horasCubiertasChofer = $coberturaChofer * $parametros->turno_hora;
            $horasCubiertasCargador = $coberturaCargador * $parametros->turno_hora;
            
            // Calculamos el bono total por coberturas
            $bonoCobertura = ($horasCubiertasChofer * $parametros->bono_chofer) + ($horasCubiertasCargador * $parametros->bono_cargador);
        }

        // Calculamos el sueldo bruto
        $sueldoBruto = $sueldoBase + $bonoCobertura + $bonoCargo + $bonoEntregas;

        // Calculamos el ISR
        if($sueldoBruto > 16000){
            $ImpuestoSobreRenta = $sueldoBruto*0.12;
        } else {
            $ImpuestoSobreRenta = $sueldoBruto*0.09;
        }

        // Calculamos vales
        if ($empleado->tipo_empleado === 'interno'){
            $Vales = $sueldoBase*$parametros->vales;
        } else {
            $Vales = 0;
        }

        // Calculamos el sueldo neto
        $sueldoNeto = $sueldoBruto - $ImpuestoSobreRenta;

        $Nomina = [
            'Sueldo Base' => $sueldoBase,
            'Bono por cobertura' => $bonoCobertura,
            'Bono por cargo' => $bonoCargo,
            'Bono por entregas' => $bonoEntregas,
            'Sueldo Bruto' => $sueldoBruto,
            'ISR' => $ImpuestoSobreRenta,
            'Sueldo Neto' => $sueldoNeto,
            'Vales' => $Vales,
        ];

        $InformacionPeriodo = [
            'Asistencias' => $asistencias,
            'Horas Trabajadas' => $asistencias * $parametros->turno_hora,
            'Entregas' => $entregas,
        ];

        return response()->json([
            'Periodo Nomina' => $periodoNomina,
            'empleado' => $empleado,
            'Nomina' => $Nomina,
            'Informacion Periodo' => $InformacionPeriodo,
        ]);
    }
}
