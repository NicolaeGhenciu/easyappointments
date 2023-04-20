<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empleado extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;

    protected $table = 'empleados';

    protected $fillable = [
        'id_empleado',
        'id_empresa',
        'nif',
        'nombre',
        'apellidos',
        'cargo',
        'fecha_nacimiento',
        'direccion',
        'telefono',
        'provincia_id',
        'municipio_id'
    ];

    public function provincia()
    {
        return $this->belongsTo('App\Models\Provincia', 'provincia_id', 'id');
    }

    public function municipio()
    {
        return $this->belongsTo('App\Models\Municipio', 'municipio_id', 'id');
    }
}
