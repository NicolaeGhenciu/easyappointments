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
    protected $primaryKey = 'id_cita';
    protected $table = 'citas';

    protected $fillable = [
        'id_cita',
        'id_cliente',
        'id_empresa',
        'id_empleado',
        'id_servicio',
        'fecha_inicio',
        'fecha_fin',
        'status'
    ];

    public function empresa()
    {
        return $this->belongsTo('App\Models\Empresa', 'id_empresa', 'id_empresa');
    }

    public function empleado()
    {
        return $this->belongsTo('App\Models\Empleado', 'id_empleado', 'id_empleado');
    }

    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente', 'id_cliente', 'id_cliente');
    }

    public function servicio()
    {
        return $this->belongsTo('App\Models\Servicio', 'id_servicio', 'id_servicio');
    }
}
