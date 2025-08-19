<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entregas extends Model
{
    //
    use HasFactory;

    protected $table = 'entregas';

    protected $fillable = [
        'empleado_id',
        'fecha',
        'cantidad_entregas'
    ];
}