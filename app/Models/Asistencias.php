<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencias extends Model
{
    //
    use HasFactory;

    protected $table = 'asistencias';

    protected $fillable = [
        'empleado_id',
        'fecha',
        'cubrio_turno',
        'turno_cubierto'
    ];

    protected $casts = [
        'cubrio_turno' => 'boolean',
        'turno_cubierto' => 'string'
    ];
}
