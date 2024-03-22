<?php

namespace App\Http\Controllers;

use App\Imports\ProdutosImport;
use App\Models\Cliente;
use App\Models\Company;
use App\Models\Carrinho;
use App\Models\Equipamento;
use App\Models\Marca;
use App\Models\EquipamentoOS;
use App\Models\OrdemServico;
use App\Models\MaoDeObra;
use App\Models\Produto;
use App\Models\Revenda;
use App\Models\Terceiro;
use App\Models\User;
use App\Models\Anota;
use App\Models\CarrinhoOrdem;
use App\Models\CarrinhoProdutos;
use App\Models\VendasProdutos;
use App\Models\RelatorioProdutos;
use App\Models\VendasOrdem;
use App\Models\GarantiaCliente;
use App\Models\GarantiaItens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use App\Imports\ClienteImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Mpdf\Mpdf;


use App\Models\TransactionsProdutos;

class EmpresaController extends Controller
{
    public function login_empresa(Request $request, $empresa)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();

        // Cria o banco de dados da empresa
        DB::statement('CREATE DATABASE IF NOT EXISTS ' . $empresa->database_name);


        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);

        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        // Executa a migração para criar as tabelas da empresa
        Artisan::call('migrate', [
            '--database' => 'empresa',
            '--path' => 'database/migrations/empresa',
        ]);

        // Retorna a view com os dados da empresa.
        return view('empresa.login', compact('empresa'));
    }

    public function cadastro_empresa(Request $request, $empresa)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        return view('empresa.cadastro', compact('empresa'));
    }

    public function request_cadastro_empresa(Request $request, $empresa)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        // Criação de um novo usuário
        $user = User::create([
            'name' => $request->input('user_name'),
            'email' => $request->input('user_email'),
            'password' => bcrypt($request->input('user_password')),
        ]);

        if ($user) {
            // Verifica se o usuário tem a permissão igual a 1
            if ($user->permission == 1) {
                // Autentica o usuário automaticamente
                Auth::login($user);
                return redirect()->route('dashboard_empresa', ['empresa' => $empresa->name]);
            }

            return redirect()->route('login_empresa', ['empresa' => $empresa->name])->with('error', 'Você não tem permissão para acessar esta área.');

        }

    }

    public function request_login_empresa(Request $request, $empresa)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        // Validação dos dados de entrada
        $request->validate([
            'user_email' => 'required|email',
            'user_password' => 'required',
        ]);

        // Busca o usuário pelo email fornecido
        $user = User::where('email', $request->user_email)->first();

        // Verifica se o usuário existe e se sua permissão é igual a 1
        if ($user && $user->permission == 1) {
            // Tenta autenticar o usuário
            if (Auth::attempt(['email' => $request->user_email, 'password' => $request->user_password])) {
                // Autenticação bem-sucedida, redirecione para a página desejada
                return redirect()->route('dashboard_empresa', ['empresa' => $empresa->name]);
            } else {
                // Autenticação falhou, redirecione de volta com uma mensagem de erro
                return back()->withInput()->with('error', 'As credenciais fornecidas estão incorretas.');
            }
        } else {
            // Usuário não encontrado ou não possui permissão suficiente
            return back()->withInput()->with('error', 'Você não tem permissão para acessar esta área.');
        }

    }

    public function dashboard_empresa(Request $request, $empresa)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        // Calcular o total de ValorPago para o mês atual
        $sumMensalProdutos = TransactionsProdutos::where('created_at', '>=', Carbon::now()->startOfMonth())
            ->sum('ValorPago');

        $sumMensalOrdem = VendasOrdem::where('created_at', '>=', Carbon::now()->startOfMonth())
            ->sum('ValorPago');

        $sumMensal = $sumMensalProdutos + $sumMensalOrdem;
        //dd($sumMensal);

        $dataInicioSemanaAtual = Carbon::now()->startOfWeek();
        $dataFimSemanaAtual = Carbon::now()->endOfWeek();
        $dataInicioSemanaPassada = $dataInicioSemanaAtual->copy()->subWeek()->startOfWeek();
        $dataFimSemanaPassada = $dataInicioSemanaPassada->copy()->endOfWeek();

        $ClientesAtendiosProdutos = TransactionsProdutos::where('created_at', '>=', Carbon::now()->startOfMonth())->count();
        $ClientesAtendiosOrdem = VendasOrdem::where('created_at', '>=', Carbon::now()->startOfMonth())->count();
        $atendimentoTotal = $ClientesAtendiosProdutos + $ClientesAtendiosOrdem;

        // Função para adicionar vendas ao array e formatá-las
        function adicionarVendas($query, &$vendasArray)
        {
            $vendas = $query->get();
            foreach ($vendas as $venda) {
                $diaSemana = Carbon::parse($venda->created_at)->dayOfWeek;
                // Adiciona o valor ao array
                $vendasArray[$diaSemana] += $venda->valorPago;
            }
        }

        // Inicialize os arrays para armazenar as vendas de cada dia da semana
        $vendasSemanaAtual = [0, 0, 0, 0, 0, 0, 0];
        $vendasSemanaPassada = [0, 0, 0, 0, 0, 0, 0];

        // Adicione as vendas de TransactionsProdutos para a semana atual
        adicionarVendas(
            TransactionsProdutos::whereBetween('created_at', [$dataInicioSemanaAtual, $dataFimSemanaAtual]),
            $vendasSemanaAtual
        );

        // Adicione as vendas de VendasOrdem para a semana atual
        adicionarVendas(
            VendasOrdem::whereBetween('created_at', [$dataInicioSemanaAtual, $dataFimSemanaAtual]),
            $vendasSemanaAtual
        );

        // Adicione as vendas de TransactionsProdutos para a semana passada
        adicionarVendas(
            TransactionsProdutos::whereBetween('created_at', [$dataInicioSemanaPassada, $dataFimSemanaPassada]),
            $vendasSemanaPassada
        );

        // Adicione as vendas de VendasOrdem para a semana passada
        adicionarVendas(
            VendasOrdem::whereBetween('created_at', [$dataInicioSemanaPassada, $dataFimSemanaPassada]),
            $vendasSemanaPassada
        );

        // Obter os dados do modelo
        $vendasOrdem = VendasOrdem::where('created_at', '>=', Carbon::now()->startOfMonth())->get();
        $transactionsProdutos = TransactionsProdutos::where('created_at', '>=', Carbon::now()->startOfMonth())->get();

        // Processar os dados para contar o número de ocorrências de cada tipo de pagamento
        $tipoPagamentoCount = [];
        foreach ($vendasOrdem as $venda) {
            $tipoPagamento = $venda->tipo_pagamento;
            $tipoPagamentoCount[$tipoPagamento] = ($tipoPagamentoCount[$tipoPagamento] ?? 0) + 1;
        }
        foreach ($transactionsProdutos as $transaction) {
            $tipoPagamento = $transaction->tipo_pagamento;
            $tipoPagamentoCount[$tipoPagamento] = ($tipoPagamentoCount[$tipoPagamento] ?? 0) + 1;
        }

        // Cálculo da porcentagem de cada tipo de pagamento em relação ao total
        $totalPagamentos = array_sum($tipoPagamentoCount);
        $porcentagens = [];
        foreach ($tipoPagamentoCount as $tipoPagamento => $count) {
            $porcentagens[$tipoPagamento] = ($count / $totalPagamentos) * 100;
        }


        // Obter os dados do modelo para VendasOrdem agrupados por tipo
        $vendasOrdemValor = VendasOrdem::where('created_at', '>=', Carbon::now()->startOfMonth())
        ->groupBy('tipo')
        ->selectRaw('tipo, SUM(valorPago) as total')
        ->pluck('total', 'tipo');
        $transactionsProdutosValor = TransactionsProdutos::where('created_at', '>=', Carbon::now()->startOfMonth())->sum('valorPago');

        //dd($vendasOrdemValor, $transactionsProdutosValor);

        $sumOrdem = OrdemServico::where('created_at', '>=', Carbon::now()->startOfMonth())->where('status', 'ABERTA')->count();




        $topProdutos = RelatorioProdutos::where('created_at', '>=', Carbon::now()->startOfMonth())->orderBy('qtd_produto', 'desc')->limit(5)->get();

        // Obter o primeiro dia do mês atual
        $primeiroDia = Carbon::now()->startOfMonth();

        // Inicializar arrays para armazenar os totais de vendas para cada dia
        $labels = [];
        $totalVendas = [];

        // Loop pelos dias do mês
        for ($i = 0; $i < $primeiroDia->daysInMonth; $i++) {
            // Obter a data atual
            $dataAtual = $primeiroDia->copy()->addDays($i);

            // Adicionar o dia ao array de labels
            $labels[] = $dataAtual->format('d/m');

            // Obter os valores de vendas para o dia atual de ambas as fontes de dados
            $vendasOrdens = VendasOrdem::whereDate('created_at', $dataAtual)->sum('valorPago');
            $transactionsProdutos = TransactionsProdutos::whereDate('created_at', $dataAtual)->sum('valorPago');

            // Somar os valores de vendas das duas fontes de dados
            $totalVendas[] = $vendasOrdens + $transactionsProdutos;
        }

        return view('empresa.dashboard', compact('topProdutos', 'labels', 'totalVendas', 'empresa', 'sumOrdem', 'sumMensal', 'atendimentoTotal', 'vendasSemanaAtual', 'vendasSemanaPassada', 'porcentagens', 'vendasOrdemValor', 'transactionsProdutosValor', 'transactionsProdutos'));
    }

    /* LOGOUT */
    public function logout_empresa(Request $request, $empresa)
    {
        Auth::logout(); // Realiza o logout do usuário
        // Redireciona o usuário para a página de login (ou para onde preferir)
        return redirect()->route('login_empresa', ['empresa' => $empresa]);
    }


    public function dashboard_cadastro_cliente(Request $request, $empresa)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $clientes = Cliente::paginate(10);
        $ordemServicos = OrdemServico::paginate(10);

        return view('empresa.dashboard_cadastro_cliente', compact('empresa', 'clientes', 'ordemServicos'));
    }

    public function dashboard_ordem_servico(Request $request, $empresa)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $ordemServicos = OrdemServico::paginate(10);
        $equipamentosOS = EquipamentoOS::where('id_user', Auth::user()->id)->where('listado', null)->get();
        $equipamentosListados = EquipamentoOS::where('id_user', Auth::user()->id)->get();



        return view('empresa.dashboard_ordem_servico', compact('empresa', 'ordemServicos', 'equipamentosOS', 'equipamentosListados'));
    }

    public function dashboard_cadastrando_cliente(Request $request, $empresa)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $newCliente = Cliente::create([
            'nome_completo' => $request->cliente_nome_completo,
            'celular' => $request->cliente_celular,
            'endereco' => $request->cliente_endereco,
            'cidade' => $request->cliente_cidade,
            'uf' => $request->cliente_uf,
            'numero' => $request->cliente_numero,
            'cep' => $request->cliente_cep,
        ]);

        if ($newCliente) {
            return redirect()->route('dashboard_ordem_servico', ['empresa' => $empresa->name])->with('success', 'Cliente cadastrado com sucesso!');
        }

    }

    public function dashboard_atualizar_cliente(Request $request, $empresa, $id)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $cliente = Cliente::find($id);
        $cliente->nome_completo = $request->cliente_nome_completo;
        $cliente->celular = $request->cliente_celular;
        $cliente->endereco = $request->cliente_endereco;
        $cliente->numero = $request->cliente_numero;
        $cliente->cep = $request->cliente_cep;
        $cliente->save();

        if ($cliente) {
            return redirect()->route('dashboard_cadastro_cliente', ['empresa' => $empresa->name])->with('success', 'Cliente atualizado com sucesso!');
        }
    }

    public function dashboard_deletando_cliente(Request $request, $empresa, $id)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $deletandoUser = Cliente::find($id);
        $deletandoUser->delete();

        if ($deletandoUser) {
            return redirect()->route('dashboard_cadastro_cliente', ['empresa' => $empresa->name])->with('success', 'Cliente foi deletado com sucesso!');
        }

    }




    public function dashboard_revendas(Request $request, $empresa)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $revendas = Revenda::paginate(10);

        return view('empresa.dashboard_cadastro_revendas', compact('empresa', 'revendas'));
    }
    public function dashboard_produtos(Request $request, $empresa)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $produtos = Produto::paginate(10);

        return view('empresa.dashboard_cadastro_produtos', compact('empresa', 'produtos'));
    }

    public function dashboard_terceiros(Request $request, $empresa)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        return view('empresa.dashboard_terceiros', compact('empresa'));
    }

    public function dashboard_metricas(Request $request, $empresa)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        return view('empresa.dashboard_metricas', compact('empresa'));
    }

    public function dashboard_cadastro_revendas(Request $request, $empresa)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $revendas_ok = Revenda::create([
            'nome_responsavel' => $request->revenda_nome,
            'nome_empresa' => $request->revenda_nome_empresa,
            'cnpj_empresa' => $request->revenda_cnpj,
            'numero' => $request->revenda_numero,
            'desconto' => $request->revenda_desconto,
            'endereco' => $request->revenda_endereco,
            'cep' => $request->revenda_cep,
            'email' => $request->revenda_email,
            'celular' => $request->revenda_celular,
            'obs' => $request->revenda_obs,
        ]);

        if ($revendas_ok) {
            return back()->with('success', 'Revenda cadastrada com sucesso!');
        }

    }

    public function dashboard_cadastro_cliente_pesquisa(Request $request, $empresa)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        // Verifica se foi enviado um termo de pesquisa
        if ($request->has('search')) {
            $searchTerm = $request->input('search');

            // Busca os clientes cujo nome contém o termo de pesquisa ou cujo número de celular contém o termo de pesquisa
            $clientes = Cliente::where(function ($query) use ($searchTerm) {
                $query->where('nome_completo', 'like', "%$searchTerm%")
                    ->orWhere('celular', 'like', "%$searchTerm%");
            })
                ->orderBy('nome_completo', 'asc')
                ->paginate(10);

            // Mantém todos os parâmetros de consulta na paginação
            $clientes->appends($request->query());

            $ordemServicos = OrdemServico::paginate(10);

            return view('empresa.dashboard_cadastro_cliente_pesquisa', compact('empresa', 'clientes', 'ordemServicos'));
        }

    }

    public function dashboard_deletar_revendas(Request $request, $empresa, $id)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $busca = Revenda::find($id);
        $busca->delete();

        if ($busca) {
            return back()->with('success', 'Revenda excluida com sucesso!');
        }
    }

    public function dashboard_atualizar_revendas(Request $request, $empresa, $id)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $busca = Revenda::find($id);
        $busca->nome_responsavel = $request->revenda_nome;
        $busca->nome_empresa = $request->revenda_nome_empresa;
        $busca->cnpj_empresa = $request->revenda_cnpj;
        $busca->numero = $request->revenda_numero;
        $busca->desconto = $request->revenda_desconto;
        $busca->endereco = $request->revenda_endereco;
        $busca->cep = $request->revenda_cep;
        $busca->email = $request->revenda_email;
        $busca->celular = $request->revenda_celular;
        $busca->obs = $request->revenda_obs;
        $busca->save();

        if ($busca) {
            return back()->with('success', 'Revenda atualizada com sucesso!');
        }

    }

    public function dashboard_import_csv(Request $request, $empresa)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $empresa = $request->route('empresa');

        if ($request->hasFile('arquivo_csv')) {
            $file = $request->file('arquivo_csv');

            try {
                // Importe o arquivo usando a classe de importação personalizada
                Excel::import(new ClienteImport, $file);

                return redirect()->back()->with('success', 'Arquivo importado com sucesso!');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Ocorreu um erro ao importar o arquivo: ' . $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', 'Nenhum arquivo foi enviado.');
        }
    }

    public function dashboard_produtos_cadastro(Request $request, $empresa)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $consulta = Produto::where('sku', $request->produto_sku)->first();

        if($consulta){
            return back()->with('error', 'Produto ja cadastrado!');
        }

        $produto = new Produto();
        $produto->sku = $request->produto_sku;
        $produto->descricao = $request->produto_descricao;
        $produto->ncm = $request->produto_ncm;
        $produto->cst = $request->produto_cst;
        $produto->letra = $request->produto_letra;
        $produto->pis = $request->produto_pis;
        $produto->confins = $request->produto_confins;
        $produto->pvenda = $request->produto_preco_venda;
        $produto->unidade = $request->produto_unidade;
        $produto->save();

        if ($produto) {
            return back()->with('success', 'Produto cadastrado com sucesso!');
        }
    }

    public function dashboard_produtos_atualizar(Request $request, $empresa, $id)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $produtoBusca = Produto::find($id);
        $produtoBusca->sku = $request->produto_sku;
        $produtoBusca->descricao = $request->produto_descricao;
        $produtoBusca->ncm = $request->produto_ncm;
        $produtoBusca->cst = $request->produto_cst;
        $produtoBusca->letra = $request->produto_letra;
        $produtoBusca->pis = $request->produto_pis;
        $produtoBusca->confins = $request->produto_confins;
        $produtoBusca->pvenda = $request->produto_preco_venda;
        $produtoBusca->unidade = $request->produto_unidade;
        $produtoBusca->save();

        if ($produtoBusca) {
            return back()->with('success', 'Produto atualizado com sucesso!');
        }
    }

    public function dashboard_produtos_pesquisa(Request $request, $empresa)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        // Verifica se foi enviado um termo de pesquisa
        if ($request->has('search')) {
            $searchTerm = $request->input('search');

            // Busca os produtos cuja descrição contém o termo de pesquisa OU cujo SKU contém o termo de pesquisa
            $produtos = Produto::where(function ($query) use ($searchTerm) {
                $query->where('descricao', 'like', "%$searchTerm%")
                    ->orWhere('sku', 'like', "%$searchTerm%");
            })
                ->orderBy('descricao', 'asc')
                ->paginate(10);


            // Mantém todos os parâmetros de consulta na paginação
            $produtos->appends($request->query());

            return view('empresa.dashboard_cadastro_produtos_pesquisa', compact('empresa', 'produtos'));
        }
    }

    public function dashboard_produto_import_csv(Request $request, $empresa)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $empresa = $request->route('empresa');

        if ($request->hasFile('arquivo_csv')) {
            $file = $request->file('arquivo_csv');

            try {
                // Importe o arquivo usando a classe de importação personalizada
                Excel::import(new ProdutosImport, $file);

                return redirect()->back()->with('success', 'Arquivo importado com sucesso!');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Ocorreu um erro ao importar o arquivo: ' . $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', 'Nenhum arquivo foi enviado.');
        }
    }
    public function dashboard_ordem_buscar_cliente(Request $request, $empresa)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $searchTerm = $request->get('query');
        $clientes = Cliente::where('nome_completo', 'like', "%$searchTerm%")
            ->orWhere('celular', 'like', "%$searchTerm%")
            ->orderBy('nome_completo', 'asc')
            ->get();

        $output = '<option value="">Selecione o cliente</option>';
        foreach ($clientes as $cliente) {
            $output .= '<option value="' . $cliente->id . '">' . $cliente->nome_completo . '</option>';
        }
        echo $output;

    }

    public function dashboard_cadastrando_ordem(Request $request, $empresa)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        if ($request->cliente_id == null) {
            return back()->with('error', 'Preencha todos os dados para listar a ordem de serviço!');
        }

        $cliente = Cliente::find($request->cliente_id);
        $newOrdemServico = new OrdemServico();
        $newOrdemServico->nome_cliente = $cliente->nome_completo;
        $newOrdemServico->tipo = $request->tipo_filter;
        $newOrdemServico->id_cliente = $request->cliente_id;
        $newOrdemServico->abertura_da_ordem = Auth::user()->name;
        $newOrdemServico->status = "ABERTA";
        $newOrdemServico->save();

        if ($request->has('id_equipamento')) {
            foreach ($request->id_equipamento as $idEquipamento) {
                EquipamentoOS::where('id', $idEquipamento)->update([
                    'status' => 'AGUARDANDO ORÇAMENTO',
                    'os_permitida' => $newOrdemServico->id,
                    'listado' => 'SIM',
                ]);
            }
        }


        if ($newOrdemServico) {
            return back()->with('success', 'Ordem de serviço aberta com sucesso!');
        }
    }


    public function dashboard_deletar_ordem(Request $request, $empresa, $id)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $buscaOrdemServico = OrdemServico::find($id);
        $buscaOrdemServico->delete();

        if ($buscaOrdemServico) {
            return back()->with('success', 'A ordem de Nº ' . $buscaOrdemServico->id . ' foi excuida com sucesso!');
        }
    }

    public function dashboard_listar_items_ordem(Request $request, $empresa, $id_ordem, $id_equipamento)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $equipamento = EquipamentoOS::find($id_equipamento);

     
        $terceiros = Terceiro::where('id_equipamento_permitido', $id_equipamento)->get();
        $carrinhos = Carrinho::where('id_equipamento_permitido', $id_equipamento)->get();
        $maodeobras = MaoDeObra::where('id_equipamento_permitido', $id_equipamento)->get();

        // Verificar se já existe uma anotação relacionada a $id_ordem
        $anotacaoExistente = Anota::where('os_permitida', $id_equipamento)->first();

        // Inicializar uma variável para armazenar o conteúdo da anotação
        $anotacaoConteudo = null;

        // Se existe uma anotação relacionada à $id_ordem, obter o conteúdo
        if ($anotacaoExistente) {
            $anotacaoConteudo = $anotacaoExistente->anota;
        }

        $sumListagem = Carrinho::where('id_equipamento_permitido', $id_equipamento)->sum('valor');
        $sumTerceiro = Terceiro::where('id_equipamento_permitido', $id_equipamento)->sum('valor');
        $sumMao = MaoDeObra::where('id_equipamento_permitido', $id_equipamento)->sum('valor');
        $sumTotal = ($sumListagem + $sumTerceiro) + $sumMao;

        $OrdemServico = OrdemServico::find($id_ordem);
        

        

        return view('empresa.dashboard_os_listagem', compact('equipamento', 'OrdemServico', 'empresa', 'id_ordem', 'id_equipamento', 'terceiros', 'carrinhos', 'maodeobras', 'anotacaoConteudo', 'sumListagem', 'sumTerceiro', 'sumMao', 'sumTotal'));
    }

    public function dashboard_ordem_atualizar_dados(Request $request, $empresa, $id_ordem)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $OrdemServico = OrdemServico::find($id_ordem);
        $cliente = Cliente::find($request->cliente_id_atualizar);

        $OrdemServico->nome_cliente = $cliente->nome_completo;
        $OrdemServico->id_cliente = $request->cliente_id_atualizar;
        $OrdemServico->save();

        if ($OrdemServico) {
            return back()->with('success', 'Cliente atualizado com sucesso!');
        }
    }

    public function dashboard_cadastro_terceiros(Request $request, $empresa, $id_ordem)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $newTerceiro = new Terceiro();
        $newTerceiro->nome_empresa = $request->nome_empresa;
        $newTerceiro->tipo_servico = $request->tipo_servico;
        $newTerceiro->valor = $request->valor;
        $newTerceiro->obs = $request->obs;
        $newTerceiro->id_os = $id_ordem;
        $newTerceiro->id_equipamento_permitido = $request->id_equipamento;
        $newTerceiro->save();

        if ($newTerceiro) {
            return back()->with('success', 'Terceiros registrado com sucesso!');
        }

    }

    public function dashboard_atualizar_terceiros(Request $request, $empresa, $id_ordem, $id_terceiro)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $newTerceiro = Terceiro::find($id_terceiro);
        $newTerceiro->nome_empresa = $request->nome_empresa;
        $newTerceiro->tipo_servico = $request->tipo_servico;
        $valor = $request->valor;
        $valor = str_replace(',', '.', $valor);
        $newTerceiro->valor = $valor;
        $newTerceiro->obs = $request->obs;
        $newTerceiro->save();

        if ($newTerceiro) {
            return back()->with('success', 'Terceiro atualizado com sucesso!');
        }
    }

    public function dashboard_deletar_terceiros(Request $request, $empresa, $id_ordem, $id_terceiro)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $terceiro = Terceiro::find($id_terceiro);
        $terceiro->delete();

        if ($terceiro) {
            return back()->with('success', 'Terceiro deletado com sucesso!');
        }
    }

    public function dashboard_ordem_atualiza_status(Request $request, $empresa, $id_ordem, $id)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }


        // Encontrar o equipamento pelo ID
        $equipamento = EquipamentoOS::find($id);

        if ($equipamento) {

            // Atualizar o status da ordem de serviço com base no valor recebido da requisição
            $equipamento->status = $request->status;
            $equipamento->q_aut = Auth::user()->name;
            // Salvar as alterações no equipamento
            $equipamento->save();

            // Responder com uma mensagem de sucesso
            return response()->json(['message' => 'Status atualizado com sucesso'], 200);

        } else {

            // Se o equipamento não for encontrado, responder com uma mensagem de erro
            return response()->json(['message' => 'Equipamento não encontrado'], 404);

        }

    }
    public function dashboard_ordem_atualizar_status_select(Request $request, $empresa, $id_ordem, $id)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }


        // Encontrar o equipamento pelo ID
        $equipamento = EquipamentoOS::find($id);

        if ($equipamento) {
            // Atualizar o status da ordem de serviço com base no valor recebido da requisição
            $equipamento->status = $request->status;
            $equipamento->q_aut = Auth::user()->name;

            if ($request->status == "NÃO AUTORIZADO") {
                // Excluir registros em outras tabelas relacionadas ao equipamento
                Carrinho::where('id_equipamento_permitido', $id)->delete();
                Terceiro::where('id_equipamento_permitido', $id)->delete();
                MaoDeObra::where('id_equipamento_permitido', $id)->delete();
            }

            // Salvar as alterações no equipamento
            $equipamento->save();

            // Responder com uma mensagem de sucesso
            return response()->json(['message' => 'Status atualizado com sucesso'], 200);
        } else {
            // Se o equipamento não for encontrado, responder com uma mensagem de erro
            return response()->json(['message' => 'Equipamento não encontrado'], 404);
        }

    }

    public function dashboard_ordem_buscar_produto(Request $request, $empresa, $id_ordem)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $searchTerm = $request->get('query');
        $produtos = Produto::where('descricao', 'like', "%$searchTerm%")
            ->orWhere('sku', 'like', "%$searchTerm%")
            ->orderBy('descricao', 'asc')
            ->get();

        $output = '<option value="">Selecione seu produto</option>';
        foreach ($produtos as $produto) {
            $output .= '<option value="' . $produto->id . '">' . $produto->descricao . ' - SKU: ' . $produto->sku . ' - [ R$ ' . $produto->pvenda . ' ] </option>';
        }
        echo $output;
    }

    public function dashboard_os_listar_item(Request $request, $empresa, $id_ordem)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        //dd($request->all());

        $produto = Produto::find($request->produto_id);
        $sku = $produto->sku;

        $carrinhoExistente = Carrinho::where('sku', $sku)->where('produto', $produto->descricao)->where('id_os', $id_ordem)->first();

        if ($carrinhoExistente) {
            // O item já existe no carrinho, então atualize a quantidade e o valor total
            $carrinhoExistente->qtd_produto += 1; // Incrementa a quantidade
            $carrinhoExistente->valor += $produto->pvenda; // Atualiza o valor total
            $carrinhoExistente->save();
        } else {
            // O item não existe no carrinho, então crie um novo
            $newCarrinho = new Carrinho();
            $newCarrinho->sku = $sku;
            $newCarrinho->produto = $produto->descricao;
            $newCarrinho->qtd_produto = 1;
            $newCarrinho->id_os = $id_ordem;
            $newCarrinho->id_equipamento_permitido = $request->id_equipamento;
            $newCarrinho->valor = $produto->pvenda;
            $newCarrinho->save();
        }

        return back()->with('success', 'Item listado com sucesso!');


    }


    public function dashboard_os_sub_item(Request $request, $empresa, $id_ordem)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        // Tenta encontrar um produto com o mesmo nome no banco de dados
        $product = Carrinho::where('produto', $request->name_produto)->where('id_os', $id_ordem)->where('id_equipamento_permitido', $request->id_equipamento)->first();

        if ($product) {
            // Se o produto já existir, atualize a quantidade e o valor
            $product->qtd_produto += 1;
            $product->valor += str_replace(',', '.', $request->valor_produto);
            $product->save();
        } else {
            // Se o produto não existir, crie um novo registro
            $newProduct = new Carrinho();
            $newProduct->sku = '---';
            $newProduct->produto = $request->name_produto;
            $newProduct->valor = str_replace(',', '.', $request->valor_produto);
            $newProduct->id_os = $id_ordem;
            $newProduct->id_equipamento_permitido = $request->id_equipamento;
            $newProduct->qtd_produto = 1;
            $newProduct->save();
        }


        return back()->with('success', 'Produto listado com sucesso!');

    }







    public function dashboard_os_deletar_item(Request $request, $empresa, $id_ordem, $id)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $busca = Carrinho::find($id);
        $busca->delete();

        if ($busca) {
            return back()->with('success', 'Item excluido do carrinho com sucesso!');
        }

    }

    public function dashboard_cadastro_mao_de_obra(Request $request, $empresa, $id_ordem)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $new = new MaoDeObra();
        $new->tipo = $request->mao_obra;
        $new->valor = $request->valor;
        $new->obs = $request->obs;
        $new->id_os = $id_ordem;
        $new->id_equipamento_permitido = $request->id_equipamento;
        $new->save();

        if ($new) {
            return back()->with('success', 'Mão de obra registrada com sucesso!');
        }

    }
    public function dashboard_deletar_mao_de_obra(Request $request, $empresa, $id_ordem, $id)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $busca = MaoDeObra::find($id);
        $busca->delete();

        if ($busca) {
            return back()->with('success', 'Mão de obra excluida com sucesso!');
        }
    }

    public function dashboard_anotacao_os(Request $request, $empresa, $id_ordem)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        // Verificar se já existe uma anotação relacionada a $id_ordem
        $anotacaoExistente = Anota::where('os_permitida', $request->id_equipamento)->first();

        if ($anotacaoExistente) {
            // Se já existir, atualize o campo 'anota'
            $anotacaoExistente->anota = $request->anotacoes;
            $anotacaoExistente->save();
        } else {
            // Se não existir, crie uma nova anotação
            $newAnota = new Anota();
            $newAnota->anota = $request->anotacoes;
            $newAnota->os_permitida = $request->id_equipamento;
            $newAnota->save();
        }

        return back()->with('success', 'Anotações salvas com sucesso!');
    }
    public function dashboard_ordem_cadastro_equipamento(Request $request, $empresa)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $new = new EquipamentoOS();
        $new->equipamento = $request->equipamento;
        $new->id_user = Auth::user()->id;
        $new->save();

        if ($new) {
            return back()->with('success', 'Item foi listado com sucesso!');
        }

    }

    public function dashboard_ordem_deletar_registro(Request $request, $empresa, $id_ordem)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        if(Auth::user()->nivel == '0'){
            $busca1 = OrdemServico::find($id_ordem);
            $busca1->delete();
    
            $busca2 = EquipamentoOS::where('os_permitida', $id_ordem)->delete();
    
            if ($busca1 || $busca2) {
                return back()->with('success', 'Ordem de servico excluido com sucesso!');
            }
        }

        return back()->with('error', 'Você não tem permissão para deletar essa ordem de serviço!');


    }
    public function setOpenCard(Request $request, $empresa, $cardId, $id_ordem)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        // Verificar se o card clicado é o mesmo que o atualmente aberto
        if (Session::has('openCardId') && Session::get('openCardId') == $cardId) {
            // Se for o mesmo, fechar o card
            Session::forget('openCardId');
        } else {
            // Se não for o mesmo, abrir o card clicado
            Session::put('openCardId', $cardId);
        }


        //dd($totalCarrinhos, $totalTerceiros, $totalmaoDeObras, $total, $empresa);

        return redirect()->back();
    }

    public function dashboard_ordem_atualizar_equipamento(Request $request, $empresa, $id_ordem, $id)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $busca = EquipamentoOS::find($id);
        $busca->equipamento = $request->equipamento;
        $busca->save();

        if ($busca) {
            return back()->with('success', 'Equipamento atualizado com sucesso!');
        }
    }

    public function dashboard_buscar_ordem(Request $request, $empresa)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        // Obtém o número identificador da Ordem de Serviço do formulário
        $numeroIdentificador = $request->input('search_os');

        // Busca a Ordem de Serviço com base no número identificador
        $ordemServicos = OrdemServico::where('id', $numeroIdentificador)->first();

        if ($ordemServicos) {

            $equipamentosOS = EquipamentoOS::where('id_user', Auth::user()->id)->where('listado', null)->get();
            $equipamentosListados = EquipamentoOS::where('id_user', Auth::user()->id)->where('os_permitida', $ordemServicos->id)->get();
            return view('empresa.dashboard_ordem_servico_pesquisa', compact('empresa', 'ordemServicos', 'equipamentosOS', 'equipamentosListados'));

        } else {
            return back()->with('error', 'Ordem de serviço não localizada!');
        }

    }

    public function dashboard_ordem_atualizar_garantia_equipamento(Request $request, $empresa, $id_ordem, $id)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        //dd($request->all());

        $att = EquipamentoOS::find($id);
        $att->data_compra_garantia = $request->data;
        $att->vendido_por_garantia = $request->vendedor;
        $att->defeito_garantia = $request->defeito;
        $att->nfe_garantia = $request->nfe;
        $att->uso_profissional_garantia = $request->opcao;
        $att->save();

        if ($att) {
            return back()->with('success', 'Dados atualizados com sucesso!');
        }
    }

    public function dashboard_ordem_atualizar_dados_status(Request $request, $empresa, $id_ordem, $id)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $new = EquipamentoOS::find($id);

        if ($request->valor_equipamento) {

            $new->valor_autorizado = str_replace(',', '.', $request->valor_equipamento);

            if ($request->valor_final_autorizado) {
                $new->valor_final_autorizado = str_replace(',', '.', $request->valor_final_autorizado);
                $new->valor_pago_autorizado = str_replace(',', '.', $request->valor_pago_autorizado);
                $new->tipo_pagamento_autorizado = $request->forma_pagamento;
                $new->save();
            }
            $new->save();
        }

        $new->aguardando_pcs_data = $request->aguardando_pcs_data;
        $new->aguardando_pcs_obs = $request->aguardando_pcs_obs;
        $new->save();

        if ($new) {
            return back()->with('success', 'Dados atualizados com sucesso!');
        }
    }

    public function dashboard_vendas(Request $request, $empresa)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }


        $vendasOrdem = CarrinhoOrdem::where('id_user', Auth::user()->id)->get();
        $sumTotalOrdem = CarrinhoOrdem::where('id_user', Auth::user()->id)->sum('valorPago');

        $vendasProdutos = CarrinhoProdutos::where('id_user', Auth::user()->id)->get();
        $sumTotalProdutos = CarrinhoProdutos::where('id_user', Auth::user()->id)->sum('total');

        $revendas = Revenda::all();

        $vendas = VendasProdutos::where('id_user', Auth::user()->id)
        ->latest()  // Ordena os registros pela coluna 'created_at' em ordem decrescente
        ->limit(10) // Limita o resultado a 10 registros
        ->get();    // Obtém os registros

        return view('empresa.dashboard_empresa_vendas', compact('empresa', 'vendasOrdem', 'sumTotalOrdem', 'vendasProdutos', 'sumTotalProdutos', 'revendas', 'vendas'));
    }

    public function dashboard_vendas_busca_os(Request $request, $empresa)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $consulta_ordem = OrdemServico::find($request->id_ordem);

        if ($consulta_ordem) {
            $equipamentosOS = EquipamentoOS::where('id_user', Auth::user()->id)
                                            ->where('os_permitida', $consulta_ordem->id)
                                            ->get();
        
            if ($equipamentosOS->isNotEmpty()) {
                foreach ($equipamentosOS as $item) {
                    // Crie uma nova instância de CarrinhoOrdem para cada item
                    $new = new CarrinhoOrdem();
                    
                    // Preencha os dados relevantes do CarrinhoOrdem para este item
                    $new->id_user = Auth::user()->id;
                    $new->cod_os = $consulta_ordem->id; // ou algo assim, dependendo do que `id` representa
                    $new->id_equipamento = $item->id; // ou outra maneira de obter o nome do cliente
                    $new->nome_cliente = $item->ordemServico->nome_cliente; // ou outra maneira de obter o nome do cliente
                    $new->tipo_pagamento = $item->MeioPagamento; // ou outra maneira de obter o tipo de pagamento
                    $new->valorTotal = $item->valorTotal; // ou outra maneira de obter o valor total
                    $new->desconto = $item->desconto; // ou outra maneira de obter o desconto
                    $new->valorComDesconto = $item->valorComDesconto; // ou outra maneira de obter o valor com desconto
                    $new->valorPago = $item->valorPago; // ou outra maneira de obter o valor pago
                    $new->valorTroco = $item->valorTroco; // ou outra maneira de obter o valor do troco
                    $new->qtdParcelas = $item->parcelaTotal; // ou outra maneira de obter a quantidade total de parcelas
                    $new->valorParcelas = $item->valorParcelas; // ou outra maneira de obter o valor das parcelas
                    // Adicione os demais campos conforme necessário
                    // $new->campo = $item->valor;
                    
                    // Salve o CarrinhoOrdem para este item
                    $new->save();
                }
            
                return back()->with('success', 'Ordens de serviço listadas com sucesso!');
            }
            else 
            {
                return back()->with('error', 'Nenhum equipamento encontrado para esta ordem de serviço.');
            }
        } 
        else 
        {
            return back()->with('error', 'Nº de ordem não localizado!');
        }
        

    }


    public function dashboard_vendas_deletar_os(Request $request, $empresa, $id)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $delete = CarrinhoOrdem::find($id);
        $delete->delete();

        if ($delete) {
            return back()->with('success', 'Ordem de serviço excluida com sucesso!');
        }

    }
    public function dashboard_vendas_deletar_produto(Request $request, $empresa, $id)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $delete = CarrinhoProdutos::find($id);
        $delete->delete();

        if ($delete) {
            return back()->with('success', 'Produto excluido com sucesso!');
        }
    }

    public function dashboard_vendas_request_modal_ordem_servico(Request $request, $empresa)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $valor_pago = $request->desconto_aplicadoOrdem;
        $desconto = $request->descontoOrdem;
        $tipo_pagamento = $request->tipo_pagamento;
        $parcelas = $request->parcelas;

        return back()->with([
            'tipo_pagamento' => $tipo_pagamento,
            'parcelas' => $parcelas,
            'desconto' => $desconto,
            'valor_pago' => $valor_pago,
            'submit_formulario' => true, // variável para submeter o formulário na view
        ]);

    }

    public function dashboard_vendas_busca_produto(Request $request, $empresa)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $consulta_produto = Produto::where('sku', $request->cod_produto)->first();

        if ($consulta_produto) {

            $consulta_produto_carrinho_produtos = CarrinhoProdutos::where('cod_produto', $consulta_produto->sku)->first();

            if ($consulta_produto_carrinho_produtos) {
                $consulta_produto_carrinho_produtos->qtd += $request->qtd ?? 1;
                $consulta_produto_carrinho_produtos->total += $consulta_produto->pvenda * $request->qtd ?? 1;
                $consulta_produto_carrinho_produtos->save();

                return back();
            } else {

                $newCarrinho = new CarrinhoProdutos();
                $newCarrinho->id_user = Auth::user()->id;
                $newCarrinho->cod_produto = $consulta_produto->sku;
                $newCarrinho->nome_produto = $consulta_produto->descricao;
                $newCarrinho->qtd = $request->qtd ?? 1;
                $newCarrinho->total = $consulta_produto->pvenda * $request->qtd ?? 1;
                $newCarrinho->save();

                return back()->with('success', 'Produto adicionado com sucesso!');
            }


        } else {
            return back()->with('error', 'Este produto não existe.');
        }
    }


    public function dashboard_vendas_request_modal_produtos(Request $request, $empresa)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $valor_pago = $request->desconto_aplicadoProdutos;

        if($request->tipo_pagamentoProdutos == "REVENDA"){
            $tipo_pagamento = $request->meio_pagamentoProdutos;
            $desconto = "REVENDA";
        }else{
            $tipo_pagamento = $request->tipo_pagamentoProdutos;
            $desconto = $request->descontoProdutos;
        }

        $parcelas = $request->parcelasProdutos;
        $id_revenda = $request->id_revendaProdutos;

        $revenda = Revenda::find($id_revenda);

        return back()->with([
            'tipo_pagamentoProdutos' => $tipo_pagamento,
            'parcelasProdutos' => $parcelas,
            'id_revendaProdutos' => $id_revenda,
            'cliente_nomeProdutos' => $revenda ? $revenda->nome_empresa : NULL,
            'descontoProdutos' => $desconto,
            'valor_pagoProdutos' => $valor_pago,
            'ativa_form' => true, // variável para submeter o formulário na view
        ]);

    }


    public function dashboard_vendas_request_produtos_concluidas(Request $request, $empresa)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        //dd($request->all());

        // Obtendo os dados do request
        $cod_produtos = $request->input('cod_produto');
        $qtd_produtos = $request->input('qtd_produto');
        $valor_total_produtos = $request->input('valor_total');
        $cliente_nome = $request->input('cliente_nome');
        $desconto = $request->input('desconto');
        $valor_pago = $request->input('valor_pago');
        $tipo_pagamento = $request->input('tipo_pagamento');
        $parcelas = $request->input('parcelas');
        $id_revenda = $request->input('id_revenda');
        $hash_transaction = md5(uniqid());

        // Iterando pelos arrays e criando uma nova instância de VendasProdutos para cada item
        for ($i = 0; $i < count($cod_produtos); $i++) {

            $vendaProduto = new VendasProdutos();
            $vendaProduto->hash_transaction = $hash_transaction;
            $vendaProduto->id_user = auth()->user()->id; // Defina o ID do usuário conforme necessário
            $vendaProduto->cod_produto = $cod_produtos[$i];
            $vendaProduto->qtd_produto = $qtd_produtos[$i];
            $vendaProduto->nome_cliente = $cliente_nome;
            $vendaProduto->valorTotal = $valor_total_produtos[$i];
            $vendaProduto->id_revenda = $id_revenda;
            $vendaProduto->save();


            // Verificar se já existe um registro com o mesmo cod_produto
            $existing = RelatorioProdutos::where('cod_produto', $cod_produtos[$i])->first();

            if ($existing) {
                // Se já existir, atualizar a quantidade e o valor total
                $existing->qtd_produto += $qtd_produtos[$i];
                $existing->valorTotal += $valor_total_produtos[$i];
                $existing->nome_cliente = Auth::user()->name;
                $existing->save();
            } else {
                // Se não existir, criar um novo registro
                $vendaProduto = new RelatorioProdutos();
                $vendaProduto->hash_transaction = $hash_transaction;
                $vendaProduto->id_user = auth()->user()->id;
                $vendaProduto->cod_produto = $cod_produtos[$i];
                $vendaProduto->qtd_produto = $qtd_produtos[$i];
                $vendaProduto->nome_cliente = Auth::user()->name;
                $vendaProduto->valorTotal = $valor_total_produtos[$i];
                $vendaProduto->id_revenda = $id_revenda;
                $vendaProduto->save();
            }


            // Verificar se já existe um registro com o mesmo hash_transaction
            $exists = TransactionsProdutos::where('hash_transaction', $hash_transaction)->exists();

            // Se não existir, criar um novo registro
            if (!$exists) {
                $new = new TransactionsProdutos();
                $new->hash_transaction = $hash_transaction;
                $new->id_user = auth()->user()->id;
                $new->desconto_porcentagem = $desconto;
                $new->valorTotal = array_sum($valor_total_produtos);
                $new->valorPago = $valor_pago;
                $new->parcelas = $parcelas;
                $new->tipo_pagamento = $tipo_pagamento;
                $new->save();

            }

        }


        CarrinhoProdutos::where('id_user', Auth::user()->id)->delete();

        return back()->with('success', 'Vendas processada com sucesso!');

    }

    public function dashboard_vendas_request_ordem_concluidas(Request $request, $empresa)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $cod_os = $request->input('cod_os');
        $cliente_os = $request->input('cliente_os');
        $desconto = $request->input('desconto');
        $valor_total = $request->input('valor_total');
        $valor_pago = $request->input('valor_pago');
        $parcelas = $request->input('parcelas');
        $tipo_pagamento = $request->input('tipo_pagamento');

        $consultaOS = OrdemServico::find($cod_os);

        $newOrdem = new VendasOrdem();
        $newOrdem->id_user = Auth::user()->id;
        $newOrdem->cod_os = $cod_os;
        $newOrdem->nome_cliente = $cliente_os;
        $newOrdem->desconto = $desconto;
        $newOrdem->valorTotal = $valor_total;
        $newOrdem->valorPago = $valor_pago;
        $newOrdem->parcelas = $parcelas;
        $newOrdem->tipo_pagamento = $tipo_pagamento;
        $newOrdem->tipo = $consultaOS->tipo;
        $newOrdem->save();

        CarrinhoOrdem::where('id_user', Auth::user()->id)->delete();
        OrdemServico::where('id', $cod_os)->update(['status' => 'FECHADA']);

        return back()->with('success', 'Ordem processada com sucesso!');
    }

    public function dashboard_empresa_pesquisa_personalizada(Request $request, $empresa)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        // Obtenha as datas de início e fim do formulário
        $start_date = Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->startOfDay();
        $end_date = Carbon::createFromFormat('Y-m-d', $request->input('end_date'))->endOfDay();
        
        // Obter os dados do modelo para VendasOrdem agrupados por tipo
        $vendasOrdemValor = VendasOrdem::where('created_at', '>=', $start_date)
            ->where('created_at', '<=', $end_date)
            ->groupBy('tipo')
            ->selectRaw('tipo, SUM(valorPago) as total')
            ->pluck('total', 'tipo');

        $transactionsProdutosValor = TransactionsProdutos::where('created_at', '>=', $start_date)
            ->where('created_at', '<=', $end_date)
            ->sum('valorPago');

        // Obter os dados do modelo dentro do intervalo de datas fornecido
        $vendasOrdemsGraficodonut1 = VendasOrdem::whereDate('created_at', '>=', $start_date)
        ->whereDate('created_at', '<=', $end_date)
        ->get();

        $transactionsProdutosGraficodonut1 = TransactionsProdutos::whereDate('created_at', '>=', $start_date)
        ->whereDate('created_at', '<=', $end_date)
        ->get();

    
        $topProdutos = RelatorioProdutos::whereBetween('created_at',  [$start_date, $end_date])
        ->orderBy('qtd_produto', 'desc')
        ->limit(5)
        ->get();
    
        $sumOrdem = OrdemServico::whereBetween('created_at', [$start_date, $end_date])
        ->where('status', 'ABERTA')
        ->count();

        // Calcular o total de ValorPago para o período personalizado
        $sumMensalProdutos = TransactionsProdutos::whereBetween('created_at', [$start_date, $end_date])->sum('ValorPago');
        $sumMensalOrdem = VendasOrdem::whereBetween('created_at', [$start_date, $end_date])->sum('ValorPago');
        $sumMensal = $sumMensalProdutos + $sumMensalOrdem;

        // Calcular o número total de clientes atendidos no período personalizado
        $ClientesAtendiosProdutos = TransactionsProdutos::whereBetween('created_at', [$start_date, $end_date])->count();
        $ClientesAtendiosOrdem = VendasOrdem::whereBetween('created_at', [$start_date, $end_date])->count();
        $atendimentoTotal = $ClientesAtendiosProdutos + $ClientesAtendiosOrdem;



        /*
            PERSONALIZADO TOTAL DE VENDAS
        */

        // Defina a data inicial e final do mês anterior
        $mes_anterior_start = $start_date->copy()->subMonth()->startOfMonth();
        $mes_anterior_end = $start_date->copy()->subMonth()->endOfMonth();

        // Inicialize os arrays para armazenar os totais de vendas para cada dia do mês atual e do mês anterior
        $labelsVendasPersonalizadas = [];
        $totalVendasPersonalizadas = [];
        $totalVendasPersonalizadasMesAnterior = [];
        $totalPorcentagemComparativo = [];

        // Loop pelos dias entre $start_date e $end_date
        for ($dataAtual = $start_date; $dataAtual->lte($end_date); $dataAtual->addDay()) {
            // Adicionar o dia ao array de labels
            $labelsVendasPersonalizadas[] = $dataAtual->format('d/m');

            // Obter os valores de vendas para o dia atual de ambas as fontes de dados
            $vendasOrdens = VendasOrdem::whereDate('created_at', $dataAtual)->sum('valorPago');
            $transactionsProdutos = TransactionsProdutos::whereDate('created_at', $dataAtual)->sum('valorPago');

            // Obter os valores de vendas para o dia correspondente do mês anterior
            $vendasOrdensMesAnterior = VendasOrdem::whereDate('created_at', $dataAtual->copy()->subMonth())->sum('valorPago');
            $transactionsProdutosMesAnterior = TransactionsProdutos::whereDate('created_at', $dataAtual->copy()->subMonth())->sum('valorPago');

            // Somar os valores de vendas das duas fontes de dados
            $totalVendasPersonalizadas[] = $vendasOrdens + $transactionsProdutos;
            $totalVendasPersonalizadasMesAnterior[] = $vendasOrdensMesAnterior + $transactionsProdutosMesAnterior;

        // Calcular a porcentagem de variação para o dia atual
        if ($vendasOrdensMesAnterior + $transactionsProdutosMesAnterior != 0) {
            $porcentagem = (($vendasOrdens + $transactionsProdutos) - ($vendasOrdensMesAnterior + $transactionsProdutosMesAnterior)) / abs($vendasOrdensMesAnterior + $transactionsProdutosMesAnterior) * 100;
        } else {
            $porcentagem = 0;
        }

            // Adicionar a porcentagem ao array
            $totalPorcentagemComparativo[] = $porcentagem;
        }



        /*
            DONUT PORCETAGEM DE TIPO DE PAGAMENTO
        */

        // Processar os dados para contar o número de ocorrências de cada tipo de pagamento
        $tipoPagamentoCount = [];
        foreach ($vendasOrdemsGraficodonut1 as $venda) {
            $tipoPagamento = $venda->tipo_pagamento;
            $tipoPagamentoCount[$tipoPagamento] = ($tipoPagamentoCount[$tipoPagamento] ?? 0) + 1;
        }
        foreach ($transactionsProdutosGraficodonut1 as $transaction) {
            $tipoPagamento = $transaction->tipo_pagamento;
            $tipoPagamentoCount[$tipoPagamento] = ($tipoPagamentoCount[$tipoPagamento] ?? 0) + 1;
        }

        // Cálculo da porcentagem de cada tipo de pagamento em relação ao total
        $totalPagamentos = array_sum($tipoPagamentoCount);
        $porcentagens = [];
        foreach ($tipoPagamentoCount as $tipoPagamento => $count) {
            $porcentagens[$tipoPagamento] = ($count / $totalPagamentos) * 100;
        }

        return view('empresa.dashboard_pesquisa', compact(
            'empresa', 
            'sumMensal', 
            'atendimentoTotal', 
            'topProdutos', 
            'sumOrdem', 
            'totalVendasPersonalizadas', 
            'labelsVendasPersonalizadas', 
            'totalVendasPersonalizadasMesAnterior', 
            'totalPorcentagemComparativo',
            'porcentagens',
            'vendasOrdemValor',
            'transactionsProdutosValor',
        ));
    }

    public function dashboard_gerador_pdf_route(Request $request, $empresa, $id_ordem)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');
        
        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $ordem = OrdemServico::where('id', $id_ordem)->first();
        $itens = EquipamentoOS::where('os_permitida', $id_ordem)->where('id_user', Auth::user()->id)->get();



        return view('empresa.dashboard_ordem_de_servico_pdf', compact('empresa', 'ordem', 'itens', 'id_ordem'));

    }

    public function dashboard_ordem_atualizar_status_nao_autorizado(Request $request, $empresa, $id_ordem, $id)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $new = EquipamentoOS::find($id);
        $new->os_nao_autorizada_obs = $request->os_nao_autorizada_obs;
        $new->save();

        if ($new) {
            return back()->with('success', 'Dados atualizados com sucesso!');
        }
    }

    public function dashboard_vendas_detalhes_excluir_venda(Request $request, $empresa, $hash)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        foreach ($request->cod_produto as $index => $codigoProduto) {

            $produto = Produto::where('sku', $codigoProduto)->first();
        
            if ($produto) {
                // Encontra o registro correspondente na tabela RelatorioProdutos
                $relatorioProduto = RelatorioProdutos::where('cod_produto', $codigoProduto)->first();
                
                if ($relatorioProduto) {
                    // Atualiza a quantidade e o valor total
                    $relatorioProduto->qtd_produto -= $request->qtd_produto[$index];
                    $relatorioProduto->valorTotal -= $produto->pvenda * $request->qtd_produto[$index];
                    $relatorioProduto->save();
                }
            }

        }

        $delete1 = VendasProdutos::where('hash_transaction', $hash)->delete();
        $delete2 = TransactionsProdutos::where('hash_transaction', $hash)->delete();

        if ($delete1 && $delete2) {
            return back()->with('success', 'Venda excluida com sucesso!');
        }

    }

    public function dashboard_dados_garantia(Request $request, $empresa, $hash)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $verificar = GarantiaCliente::where('hash', $hash)->first();

        if($verificar){

            return back()->with('error', 'Garantia já cadastrada para esta venda!');

        }

        $new = new GarantiaCliente();
        $new->vendedor = Auth::user()->name;
        $new->hash = $hash;
        $new->clienteNome = $request->clienteNome;
        $new->clienteCEP = $request->clienteCEP;
        $new->clienteCelular = $request->clienteCelular;
        $new->clienteEndereco = $request->clienteEndereco;
        $new->clienteN = $request->clienteN;
        $new->inicioGarantia = $request->inicioGarantia;
        $new->fimGarantia = $request->fimGarantia;
        $new->save();

        // Salvar os itens e quantidades
        foreach ($request->list_produto as $key => $produto) {

            $consulta = Produto::where('sku', $produto)->first();

            $garantiaItem = new GarantiaItens();
            $garantiaItem->item = $consulta->sku;
            $garantiaItem->qtd = $request->qtd_produto[$key];
            $garantiaItem->valor =  $consulta->pvenda;
            $garantiaItem->vendedor = Auth::user()->name;
            $garantiaItem->hash =  $hash;
            $garantiaItem->save();
        }


        
        if ($new) {
            return back()->with('success', 'Garantia lançada com sucesso!');
        }

    }

    public function dashboard_processa_item(Request $request, $empresa, $id_ordem, $id_equipamento)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }
        
        //dd($request->all());

        $new = EquipamentoOS::find($id_equipamento);
        $new->q_aut = Auth::user()->name;
        $new->MeioPagamento = $request->tipo_pagamentoProdutos;
        $new->valorTotal = $request->valor_total;

        if($request->valorPago == null)
        {
            $new->valorPago = $request->valor_total;
        }
        else
        {
            $new->valorPago = $request->valorPago;
        }	

        $new->valorTroco = $request->troco;
        $new->desconto = $request->descontoProdutos;
        $new->valorComDesconto = $request->total;
        $new->parcelaTotal = $request->parcelas;
    
        if($request->parcelas != null)
        {
            $resultado = $request->total / $request->parcelas;
            $resultado_formatado = number_format($resultado, 2, '.', '');
            $new->valorParcelas = $resultado_formatado;
        }
        else
        {
            $new->valorParcelas = $request->total;
        } 

        $new->save();
        
        return back()->with('success', 'Item processado com sucesso!');

    }

    public function dashboard_aguardando_pecas(Request $request, $empresa, $id_ordem, $id_equipamento)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $att = EquipamentoOS::find($id_equipamento);
        $att->pedidoPecas = $request->DataInicial;
        $att->entregaPecas = $request->DataFinal;
        $att->pedidoOBS = $request->aguardando_obs;
        $att->save();

        return back()->with('success', 'Datas de pedido de peças listados com sucesso!');

    }

    public function dashboard_substatus(Request $request, $empresa, $id_ordem, $id_equipamento)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $att = EquipamentoOS::find($id_equipamento);
        $att->substatus = $request->checkbox;
        $att->obs_naoautorizado = $request->obs_naoaut;
        $att->save();

        return back()->with('success', 'Dados atualizado com sucesso!');

    }
    public function dashboard_gen_protocolo(Request $request, $empresa, $id_ordem)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');
        
        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $ordem = OrdemServico::where('id', $id_ordem)->first();
        $itens = EquipamentoOS::where('os_permitida', $id_ordem)->where('id_user', Auth::user()->id)->get();



        return view('empresa.dashboard_gen_protocolo', compact('empresa', 'ordem', 'itens', 'id_ordem'));

    }

    public function deletando_equipamento(Request $request, $empresa, $id){
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');
        
        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        } 

        $deletar = EquipamentoOS::find($id);
        $deletar->delete();

        return back()->with('success', 'Equipamento excluido com sucesso!');
    }

    public function dashboard_ordem_cancelamento(Request $request, $empresa, $id_ordem)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        if(Auth::user()->nivel == '0'){
            $ordem = OrdemServico::where('id', $id_ordem)->first();
            $ordem->status = 'CANCELADA';
            $ordem->obs = $request->obs;
            $ordem->save();
    
            return back()->with('success', 'Ordem cancelada com sucesso!');
        }

       return back()->with('error', 'Você não tem permissão para cancelar ordens de serviço!');
        

    }

    public function dashboard_get_busca_produto(Request $request, $empresa)
    {
        // Busca o registro da empresa na tabela "companies" pelo nome informado na rota.
        $empresa = Company::where('name', $empresa)->firstOrFail();
        // Cria uma nova conexão com o banco de dados da empresa.
        Config::set('database.connections.empresa', [
            'driver' => 'mysql',
            'host' => $empresa->database_host,
            'port' => $empresa->database_port,
            'database' => $empresa->database_name,
            'username' => $empresa->database_username,
            'password' => $empresa->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        // Configura a conexão com o banco de dados da empresa para que fique disponível em todo o escopo da aplicação.
        DB::setDefaultConnection('empresa');

        if (!$request->user()) {
            return redirect("/empresa/$empresa->name")->with('error', 'Você precisa fazer login para acessar essa página.');
        }

        $searchTerm = $request->get('query');
        $produtos = Produto::where('descricao', 'like', "%$searchTerm%")
            ->orWhere('sku', 'like', "%$searchTerm%")
            ->orderBy('descricao', 'asc')
            ->get();

        $output = '<option value="">Selecione seu produto</option>';
        foreach ($produtos as $produto) {
            $output .= '<option value="' . $produto->sku . '">' . $produto->descricao . ' - SKU: ' . $produto->sku . ' - [ R$ ' . $produto->pvenda . ' ] </option>';
        }
        echo $output;
    }
}

