<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login()
    {

        return view('login');

    }
    public function cadastro()
    {
        
        return view('cadastro');

    }
    public function cadastro_registrando_user(Request $request){

        // Criação de um novo usuário
        $user = User::create([
            'name' => $request->input('user_name'),
            'email' => $request->input('user_email'),
            'password' => bcrypt($request->input('user_password')),
        ]);
    
        if($user->permission == 1){
            // Autentica o usuário automaticamente
            Auth::login($user);

            return redirect()->route('dashboard');
        }
        
        return back()->with('error', 'Ops algo deu errado.');
    }
    
}
