<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parametros_Nomina extends Model
{
    //
    use HasFactory;

    protected $table = 'parametros_nomina';

    protected $fillable = [
        'turno_hora',
        'pago_hora',
        'bono_entrega',
        'bono_chofer',
        'bono_cargador',
        'vales'
    ];
}
