<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function ingresar(Request $request)
    {
        // validar
        $credenciales = $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);
        // verificar
        if(!Auth::attempt($credenciales)){
            return response()->json(["mensaje" => "No Autorizado"], 401);
        }
        //generar token
        $user = Auth::user();
        $tokenResult = $user->createToken('Token Login');
        $token = $tokenResult->plainTextToken;
        // responder  
        return response()->json([
            'access_token' => $token,
            "token_type" => "Bearer",
            "usuario" => $user
        ]);   
    }

    public function registro(Request $request)
    {
        // validar
        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required",
            "c_password" => "required|same:password"
        ]);

        // registrar
        $u = new User;
        $u->name = $request->name;
        $u->email = $request->email;
        $u->password = bcrypt($request->password);
        $u->save();

        // respuesta
        return response()->json(["mensaje" => "Usuario registrado"], 201);
        
    }
    
    public function perfil(Request $request)
    {

        $user = Auth::user();
        return response()->json($user, 200);
        
    }
    
    public function salir(Request $request)
    {

        $request->user()->tokens()->delete();

        return response()->json(["mensaje" => "Log Out"], 200);
        
    }
}
