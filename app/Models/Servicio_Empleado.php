<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio_Empleado extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'servicios_empleado';

    protected $fillable = [
        'id_servicio_empleado',
        'id_empleado',
        'id_servicio',
    ];

    public function empleado()
    {
        return $this->belongsTo('App\Models\Empleado', 'id_empleado', 'id_empleado');
    }

    public function servicio()
    {
        return $this->belongsTo('App\Models\Servicio', 'id_servicio', 'id_servicio');
    }
}
