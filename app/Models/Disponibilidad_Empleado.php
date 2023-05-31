<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Disponibilidad_Empleado extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;
    protected $primaryKey = 'id_disponibilidad';
    protected $table = 'disponibilidad_empleado';

    protected $fillable = [
        'id_disponibilidad',
        'id_empleado',
        'dia_semana',
        'hora_inicio',
        'hora_fin',
    ];

    public function empleado()
    {
        return $this->belongsTo('App\Models\Empleado', 'id_empleado', 'id_empleado');
    }
}
