<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleados extends Model
{
    use HasFactory;

    protected $table = 'empleados';

    protected $fillable = [
        'numero_empleado',
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'rol',
        'tipo_empleado'
    ];

    protected $casts = [
        'rol' => 'string',
        'tipo_empleado' => 'string'
    ];
}
