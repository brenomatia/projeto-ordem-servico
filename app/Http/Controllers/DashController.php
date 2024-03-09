<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DashController extends Controller
{

    public function acessando_request(Request $request)
    {
        // Validação dos dados de entrada
        $request->validate([
            'user_email' => 'required|email',
            'user_password' => 'required',
        ]);

            // Busca o usuário pelo email fornecido
        $user = User::where('email', $request->user_email)->first();

        // Verifica se o usuário existe e se sua permissão é igual a 1
        if ($user && $user->permission == 1) {

            if (Auth::attempt(['email' => $request->user_email, 'password' => $request->user_password])) {
                // Autenticação bem-sucedida, redirecione para a página desejada
                return redirect()->route('dashboard');
            } else {
                // Autenticação falhou, redirecione de volta com uma mensagem de erro
                return back()->withInput()->with('error', 'As credenciais fornecidas estão incorretas.');
            }
        } else {
            // Se o usuário não existir ou sua permissão não for igual a 1, redirecione de volta com uma mensagem de erro
            return back()->withInput()->with('error', 'Você não tem permissão para acessar esta página.');
        }
    }
    
    public function dashboard(){
        $empresas = Company::all();

        return view('admin.dashboard_cadastro_empresas', compact('empresas'));
    }

    public function logout(){

        Auth::logout(); // Realiza o logout do usuário
        // Redireciona o usuário para a página de login (ou para onde preferir)
        return redirect()->route('login');
    }

    public function dashboard_cadastro_empresa(Request $request){

        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'name' => 'required',
            'endereco' => 'required',
            'database_name' => 'required',
            'database_host' => 'required',
            'database_port' => 'required',
            'database_username' => 'required',
        ]);
    
        // Verifica se um arquivo foi enviado
        if ($request->hasFile('file')) {
            // Salva o logo da empresa na pasta "public/logos"
            $logo_empresa = time() . '.' . $request->file('file')->getClientOriginalExtension();
            $path = $request->file('file')->move(public_path('logos'), $logo_empresa);
        } else {
            // Retorna uma resposta de erro se não foi enviado nenhum arquivo
            return back()->with('error', 'Nenhum arquivo de imagem foi enviado.');
        }
    
        // Crie uma nova instância do model Company com os valores preenchidos pelo usuário
        $company = new Company([
            'logo' => $logo_empresa,
            'name' => $request->input('name'),
            'endereco' => $request->input('endereco'),
            'database_name' => $request->input('database_name'),
            'database_host' => $request->input('database_host'),
            'database_port' => $request->input('database_port'),
            'database_username' => $request->input('database_username'),
            'database_password' => $request->input('database_password'),
        ]);
    
        // Salve os dados no banco de dados
        $company->save();
    
        // Redirecione o usuário para a página desejada
        return back()->with('success', 'Empresa cadastrada com sucesso!');
    }
    
    public function dashboard_atualiza_cadastro_empresa(Request $request, $id)
    {

        $request->validate([
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'name' => 'required',
            'endereco' => 'required',
            'database_name' => 'required',
            'database_host' => 'required',
            'database_port' => 'required',
            'database_username' => 'required',
        ]);

        // Busca a instância existente do model Company pelo id
        $company = Company::findOrFail($id);

        // Se um novo logo for enviado, salva o logo da empresa na pasta "public/logos"
        if ($request->hasFile('file')) {
            $logo_empresa = time() . '.' . $request->file->getClientOriginalExtension();
            $path = $request->file->move(public_path('logos'), $logo_empresa);
            $company->logo = basename($path);
        }

        // Preenche os novos dados recebidos do formulário na instância do model Company
        $company->name = $request->input('name');
        $company->endereco = $request->input('endereco');
        $company->database_name = $request->input('database_name');
        $company->database_host = $request->input('database_host');
        $company->database_port = $request->input('database_port');
        $company->database_username = $request->input('database_username');
        $company->database_password = $request->input('database_password');

        // Salva as alterações no banco de dados
        $company->save();

        // Redireciona o usuário para a página desejada
        return back()->with('success', 'Empresa atualizada com sucesso!');
    }
    
    public function delete_cadastro_empresa(Request $request, $id)
    {
        $busca = Company::find($id);
        $busca->delete();

        if($busca)
        {
            return back()->with('success', 'A empresa ' . $busca->name . ' foi deletada com sucesso!');
        }
    }

}
