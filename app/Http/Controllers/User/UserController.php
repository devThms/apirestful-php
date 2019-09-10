<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $usuarios = User::all();  
        
        return response()->json([
            'data' => $usuarios
        ], 200);
        
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ];

        $this->validate($request, $rules);

        $req = $request->all();
        $req['password'] = bcrypt($request->password);
        $req['verified'] = User::USUARIO_NO_VERIFICADO;
        $req['verification_token'] = User::generarVerificationToken();
        $req['admin'] = User::USUARIO_REGULAR;

        $usuario = User::create($req);

        return response()->json([
            'data' => $usuario
        ], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $usuario = User::findOrFail($id);  
        
        return response()->json([
            'data' => $usuario
        ], 200);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $usuario = User::findOrFail($id);

        $rules = [
            'email' => 'email|unique:users,email,' . $usuario->id,
            'password' => 'min:6|confirmed',
            'admin' => 'in:' . User::USUARIO_ADMIN . ',' . User::USUARIO_REGULAR
        ];

        $this->validate($request, $rules);

        if ($request->has('name')) {
            $usuario->name = $request->name;
        }

        if ($request->has('email') && $usuario->email != $request->email) {
            $usuario->verified = User::USUARIO_NO_VERIFICADO;
            $usuario->verification_token = User::generarVerificationToken();
            $usuario->email = $request->email;
        }

        if ($request->has('password')) {
            $usuario->password = bcrypt($request->password);
        }

        if ($request->has('admin')) {
            if (!$usuario->esVerificado()) {
                return response()->json([
                    'error' => 'Unicamente los usuarios verificados pueden cambiar su valor de administrador',
                    'code' => '409'
                ], 409);
            }

            $usuario->admin = $request->admin;
        }

        if (!$usuario->isDirty()) {
            return response()->json([
                'error' => 'Se debe especificar al menos un valor diferente para actualizar',
                'code' => '422'
            ], 422);
        }

        $usuario->save();

        return response()->json([
            'data' => $usuario
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $usuario = User::findOrFail($id);

        $usuario->delete();

        return response()->json([
            'data' => $usuario
        ], 200);

    }
}
