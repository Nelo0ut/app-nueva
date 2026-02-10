<?php

namespace App\Http\Controllers\CCE;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Entidad;
use App\Models\Role;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use App\Mail\ActualizarUsuario;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //  $users = User::with(['entidad','role'])->select('users.id','users.name','users.email','users.role_id','users.entidad_id','users.flestado','roles.tipo','entidads.alias')
        //                     ->join('roles','roles.id', '=', 'role_id')
        //                     ->join('entidads','entidads.id', '=', 'entidad_id')
        //                     ->whereNotIn('users.id', [3,4])->get();
        // dd($users);
        //  $users = User::whereNotIn('users.id', [3,4])->get();
        // dd($users);

        return view('CCE.users.index', [
            'users' => User::whereNotIn('users.role_id', [1,2])->where('flestado','=',1)->get(),
            'entidads' => Entidad::latest()->whereNotIn('id', [1])->get(),
            'roles' => Role::whereNotIn('id', [1,2])->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        // Guardamos
        // $user = User::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => bcrypt($request->password),
        //     'role_id' => $request->role_id,
        //     'entidad_id' => $request->entidad_id,
        //     'flestado' => $request->flestado,
        // ]);

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        if($request->email_2 != ''){
            $user->email_2 = $request->email_2; 
        }
        $user->usuario = $request->usuario;
        $user->password = bcrypt($request->password);
        $user->role_id = $request->role_id;
        $user->entidad_id = $request->entidad_id;
        $user->flestado = $request->flestado;
        $user->save();

        //Retornamos
        return back()->with('status', 'Creado con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        // dd($user);
        return view('CCE.users.editar',[
            'user' => $user,
            'entidads' => Entidad::latest()->whereNotIn('id', [1])->get(),
            'roles' => Role::whereNotIn('id', [1, 2])->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {   
      
        //Actualizamos
        //$user->update($request->all());
        //$user = new User;
        $msgCambios = '';

        if ($user->name != $request->name) {
            $user->name = $request->name;
            $msgCambios .= "Se actualizó el nombre<br>";
        }

        if ($user->email != $request->email) {
            $user->email = $request->email;
            $msgCambios .= "Se actualizó el correo<br>";
        }

        if($request->email_2 != ''){
            if ($user->email_2 != $request->email_2) {
                $user->email_2 = $request->email_2;
                $msgCambios .= "Se actualizó el correo adicional<br>";
            }
        }

        if ($user->usuario != $request->usuario) {
            $user->usuario = $request->usuario;
            $msgCambios .= "Se actualizó el usuario del login<br>";
        }

        if ($user->role_id != $request->role_id) {
            $user->role_id = $request->role_id;
            $msgCambios .= "Se actualizó el perfil<br>";
        }

        if ($user->entidad_id != $request->entidad_id) {
            $user->entidad_id = $request->entidad_id;
            $msgCambios .= "Se actualizó la entidad a la que pertenece<br>";
        }

        if ($user->flestado != $request->flestado) {
            $user->flestado = $request->flestado;
            $msgCambios .= "Se actualizó el estado<br>";
        }



        if ($request->password) {
            if ($user->password != bcrypt($request->password)) {
                $user->password = bcrypt($request->password);
                $msgCambios .= "Se actualizó la contraseña<br>";
            }
        }

        $user->update();
        
        /* Enviar Mail */
        $usuario = DB::table('users')
                                    ->join('entidads', 'users.entidad_id', '=', 'entidads.id')
                                    ->select('users.name', 'users.email AS email' , 'users.email_2 AS email_2', 'entidads.name AS namenet')
                                    ->where('users.entidad_id', $request->entidad_id)
                                     ->get()->toArray();

        if (count($usuario) > 0) {
            $data = [
                'usuario' => $request->usuario,
                'nombre' => $usuario[0]->namenet,
                'url' => config('app.url'),
                'mensaje' => $msgCambios,
            ];


            $array = array();
            for($i = 0; $i< sizeof($usuario) ; $i++ ){
                if($usuario[$i]->email_2){
                    array_push($array,  $usuario[$i]->email_2);
                }
                array_push($array,  $usuario[$i]->email);
            }
            Mail::to($array)->send(new ActualizarUsuario($data));
        }

        //Retornamos
        return back()->with('status', 'Actualizado con éxito');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }


    //Entidad - Usuario

    public function lista()
    {
        //  $users = User::with(['entidad','role'])->select('users.id','users.name','users.email','users.role_id','users.entidad_id','users.flestado','roles.tipo','entidads.alias')
        //                     ->join('roles','roles.id', '=', 'role_id')
        //                     ->join('entidads','entidads.id', '=', 'entidad_id')
        //                     ->whereNotIn('users.id', [3,4])->get();
        // dd($users);
        //  $users = User::whereNotIn('users.id', [3,4])->get();
        // dd($users);
        $roles=Role::whereNotIn('id', [1, 2, 3])->get();
        return view('Entidad.users.index', [
            'users' => User::where('entidad_id', session()->get('identidad'))->get(),
            'roles' => $roles
        ]);
    }

    public function editar($id)
    {
        $user = User::findOrFail($id);
        return view('Entidad.users.editar', [
            'user' => $user,
            'roles' => Role::whereNotIn('id', [1, 2])->get()
        ]);
    }

    public function actualizar(UserRequest $request, $id)
    {
        
        //Actualizamos
        //$user->update($request->all());
        $msgCambios = "";
        $user = User::findOrFail($id);
        
        if($user->name != $request->name){
            $user->name = $request->name;
            $msgCambios .= "Se actualizó el nombre<br>";
        }

        if ($user->email != $request->email) {
            $user->email = $request->email;
            $msgCambios .= "Se actualizó el correo<br>";
        }

        if($request->email_2 != ''){
            if ($user->email_2 != $request->email_2) {
                $user->email_2 = $request->email_2;
                $msgCambios .= "Se actualizó el correo adicional<br>";
            }
        }
        
        if ($user->usuario != $request->usuario) {
            $user->usuario = $request->usuario;
            $msgCambios .= "Se actualizó el usuario del login<br>";
        }

        // if ($user->role_id != $request->role_id) {
        //     $user->role_id = $request->role_id;
        //     $msgCambios .= "Se actualizó el perfil<br>";
        // }

        if ($user->entidad_id != $request->entidad_id) {
            $user->entidad_id = $request->entidad_id;
            $msgCambios .= "Se actualizó la entidad a la que pertenece<br>";
        }

        if ($user->flestado != $request->flestado) {
            $user->flestado = $request->flestado;
            $msgCambios .= "Se actualizó el estado<br>";
        }
        
        
        
        if ($request->password) {
            if ($user->password != bcrypt($request->flestado)) {
                $user->password = bcrypt($request->password);
                $msgCambios .= "Se actualizó la contraseña<br>";
            }
            
        }

        $user->update();

        /* Enviar Mail */
        $usuario = DB::table('users')
                                    ->join('entidads', 'users.entidad_id', '=', 'entidads.id')
                                    ->select('users.name', 'users.email', 'users.email_2 AS email_2', 'entidads.name AS namenet')
                                    ->where('users.entidad_id', $request->entidad_id)
                                    ->get()->toArray();
        if (count($usuario) > 0) {
            $data = [
                'usuario' => $request->usuario,
                'nombre' => $usuario[0]->namenet,
                'url' => config('app.url'),
                'mensaje' => $msgCambios,
            ];
            $arrayUsuarios = array();
            for($i = 0; $i< sizeof($usuario) ; $i++ ){
                if($usuario[$i]->email_2){
                    array_push($arrayUsuarios,  $usuario[$i]->email_2);
                }
                array_push($arrayUsuarios,  $usuario[$i]->email);
            }
            Mail::to($arrayUsuarios)->send(new ActualizarUsuario($data));
        }                            

        // if (count($usuario) > 1) {
        //     $data = [
        //         'usuario' => $request->usuario,
        //         'nombre' => $usuario[0]->namenet,
        //         'url' => config('app.url'),
        //         'mensaje' => $msgCambios,
        //     ];
        //     $correos = array();
        //     for ($i=1; $i <count($usuario) ; $i++) { 
        //         array_push($correos, $usuario[$i]->email);
        //     }
        //     Mail::to($usuario[0]->email)->cc($correos)->send(new ActualizarUsuario($data));
        // }

        //Retornamos
        return back()->with('status', 'Actualizado con éxito');
    }

    public function listar_global_usuarios( ){

        return view('Entidad.users.listar_global',[
            'users' => User::whereNotIn('users.role_id', [1,2])->where('flestado','=',1)->get(),
            'entidads' => Entidad::latest()->whereNotIn('id', [1])->get(),
            'roles' => Role::whereNotIn('id', [1,2])->get()
        ]);
    }
}
