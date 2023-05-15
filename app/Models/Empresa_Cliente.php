<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa_Cliente extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'empresa_cliente';

    protected $fillable = [
        'id',
        'id_empresa',
        'id_cliente',
    ];

    public function empresa()
    {
        return $this->belongsTo('App\Models\Empresa', 'id_empresa', 'id_empresa');
    }

    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente', 'id_cliente', 'id_cliente');
    }
}
