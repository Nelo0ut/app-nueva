<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Bitlogin;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function index()
    {
        return view('login.index');
    }

    public function login(Request $request)
    {
        $request->validate([
            'usuario'  => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = [
            'usuario'  => $request->usuario,
            'password' => $request->password,
        ];

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'usuario' => 'Usuario o contraseña incorrectos',
            ]);
        }

        $request->session()->regenerate();

        /** @var User $user */
        $user = Auth::user();

        // VALIDAR ESTADO DEL ROL
        if ($user->role->flestado != 1) {
            Auth::logout();
            $request->session()->invalidate();

            return redirect('/')
                ->withErrors(['error' => 'Estas credenciales no coinciden con nuestros registros']);
        }

        // REGISTRAR BITACORA DE LOGIN
        Bitlogin::create([
            'usuario_id' => $user->id,
            'usuario'    => $user->usuario,
            'istipo'     => 1,
        ]);

        // SESIÓN CUSTOM (LEGACY)
        if (method_exists($user, 'setSession')) {
            $user->setSession($user->role);
        }

        // REDIRECCIÓN POR ROL
        if (in_array($user->role->id, [3, 4])) {
            return redirect('/entidad');
        }

        return redirect('/admin');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

