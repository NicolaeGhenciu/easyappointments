<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Rules\CifRules;
use App\Rules\DniRule;
use App\Models\User;
use App\Models\Empresa;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Empresa_Cliente;
use App\Services\PasswordGenerator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function crearUsuarioEmpresa()
    {

        $datos = request()->merge(['role' => 'empresa'])->all();

        session()->flash('empresa');

        $datos = request()->validate([
            'nombre' => 'required|min:3|max:100',
            'cif' => ['required', new CifRules],
            'email' => 'required|email',
            'password' => 'required|min:6|max:15|regex:/^[^,]*$/',
            'telefono' => 'required|regex:/^(?:(?:\+?[0-9]{2,4})?[ ]?[6789][0-9 ]{8,13})$/',
            'direccion' => 'required|min:6|max:100',
            'provincia_id' => 'required',
            'municipio_id_e' => 'required',
            'role' => '',
        ]);

        $datos['municipio_id'] = $datos['municipio_id_e'];
        unset($datos['municipio_id_e']);

        if (emailExists($datos['email'])) {
            session()->flash('error', 'El correo electrónico ya existe en nuestra base de datos.');
            return back();
        }

        $datos['password'] = Hash::make($datos['password']);

        $empresa = Empresa::create($datos);

        $datos['empresa_id'] = $empresa->id;

        User::create($datos);

        session()->forget('empresa');

        session()->flash('message', 'Empresa registrado correctamente.');

        $email = "nicoadrianx42x@gmail.com";
        //$email = $datos['email'];

        Mail::send('email.user.bienvenidoEmpresa', ['empresa' => $empresa], function ($message) use ($email) {
            $message->from('easyappointments@empresa.com', 'Easyappointments');
            $message->to($email)
                ->subject('Bienvenido a Easyappointments');
        });

        return back();
    }

    public function crearUsuarioCliente()
    {

        $datos = request()->merge(['role' => 'cliente'])->all();

        session()->flash('cliente');

        session()->flash('crear');

        if (emailExists($datos['email'])) {
            session()->flash('error', 'El correo electrónico ya existe en nuestra base de datos.');
            return back();
        }

        if (Auth::check()) {

            $datos = request()->validate([
                'nif' => ['required', new DniRule],
                'nombre' => 'required|min:3|max:100',
                'apellidos' => 'required|min:3|max:100',
                'fecha_nacimiento' => ['required', 'date', 'before: -16 years'],
                'email' => 'required|email',
                'password' => '',
                'direccion' => 'required|min:6|max:100',
                'telefono' => 'required|regex:/^(?:(?:\+?[0-9]{2,4})?[ ]?[6789][0-9 ]{8,13})$/',
                'provincia_id' => 'required',
                'municipio_id' => 'required',
                'role' => '',
            ]);

            $pass = PasswordGenerator::generatePass();
            $datos['password'] = Hash::make($pass);
        } else {

            $datos = request()->validate([
                'nif' => ['required', new DniRule],
                'nombre' => 'required|min:3|max:100',
                'apellidos' => 'required|min:3|max:100',
                'fecha_nacimiento' => ['required', 'date', 'before: -16 years'],
                'email' => 'required|email',
                'password' => 'required|min:6|max:15|regex:/^[^,]*$/',
                'direccion' => 'required|min:6|max:100',
                'telefono' => 'required|regex:/^(?:(?:\+?[0-9]{2,4})?[ ]?[6789][0-9 ]{8,13})$/',
                'provincia_id' => 'required',
                'municipio_id' => 'required',
                'role' => '',
            ]);

            $datos['password'] = Hash::make($datos['password']);
        }

        $cliente = Cliente::create($datos);

        $datos['cliente_id'] = $cliente->id;

        User::create($datos);

        if (Auth::check()) {
            if (Auth::user()->role == 'empresa') {
                Empresa_Cliente::create([
                    'id_cliente' => $cliente->id,
                    'id_empresa' => Auth::user()->empresa_id,
                ]);
            } else {
                $empleado = Empleado::where('id_empleado', Auth::user()->empleado_id)->first();
                Empresa_Cliente::create([
                    'id_cliente' => $cliente->id,
                    'id_empresa' => $empleado->id_empresa,
                ]);
            }
        }

        session()->forget('cliente');

        session()->forget('crear');

        session()->flash('message', 'Cliente registrado correctamente.');

        $email = "nicoadrianx42x@gmail.com";
        //$email = $datos['email'];

        if (Auth::check()) {
            Mail::send('email.user.bienvenidoClienteEmpresa', ['pass' => $pass, 'cliente' => $cliente], function ($message) use ($email) {
                $message->from('easyappointments@empresa.com', 'Easyappointments');
                $message->to($email)
                    ->subject('Bienvenido a Easyappointments');
            });
        } else {
            Mail::send('email.user.bienvenidoCliente', ['cliente' => $cliente], function ($message) use ($email) {
                $message->from('easyappointments@empresa.com', 'Easyappointments');
                $message->to($email)
                    ->subject('Bienvenido a Easyappointments');
            });
        }

        return back();
    }

    public function crearUsuarioEmpleado()
    {

        $datos = request()->merge(['role' => 'empleado'])->all();

        session()->flash('crear');

        $datos = request()->validate([
            'nif' => ['required', new DniRule],
            'nombre' => 'required|min:3|max:100',
            'apellidos' => 'required|min:3|max:100',
            'cargo' => 'required|min:2|max:100',
            'fecha_nacimiento' => ['required', 'date', 'before: -18 years'],
            'email' => 'required|email',
            'password' => '',
            'direccion' => 'required|min:6|max:100',
            'telefono' => 'required|regex:/^(?:(?:\+?[0-9]{2,4})?[ ]?[6789][0-9 ]{8,13})$/',
            'provincia_id' => 'required',
            'municipio_id' => 'required',
            'role' => '',
        ]);

        if (emailExists($datos['email'])) {
            session()->flash('error', 'El correo electrónico ya existe en nuestra base de datos.');
            return back();
        }

        $pass = PasswordGenerator::generatePass();

        $datos['password'] = Hash::make($pass);

        $datos['id_empresa'] = Auth::user()->empresa_id;

        $empleado = Empleado::create($datos);

        $datos['empleado_id'] = $empleado->id;

        User::create($datos);

        session()->forget('crear');

        session()->flash('message', 'Empleado dado de alta correctamente.');

        $email = "nicoadrianx42x@gmail.com";
        //$email = $datos['email'];

        Mail::send('email.user.bienvenidoEmpleado', ['pass' => $pass, 'empleado' => $empleado, 'empresa' => Auth::user()], function ($message) use ($email) {
            $message->from(Auth::user()->email, Auth::user()->nombre);
            $message->to($email)
                ->subject('Bienvenido a Easyappointments');
        });

        return back();
    }

    public function recuperarContraseña()
    {
        session()->flash('recuperar');

        $datos = request()->validate([
            'nif_cif' => 'required|min:6|max:100',
            'email' => 'required|email',
        ]);

        $user = User::where('email', $datos['email'])->first();

        if (!$user) {
            session()->flash('error-recuperar', 'No se ha encontrado ningún usuario con el correo electrónico proporcionado.');
            return back();
        }

        $pass = PasswordGenerator::generatePass();

        $email = "nicoadrianx42x@gmail.com";

        $datos['password'] = Hash::make($pass);

        if ($user->empresa_id) {
            $model = Empresa::where('cif', $datos['nif_cif'])
                ->where('id_empresa', $user->empresa_id)
                ->first();
        } elseif ($user->empleado_id) {
            $model = Empleado::where('nif', $datos['nif_cif'])
                ->where('id_empleado', $user->empleado_id)
                ->first();
        } elseif ($user->cliente_id) {
            $model = Cliente::where('nif', $datos['nif_cif'])
                ->where('id_cliente', $user->cliente_id)
                ->first();
        }

        if ($model) {
            unset($datos['email'], $datos['nif_cif']);
            $user->update($datos);
            Mail::send('email.recuperar.recuperar', ['pass' => $pass, 'nombre' => $model->nombre, 'apellidos' => $model->apellidos ?? ''], function ($message) use ($email) {
                $message->from('easyappointments@empresa.com', 'Easyappointments')
                    ->to($email)
                    ->subject('Nueva contraseña, EasyAppointments');
            });
            session()->flash('message-recuperar', 'Tu contraseña ha sido cambiada exitosamente. La nueva contraseña te ha sido enviada a tu correo electrónico.');
            return back();
        }

        session()->flash('error-recuperar', 'No se ha encontrado ningún usuario con el nif/cif proporcionado.');
        return back();
    }

    public function miCuenta()
    {
        if (Auth::user()->role == 'empresa') {
            $empresa = Empresa::where('id_empresa', (Auth::user()->empresa_id))
                ->first();
            return view('cuenta.miCuenta', ['empresa' => $empresa]);
        }
        if (Auth::user()->role == 'empleado') {
            $empleado = Empleado::where('id_empleado', (Auth::user()->empleado_id))
                ->first();
            return view('cuenta.miCuenta', ['empleado' => $empleado]);
        }
        if (Auth::user()->role == 'cliente') {
            $cliente = Cliente::where('id_cliente', (Auth::user()->cliente_id))
                ->first();
            return view('cuenta.miCuenta', ['cliente' => $cliente]);
        }
    }

    public function cambiarPass()
    {
        session()->flash('cambiar');

        $datos = request()->validate([
            'pass' => 'required|min:6|max:15|regex:/^[^,]*$/',
            'pass2' => 'required|min:6|max:15|regex:/^[^,]*$/',
        ]);

        if ($datos['pass'] != $datos['pass2']) {
            session()->flash('error', 'Las contraseñas no coinciden');
            return back();
        }

        $pass = Hash::make($datos['pass']);

        $user = User::where('id', Auth::user()->id)->first();
        $user->update([
            'password' => $pass,
        ]);

        session()->forget('cambiar');
        session()->flash('message', 'Tu contraseña ha sido cambiada exitosamente.');
        return back();
    }
}


function emailExists($email)
{
    $user = User::where('email', $email)->first();
    return $user ? true : false;
}
