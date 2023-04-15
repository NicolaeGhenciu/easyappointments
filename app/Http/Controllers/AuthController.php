<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Provincia;
use App\Models\Municipio;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        $provincias = Provincia::all();
        $municipios = Municipio::all();
        return view('login.login', ['provincias' => $provincias, 'municipios' => $municipios]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            return redirect()->intended('/easyappointments');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
