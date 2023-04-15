<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'municipios';

    protected $fillable = [
        'id',
        'provincia_id',
        'municipio'
    ];
}
