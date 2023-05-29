<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'empresas';

    protected $fillable = [
        'id_empresa',
        'cif',
        'nombre',
        'telefono',
        'direccion',
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
