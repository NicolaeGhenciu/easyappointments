<?php

namespace App\Http\Controllers;

use App\Models\Municipio;
use Illuminate\Http\Request;

class MunicipioController extends Controller
{
    public function municipiosPorProvincia($id)
    {
        $municipios = Municipio::where('provincia_id', $id)->get();

        return response()->json($municipios);
    }
}
