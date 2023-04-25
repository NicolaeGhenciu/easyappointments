<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Servicio extends Model
{
    use HasFactory;

    use SoftDeletes;

    public $timestamps = false;

    protected $table = 'servicios';

    protected $fillable = [
        'id_empresa',
        'id_servicio',
        'cod',
        'nombre',
        'descripcion',
        'precio',
    ];

    public function empresa()
    {
        return $this->belongsTo('App\Models\Empresa', 'id_empresa', 'id_empresa');
    }
}
