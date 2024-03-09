<?php

namespace App\Http\Middleware;

use App\Models\Company;
use Closure;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckEmpresaRoute
{
    
    public function handle(Request $request, Closure $next)
    {


        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $request->route('empresa'))->first();

        // Verifica se a empresa foi encontrada.
        if (!$empresa) {
            return redirect('/');
        }

        // Adiciona a empresa ao objeto da requisição para que possa ser acessada pelos controllers.
        $request->empresa = $empresa;

        return $next($request);
    }
}
