<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'clientes';

    protected $fillable = [
        'dni',
        'nombre',
        'apellidos',
        'fecha_nacimiento',
        'direccion',
        'telefono',
        'provincia_id',
        'municipio_id'
    ];
}
