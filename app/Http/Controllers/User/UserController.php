<?php

namespace App\Http\Controllers\User;

use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

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

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
        {
            $errors = $validator->errors();

            return response()->json([
                'ok' => false,
                'message' => 'Error en validación de formulario',
                'errors' => $errors
            ], 400);

        }

        $request['password'] = bcrypt($request->password);
        $request['verified'] = User::USUARIO_NO_VERIFICADO;
        $request['verification_token'] = User::generarVerificationToken();
        $request['admin'] = User::USUARIO_REGULAR;

        $user = User::create($request->all());

        return response()->json([
            'data' => $user
        ], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {

        return response()->json([
            'data' => $user
        ], 200);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        
        $rules = [
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => 'min:6|confirmed',
            'admin' => 'in:' . User::USUARIO_ADMIN . ',' . User::USUARIO_REGULAR
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
        {
            $errors = $validator->errors();

            return response()->json([
                'ok' => false,
                'message' => 'Existe un error de validación en el formulario',
                'errors' => $errors
            ], 422);
        }

        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }

        if ($request->has('email') && $user->email != $request->email) {
            $user->verified = User::USUARIO_NO_VERIFICADO;
            $user->verification_token = User::generarVerificationToken();
            $user->email = $request->email;
        }

        if ($request->has('admin')) {
            if (!$user->esVerificado()) {
                return response()->json([
                    'error' => 'Unicamente los usuarios verificados pueden cambiar su valor de administrador',
                    'code' => '409'
                ], 409);
            }

            $user->admin = $request->admin;
        }

        if ($user->isClean()) {
            return response()->json([
                'error' => 'Se debe especificar al menos un valor diferente para actualizar',
                'code' => '422'
            ], 422);
        }

        $user->save();

        return response()->json([
            'data' => $user
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {

        $user->delete();

        return response()->json([
            'data' => $user
        ], 200);

    }
}
