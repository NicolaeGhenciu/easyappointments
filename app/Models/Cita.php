<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cita extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;

    protected $table = 'citas';

    protected $fillable = [
        'id_cita',
        'id_cliente',
        'id-empresa',
        'id_empleado',
        'id_servicio',
        'fecha_inicio',
        'fecha_fin',
        'status'
    ];
}
