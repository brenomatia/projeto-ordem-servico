@extends('empresa.layouts.dashboard_empresa_menu')

@section('title', 'Dashboard')

@section('content')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <div class="container mt-5">

        @if (session('success'))
            <div id="successAlert" class="alert alert-success mt-5">
                <i class="nav-icon fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div id="errorAlert" class="alert alert-danger mt-5">
                <i class="nav-icon fa-solid fa-circle-exclamation"></i> {{ session('error') }}
            </div>
        @endif

        <script>
            // Função para ocultar o alerta de sucesso após 5 segundos
            setTimeout(function() {
                document.getElementById('successAlert').style.display = 'none';
            }, 4000); // 5000 milissegundos = 5 segundos

            // Função para ocultar o alerta de erro após 5 segundos
            setTimeout(function() {
                document.getElementById('errorAlert').style.display = 'none';
            }, 4000); // 5000 milissegundos = 5 segundos
        </script>

        <div class="card">
            <div class="card-body">

                <form action="{{ route('dashboard_ordem_cadastro_equipamento', ['empresa' => $empresa->name]) }}"
                    method="POST" id="listagem_item">
                    @csrf

                    <div class="input-group mb-3">
                        <div class="input-group-append">
                            <button class="btn btn-primary rounded-left" type="button" id="button-addon2"><i
                                    class="fa-solid fa-wrench"></i></button>
                        </div>
                        <input type="text" class="form-control" id="equipamento" name="equipamento"
                            placeholder="Digite o equipamento">
                    </div>
                </form>

                <form action="{{ route('dashboard_cadastrando_ordem', ['empresa' => $empresa->name]) }}" method="POST"
                    id="formExterno">
                    @csrf

                    @foreach ($equipamentosOS as $new)
                        @if ($new->listado != 'SIM')
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fa-solid fa-circle-chevron-right text-primary mr-2"></i>
                                        <input type="hidden" name="id_equipamento[]" value="{{ $new->id }}" />
                                        <span style="flex: 1;">{{ $new->equipamento }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <button class="btn bg-primary rounded-left" type="button" id="button-addon2"><i class="fa-solid fa-filter"></i></button>
                        </div>
                        <select name="tipo_filter" id="tipo_filter" class="custom-select" required>
                            <option value="">Listar o tipo de OS</option>
                            <option value="BOMBA D' ÁGUA">BOMBA D' ÁGUA</option>
                            <option value="FERRAMENTA">FERRAMENTA</option>
                            <option value="MOTOR EM GERAL">MOTOR EM GERAL</option>
                            <option value="APARELHO DE CHOQUE">APARELHO DE CHOQUE</option>
                            <option value="COMPRESSOR">COMPRESSOR</option>
                            <option value="APARELHO DE SOLDA">APARELHO DE SOLDA</option>
                        </select>
                    </div>

                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <button class="btn bg-primary rounded-left" type="button" id="button-addon2"><i
                                        class="fa-solid fa-user"></i></button>
                            </div>
                            <input type="text" class="form-control" id="cliente_search"
                                placeholder="Digite o nome do cliente" aria-label="Buscar Cliente"
                                aria-describedby="button-addon2" required>
                            <div class="input-group-append">
                                <button class="btn bg-primary" type="button" id="button-addon2"><i
                                        class="fa-solid fa-magnifying-glass"></i></button>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <button class="btn bg-primary rounded-left" type="button" id="button-addon2"><i
                                        class="fa-solid fa-ellipsis"></i></button>
                            </div>
                            <select name="cliente_id" id="cliente_id" class="custom-select" required>
                                <option value="">Listagem de clientes</option>
                            </select>
                        </div>
                    
                    

                    </form>

                    <button class="btn btn-primary col-12" type="button" onclick="ativarClientes()">LISTAR ORDEM DE
                        SERVIÇO</button>


            </div>
        </div>

        <script>
            function ativarClientes() {
                // Enviar o formulário secundário
                document.getElementById('formExterno').submit();
            }
        </script>


        <form action="{{ route('dashboard_buscar_ordem', ['empresa'=>$empresa->name]) }}" method="GET" class="mb-3">
            @csrf
            <div class="input-group">
                <input type="text" class="form-control" name="search_os" placeholder="N° identificador da OS">
                <div class="input-group-append">
                    <span class="input-group-text bg-primary"><i class="fas fa-search text-white"></i></span>
                </div>
            </div>
        </form>


        @if ($ordemServicos->isNotEmpty())
            @foreach ($ordemServicos as $ordem)
                @if($ordem->status == "ABERTA")
                <div class="card ordem-servico" style="border-left: 3px solid #28A745;">
                    <a href="{{ route('setOpenCard', ['empresa' => $empresa->name, 'cardId' => $loop->iteration, 'id_ordem'=>$ordem->id]) }}"
                        class="btn col-12">
                        <div class="card-header border-0" id="heading{{ $loop->iteration }}">
                            <div class="card-title" style="text-align: left;">
                                <span class="badge badge-success" style="font-size: 15px;">
                                    ORDEM DE SERVIÇO #{{ $ordem->id }} - {{ $ordem->status }}
                                </span>
                                <p class="mt-3" style="font-size: 15px;">
                                    <strong>Cliente:</strong> {{ $ordem->nome_cliente }}<br>
                                    <strong>Data de Abertura:</strong> {{ $ordem->created_at->format('d/m/Y') }}
                                </p>
                            </div>
                            <div class="card-tools">

                                <i
                                    class="fas {{ Session::has('openCardId') && Session::get('openCardId') == $loop->iteration ? 'fa-circle-arrow-up text-red' : 'fa-circle-arrow-down text-green' }}"></i>

                            </div>
                        </div>
                    </a>
                    <div id="collapse{{ $loop->iteration }}"
                        class="collapse {{ Session::get('openCardId') == $loop->iteration ? 'show' : '' }}">
                        <div class="card-body">

                            <div class="table-responsive">
                                <div class="card">
                                    <div class="card-body">

                                        <a href="{{ URL::route('dashboard_gerador_pdf_route', ['empresa'=>$empresa->name, 'id_ordem'=>$ordem->id]) }}" target="_Blank"><button class="btn bg-purple mb-3"><i class="fa-solid fa-file-pdf mr-2"></i>VISUALIZAR PDF</button></a>

                                        <div class="row">
                                            <div class="col-lg-3 col-md-6">
                                                <div class="small-box bg-info">
                                                    <div class="inner">
                                                        <h3>R$ {{ number_format($carrinho = $ordem->carrinhos()->sum('valor'), 2, ',', '.') }}</h3>
                                                        
                                                        <p>LISTAGEM</p>
                                                    </div>
                                                    <div class="icon">
                                                        <i class="fa-solid fa-file-invoice-dollar mt-3 mr-1" style="font-size: 60px;"></i>
                                                    </div>
                                                </div>
                                            </div>
                                
                                            <div class="col-lg-3 col-md-6">
                                
                                                <div class="small-box bg-success">
                                                    <div class="inner">
                                                        <h3>R$ {{ number_format($terceiros = $ordem->terceiros()->sum('valor'), 2, ',', '.') }}</h3>
                                                        <p>TERCEIROS</p>
                                                    </div>
                                                    <div class="icon">
                                                        <i class="fa-solid fa-people-carry-box mt-3 mr-1" style="font-size: 60px;"></i>
                                                    </div>
                                                </div>
                                            </div>
                                
                                            <div class="col-lg-3 col-md-6">
                                
                                                <div class="small-box bg-warning">
                                                    <div class="inner">
                                                        <h3>R$ {{ number_format($maodeobra = $ordem->maoDeObras()->sum('valor'), 2, ',', '.') }}</h3>
                                                        <p>MÃO DE OBRA</p>
                                                    </div>
                                                    <div class="icon">
                                                        <i class="ion ion-person-add"></i>
                                                    </div>
                                                </div>
                                            </div>
                                
                                            <div class="col-lg-3 col-md-6">
                                
                                                <div class="small-box bg-danger">
                                                    <div class="inner">
                                                        <h3>R$ {{ number_format(($carrinho + $terceiros) + $maodeobra, 2, ',', '.') }}</h3>
                                                        <p>TOTAL OS</p>
                                                    </div>
                                                    <div class="icon">
                                                        <i class="fa-solid fa-filter-circle-dollar mt-3 mr-1" style="font-size: 60px;"></i>
                                                    </div>
                                                </div>
                                            </div>
                                
                                        </div>

                                        <label>Equipamentos</label>
                                        <table class="table table-bordered rounded">
                                            <tbody>
                                                @foreach ($equipamentosListados as $equipamento)
                                                    @if ($equipamento->os_permitida == $ordem->id)
                                                        <tr>
                                                            <td class="align-middle text-center">

                                                                @if ($equipamento->status == 'DESCARTE' || $equipamento->status == 'ENTREGUE PARA CLIENTE')
                                                                @else
                                                                    <select class="form-control status-select mb-2"
                                                                        id="status_{{ $equipamento->id }}" style="border: 1px solid #007BFF;">
                                                                        <option value="AGUARDANDO ORÇAMENTO"
                                                                            @if ($equipamento->status == 'AGUARDANDO ORÇAMENTO') selected @endif>
                                                                            AGUARDANDO ORÇAMENTO</option>
                                                                        <option value="AGUARDANDO AUTORIZAÇÃO"
                                                                            @if ($equipamento->status == 'AGUARDANDO AUTORIZAÇÃO') selected @endif>
                                                                            AGUARDANDO AUTORIZAÇÃO</option>
                                                                        <option value="AUTORIZADO"
                                                                            @if ($equipamento->status == 'AUTORIZADO') selected @endif>
                                                                            AUTORIZADO</option>
                                                                        <option value="AGUARDANDO PEÇAS"
                                                                            @if ($equipamento->status == 'AGUARDANDO PEÇAS') selected @endif>
                                                                            AGUARDANDO PEÇAS</option>
                                                                        <option value="NÃO AUTORIZADO"
                                                                            @if ($equipamento->status == 'NÃO AUTORIZADO') selected @endif>
                                                                            NÃO AUTORIZADO</option>
                                                                        <option value="PRONTO"
                                                                            @if ($equipamento->status == 'PRONTO') selected @endif>
                                                                            PRONTO</option>
                                                                        <option value="ENTREGUE"
                                                                            @if ($equipamento->status == 'ENTREGUE') selected @endif>
                                                                            ENTREGUE</option>
                                                                    </select>
                                                                @endif
                                                                @if ($equipamento->status == 'AGUARDANDO ORÇAMENTO')
                                                                @elseif($equipamento->status == 'AGUARDANDO AUTORIZAÇÃO')
                                                                <form action="{{ route('dashboard_ordem_atualizar_dados_status', ['empresa'=>$empresa->name, 'id_ordem'=>$ordem->id, 'id'=>$equipamento->id]) }}" method="POST">
                                                                    @csrf
                                                                    <div class="card">
                                                                        <div class="card-body">

                                                                            <div class="input-group mb-3">
                                                                                <div class="input-group-append">
                                                                                    <button
                                                                                        class="btn bg-primary rounded-left"
                                                                                        type="button"
                                                                                        id="button-addon2">R$</button>
                                                                                </div>
                                                                                <input type="text" class="form-control" id="valor_equipamento" name="valor_equipamento" @if(empty($equipamento->valor_autorizado)) placeholder="Insira o valor" @else value="{{ $equipamento->valor_autorizado }}" @endif>

                                                                            </div>
                                                                            <button class="btn bg-primary col-12" type="submit">ATUALIZAR
                                                                                DADOS</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                                @elseif($equipamento->status == 'AUTORIZADO')
                                                                <form action="{{ route('dashboard_ordem_atualizar_dados_status', ['empresa'=>$empresa->name, 'id_ordem'=>$ordem->id, 'id'=>$equipamento->id]) }}" method="POST">
                                                                    @csrf
                                                                    <div class="card">
                                                                        <div class="card-body" style="text-align: left;">
                                                                            <label>Valor:</label>
                                                                            <div class="input-group mb-3">
                                                                                <div class="input-group-append">
                                                                                    <button
                                                                                        class="btn bg-primary rounded-left"
                                                                                        type="button"
                                                                                        id="button-addon2">R$</button>
                                                                                </div>
                                                                                <input type="text" class="form-control"
                                                                                    id="valor_equipamento"
                                                                                    name="valor_equipamento"
                                                                                    @if(empty($equipamento->valor_autorizado)) placeholder="Digite o valor" @else value="{{ $equipamento->valor_autorizado }}" @endif>
                                                                            </div>
                                                                            <label>Valor final:</label>
                                                                            <div class="input-group mb-3">
                                                                                <div class="input-group-append">
                                                                                    <button
                                                                                        class="btn bg-primary rounded-left"
                                                                                        type="button"
                                                                                        id="button-addon2">R$</button>
                                                                                </div>
                                                                                <input type="text" class="form-control"
                                                                                    id="valor_final_equipamento"
                                                                                    name="valor_final_autorizado"
                                                                                    @if(empty($equipamento->valor_final_autorizado)) placeholder="Digite o valor" @else value="{{ $equipamento->valor_final_autorizado }}" @endif>
                                                                            </div>
                                                                            <label>Valor pago:</label>
                                                                            <div class="input-group mb-3">
                                                                                <div class="input-group-append">
                                                                                    <button
                                                                                        class="btn bg-primary rounded-left"
                                                                                        type="button"
                                                                                        id="button-addon2">R$</button>
                                                                                </div>
                                                                                <input type="text" class="form-control"
                                                                                    id="valor_pago_equipamento"
                                                                                    name="valor_pago_autorizado"
                                                                                    @if(empty($equipamento->valor_pago_autorizado)) placeholder="Digite o valor" @else value="{{ $equipamento->valor_pago_autorizado }}" @endif>
                                                                            </div>

                                                                            <label>Forma de pagamento:</label>
                                                                            <div class="input-group mb-3">
                                                                                <div class="input-group-append">
                                                                                    <button
                                                                                        class="btn bg-primary rounded-left"
                                                                                        type="button"
                                                                                        id="button-addon2"><i
                                                                                            class="fa-solid fa-basket-shopping"></i></button>
                                                                                </div>
                                                                                <select class="custom-select" id="forma-pagamento" name="forma_pagamento">
                                                                                    <option value="">Selecione um opção</option>
                                                                                    <option value="PIX" @if($equipamento->tipo_pagamento_autorizado == 'PIX') selected @endif>PIX</option>
                                                                                    <option value="DINHEIRO" @if($equipamento->tipo_pagamento_autorizado == 'DINHEIRO') selected @endif>DINHEIRO</option>
                                                                                    <option value="CARTÃO CRÉDITO" @if($equipamento->tipo_pagamento_autorizado == 'CARTÃO CRÉDITO') selected @endif>CARTÃO DE CRÉDITO</option>
                                                                                    <option value="CARTÃO DÉBITO" @if($equipamento->tipo_pagamento_autorizado == 'CARTÃO DÉBITO') selected @endif>CARTÃO DE DÉBITO</option>
                                                                                    <option value="BOLETO" @if($equipamento->tipo_pagamento_autorizado == 'BOLETO') selected @endif>BOLETO</option>
                                                                                </select>
                                                                                
                                                                            </div>
                                                                            <button type="submit" class="btn bg-primary col-12">ATUALIZAR
                                                                                DADOS</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                                @elseif($equipamento->status == 'AGUARDANDO PEÇAS')
                                                                <form action="{{ route('dashboard_ordem_atualizar_dados_status', ['empresa'=>$empresa->name, 'id_ordem'=>$ordem->id, 'id'=>$equipamento->id]) }}" method="POST">
                                                                    @csrf
                                                                    <div class="card">
                                                                        <div class="card-body" style="text-align: left;">
                                                                            <div class="input-group mb-3">
                                                                                <div class="input-group-append">
                                                                                    <button
                                                                                        class="btn bg-primary rounded-left"
                                                                                        type="button"
                                                                                        id="button-addon2"><i
                                                                                            class="fa-solid fa-calendar-plus"></i></button>
                                                                                </div>
                                                                                <input type="date" class="form-control"
                                                                                    id="date_pedido_pecas" 
                                                                                    name="aguardando_pcs_data"
                                                                                    value="{{ $equipamento->aguardando_pcs_data }}"
                                                                                    />
                                                                            </div>

                                                                            <textarea class="form-control mb-3" rows="4" placeholder="@if(empty($equipamento->aguardando_pcs_obs)) Observações @endif" name="aguardando_pcs_obs">@if(!empty($equipamento->aguardando_pcs_obs)){{ $equipamento->aguardando_pcs_obs }}@endif</textarea>

                                                                            <button class="btn bg-primary col-12">ATUALIZAR
                                                                                DADOS</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                                @elseif($equipamento->status == 'NÃO AUTORIZADO')
                                                                    <div class="input-group mt-3 mb-3">
                                                                        <div class="input-group-append">
                                                                            <button class="btn bg-warning rounded-left"
                                                                                type="button" id="button-addon2"><i
                                                                                    class="fa-solid fa-triangle-exclamation"></i></button>
                                                                        </div>
                                                                        <select class="custom-select select2"
                                                                            id="seleciona2_{{ $equipamento->id }}">
                                                                            <option value="">Selecione</option>
                                                                            <option value="DESCARTE">DESCARTE</option>
                                                                            <option value="ENTREGUE PARA CLIENTE">ENTREGUE
                                                                                PARA CLIENTE</option>
                                                                        </select>
                                                                    </div>
                                                                    <form action="{{ route('dashboard_ordem_atualizar_status_nao_autorizado', ['empresa'=>$empresa->name, 'id_ordem'=>$ordem->id, 'id'=>$equipamento->id]) }}" method="POST">
                                                                        @csrf
                                                                        <textarea class="form-control mb-3" rows="4" placeholder="@if(empty($equipamento->os_nao_autorizada_obs)) Observações @endif" name="os_nao_autorizada_obs">@if(!empty($equipamento->os_nao_autorizada_obs)){{ $equipamento->os_nao_autorizada_obs }}@endif</textarea>
                                                                        <button class="btn bg-primary col-12">ATUALIZAR DADOS</button>
                                                                    </form>
                                                                @elseif($equipamento->status == 'DESCARTE')
                                                                    <div class="alert bg-orange" role="alert">
                                                                        <div class="d-flex align-items-center text-white">
                                                                            <i
                                                                                class="fas fa-exclamation-triangle mr-3 text-white"></i>
                                                                            <span>O item foi descartado.</span>
                                                                        </div>
                                                                    </div>
                                                                    <textarea class="form-control mb-3" rows="4" disabled placeholder="@if(empty($equipamento->os_nao_autorizada_obs)) Observações @endif" name="os_nao_autorizada_obs">@if(!empty($equipamento->os_nao_autorizada_obs)){{ $equipamento->os_nao_autorizada_obs }}@endif</textarea>
                                                                    <form class="statusForm">
                                                                        @csrf
                                                                        <input type="hidden" value="NÃO AUTORIZADO"
                                                                            name="status" />
                                                                        <button type="button"
                                                                            class="btn bg-danger col-12 status-button"
                                                                            data-status="NÃO AUTORIZADO"
                                                                            data-equipamento-id="{{ $equipamento->id }}">VOLTAR
                                                                            PARA NÃO AUTORIZADO</button>
                                                                    </form>
                                                                @elseif($equipamento->status == 'ENTREGUE PARA CLIENTE')
                                                                    <div class="alert alert-success" role="alert">
                                                                        <div class="d-flex align-items-center">
                                                                            <i
                                                                                class="fas fa-exclamation-triangle mr-3"></i>
                                                                            <span>Item já entregue para o cliente</span>
                                                                            
                                                                        </div>
                                                                    </div>
                                                                    <textarea class="form-control mb-3" rows="4" disabled placeholder="@if(empty($equipamento->os_nao_autorizada_obs)) Observações @endif" name="os_nao_autorizada_obs">@if(!empty($equipamento->os_nao_autorizada_obs)){{ $equipamento->os_nao_autorizada_obs }}@endif</textarea>

                                                                    <form class="statusForm">
                                                                        @csrf
                                                                        <input type="hidden" value="NÃO AUTORIZADO"
                                                                            name="status" />
                                                                        <button type="button"
                                                                            class="btn bg-danger col-12 status-button"
                                                                            data-status="NÃO AUTORIZADO"
                                                                            data-equipamento-id="{{ $equipamento->id }}">VOLTAR
                                                                            PARA NÃO AUTORIZADO</button>
                                                                    </form>
                                                                @elseif($equipamento->status == "PRONTO")
                                                                <form action="{{ route('dashboard_ordem_atualizar_dados_status', ['empresa'=>$empresa->name, 'id_ordem'=>$ordem->id, 'id'=>$equipamento->id]) }}" method="POST">
                                                                    @csrf
                                                                    <div class="card">
                                                                        <div class="card-body" style="text-align: left;">
                                                                            <label>Valor:</label>
                                                                            <div class="input-group mb-3">
                                                                                <div class="input-group-append">
                                                                                    <button
                                                                                        class="btn bg-primary rounded-left"
                                                                                        type="button"
                                                                                        id="button-addon2">R$</button>
                                                                                </div>
                                                                                <input type="text" class="form-control"
                                                                                    id="valor_equipamento"
                                                                                    name="valor_equipamento"
                                                                                    @if(empty($equipamento->valor_autorizado)) placeholder="Digite o valor" @else value="{{ $equipamento->valor_autorizado }}" @endif>
                                                                            </div>
                                                                            <label>Valor final:</label>
                                                                            <div class="input-group mb-3">
                                                                                <div class="input-group-append">
                                                                                    <button
                                                                                        class="btn bg-primary rounded-left"
                                                                                        type="button"
                                                                                        id="button-addon2">R$</button>
                                                                                </div>
                                                                                <input type="text" class="form-control"
                                                                                    id="valor_final_equipamento"
                                                                                    name="valor_final_autorizado"
                                                                                    @if(empty($equipamento->valor_final_autorizado)) placeholder="Digite o valor" @else value="{{ $equipamento->valor_final_autorizado }}" @endif>
                                                                            </div>
                                                                            <label>Valor pago:</label>
                                                                            <div class="input-group mb-3">
                                                                                <div class="input-group-append">
                                                                                    <button
                                                                                        class="btn bg-primary rounded-left"
                                                                                        type="button"
                                                                                        id="button-addon2">R$</button>
                                                                                </div>
                                                                                <input type="text" class="form-control"
                                                                                    id="valor_pago_equipamento"
                                                                                    name="valor_pago_autorizado"
                                                                                    @if(empty($equipamento->valor_pago_autorizado)) placeholder="Digite o valor" @else value="{{ $equipamento->valor_pago_autorizado }}" @endif>
                                                                            </div>

                                                                            <label>Forma de pagamento:</label>
                                                                            <div class="input-group mb-3">
                                                                                <div class="input-group-append">
                                                                                    <button
                                                                                        class="btn bg-primary rounded-left"
                                                                                        type="button"
                                                                                        id="button-addon2"><i
                                                                                            class="fa-solid fa-basket-shopping"></i></button>
                                                                                </div>
                                                                                <select class="custom-select" id="forma-pagamento" name="forma_pagamento">
                                                                                    <option value="">Selecione um opção</option>
                                                                                    <option value="PIX" @if($equipamento->tipo_pagamento_autorizado == 'PIX') selected @endif>PIX</option>
                                                                                    <option value="DINHEIRO" @if($equipamento->tipo_pagamento_autorizado == 'DINHEIRO') selected @endif>DINHEIRO</option>
                                                                                    <option value="CARTÃO CRÉDITO" @if($equipamento->tipo_pagamento_autorizado == 'CARTÃO CRÉDITO') selected @endif>CARTÃO DE CRÉDITO</option>
                                                                                    <option value="CARTÃO DÉBITO" @if($equipamento->tipo_pagamento_autorizado == 'CARTÃO DÉBITO') selected @endif>CARTÃO DE DÉBITO</option>
                                                                                    <option value="BOLETO" @if($equipamento->tipo_pagamento_autorizado == 'BOLETO') selected @endif>BOLETO</option>
                                                                                </select>
                                                                                
                                                                            </div>
                                                                            <button type="submit" class="btn bg-primary col-12">ATUALIZAR
                                                                                DADOS</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                                @elseif($equipamento->status == "ENTREGUE")

                                                                <div class="alert alert-success" role="alert">
                                                                    <div class="d-flex align-items-center">
                                                                        <i
                                                                            class="fas fa-exclamation-triangle mr-3"></i>
                                                                        <span>Item entregue para o cliente {{ $ordem->nome_cliente }} .</span>
                                                                        
                                                                    </div>
                                                                </div>

                                                                @endif

                                                                <p style="font-size: 12px; text-align: left;">Ultima atualização: {{ $equipamento->updated_at->format('d/m/Y H:i:s') }} - Por: {{ $equipamento->q_aut }}</p>

                                                            </td>
                                                            <td class="align-middle text-center" style="cursor: pointer;" onclick="window.location='{{ route('dashboard_listar_items_ordem', ['empresa'=>$empresa->name, 'id_ordem'=>$ordem->id, 'id_equipamento'=>$equipamento->id]) }}';">
                                                                {{ $equipamento->equipamento }}
                                                                <br>
                                                                <span class="badge badge-primary" style="font-size: 18px;">Total: R$ {{ ( $equipamento->carrinhos()->sum('valor') +  $equipamento->terceiro()->sum('valor') ) +  $equipamento->maodeobra()->sum('valor') }}</span>
                                                            </td>
                                                            <td class="align-middle text-center">

                                                                <!-- VISUALIZAR GARANTIA -->
                                                                @if($equipamento->nfe_garantia == null)
                                                                <button type="button" class="btn bg-purple"
                                                                    data-toggle="modal"
                                                                    data-target="#garantia_{{ $equipamento->id }}"
                                                                    data-toggle="tooltip" title="Garantia equipamento">
                                                                    <i class="fa-solid fa-certificate"></i>
                                                                </button>
                                                                @else
                                                                <button type="button" class="btn bg-success"
                                                                    data-toggle="modal"
                                                                    data-target="#garantia_{{ $equipamento->id }}"
                                                                    data-toggle="tooltip" title="Garantia equipamento">
                                                                    GARANTIA REGISTRADA
                                                                </button>
                                                                @endif

                                                                <div class="modal fade"
                                                                    id="garantia_{{ $equipamento->id }}"
                                                                    tabindex="-1" role="dialog"
                                                                    aria-labelledby="garantia_{{ $equipamento->id }}"
                                                                    aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title"
                                                                                    id="garantia_{{ $equipamento->id }}">
                                                                                    {{ $equipamento->equipamento }} garantia</h5>
                                                                                <button type="button" class="close"
                                                                                    data-dismiss="modal"
                                                                                    aria-label="Fechar">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body"
                                                                                style="text-align: left;">

                                                                                <form
                                                                                    action="{{ route('dashboard_ordem_atualizar_garantia_equipamento', ['empresa' => $empresa->name, 'id_ordem' => $ordem->id, 'id' => $equipamento->id]) }}"
                                                                                    method="POST">
                                                                                    @csrf
                                                                                    <label>Data compra:</label>
                                                                                    <input type="date" class="form-control mb-2" name="data" value="{{ $equipamento->data_compra_garantia }}" />
                                                                                    <label>Vendido por:</label>
                                                                                    <input type="text" class="form-control mb-2" name="vendedor" value="{{ $equipamento->vendido_por_garantia }}" />
                                                                                    <label>Defeito:</label>
                                                                                    <input type="text" class="form-control mb-2" name="defeito" value="{{ $equipamento->defeito_garantia }}" />
                                                                                    <label>Nº NFE:</label>
                                                                                    <input type="text" class="form-control mb-2" name="nfe" value="{{ $equipamento->nfe_garantia }}" />
                                                                                    <label>Uso profissional:</label>
                                                                                    <select class="form-control mb-2" name="opcao">
                                                                                        <option value="">Selecione uma opção válida</option>
                                                                                        <option value="sim" @if($equipamento->uso_profissional_garantia == 'sim') selected @endif>Sim</option>
                                                                                        <option value="nao" @if($equipamento->uso_profissional_garantia == 'nao') selected @endif>Não</option>
                                                                                    </select>
                                                                                    
                                                                                    <button type="submit" class="btn btn-primary col-12">ATUALIZAR</button>
                                                                                </form>


                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- FIM VISUALIZAR GARANTIA -->


                                                                <!-- APAGAR ATUALIZAR EQUIPAMENTO -->

                                                                <button type="button" class="btn btn-primary"
                                                                    data-toggle="modal"
                                                                    data-target="#atualizardados_{{ $equipamento->id }}"
                                                                    data-toggle="tooltip" title="Alterar nome equipamento">
                                                                    <i class="fa-solid fa-eye"></i>
                                                                </button>

                                                                <div class="modal fade"
                                                                    id="atualizardados_{{ $equipamento->id }}"
                                                                    tabindex="-1" role="dialog"
                                                                    aria-labelledby="atualizardados_{{ $equipamento->id }}"
                                                                    aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title"
                                                                                    id="atualizardados_{{ $equipamento->id }}">
                                                                                    {{ $equipamento->equipamento }}</h5>
                                                                                <button type="button" class="close"
                                                                                    data-dismiss="modal"
                                                                                    aria-label="Fechar">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body"
                                                                                style="text-align: left;">

                                                                                <form
                                                                                    action="{{ route('dashboard_ordem_atualizar_equipamento', ['empresa' => $empresa->name, 'id_ordem' => $ordem->id, 'id' => $equipamento->id]) }}"
                                                                                    method="POST">
                                                                                    @csrf
                                                                                    <label>Equipamento:</label>
                                                                                    <input class="form-control mb-2"
                                                                                        name="equipamento"
                                                                                        value="{{ $equipamento->equipamento }}" />
                                                                                    <button type="submit"
                                                                                        class="btn btn-primary col-12">ATUALIZAR</button>
                                                                                </form>



                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- FIM ATUALIZAR EQUIPAMENTO -->

                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>



                                        <!-- APAGAR PRODUTO -->
                                        <div>
                                            <button type="button" class="btn btn-danger mb-4 mt-4 col-12" data-toggle="modal"
                                                data-target="#deletarORDEM{{ $ordem->id }}" data-toggle="tooltip"
                                                title="Excluir cliente">
                                                <i class="fa-solid fa-trash-can"></i> EXCLUIR ORDEM
                                            </button>
    
                                            <div class="modal fade" id="deletarORDEM{{ $ordem->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="deletarORDEM{{ $ordem->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="deletarORDEM{{ $ordem->id }}">
                                                                Confirmar Exclusão</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Fechar">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Tem certeza de que deseja a excluir a ordem do cliente
                                                            "{{ $ordem->nome_cliente }}" ?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Cancelar</button>
                                                            <form
                                                                action="{{ route('dashboard_ordem_deletar_registro', ['empresa' => $empresa->name, 'id_ordem' => $ordem->id]) }}"
                                                                method="POST" style="display: inline-block;">
                                                                @csrf
                                                                <button type="submit" class="btn btn-danger">Excluir</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- FIM APAGAR PRODUTO --> 
                                            </div>





                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                @endif


                <script>
                    $(document).ready(function() {
                        $('.status-button').click(function() {
                            var novoStatus = $(this).data('status');
                            var empresaNome = '{{ $empresa->name }}';
                            var idOrdem = '{{ $ordem->id ? $ordem->id : '' }}';
                            var equipamentoId = $(this).data('equipamento-id');
            
                            // Enviar requisição AJAX para atualizar o status no banco de dados
                            $.ajax({
                                url: '/empresa/' + empresaNome + '/ordem/cadastrando_ordem/atualizar_status/' +
                                    idOrdem + '/equipamento/' + equipamentoId,
                                method: 'GET',
                                data: {
                                    status: novoStatus
                                },
                                success: function(response) {
                                    //alert(response.message);
                                    window.location.reload();
                                },
                                error: function(xhr, status, error) {
                                    //alert(xhr.responseText);
                                    window.location.reload();
                                }
                            });
                        });
                    });
                </script>
            
                <script>
                    $(document).ready(function() {
                        $('.select2').change(function() {
                            var novoStatus = $(this).val();
                            var equipamentoId = $(this).attr('id').replace('seleciona2_', ''); // Correção do id
                            var empresaNome = '{{ $empresa->name }}';
                            var idOrdem = '{{ $ordem->id ? $ordem->id : '' }}';; // Correção do nome da variável
                            // Enviar requisição AJAX para atualizar o status no banco de dados
                            $.ajax({
                                url: '/empresa/' + empresaNome + '/ordem/cadastrando_ordem/atualizar_status/' +
                                    idOrdem + '/equipamento/' + equipamentoId,
                                method: 'GET',
                                data: {
                                    status: novoStatus
                                },
                                success: function(response) {
                                    //alert(response.message);
                                    window.location.reload();
                                },
                                error: function(xhr, status, error) {
                                    //alert(xhr.responseText);
                                    window.location.reload();
                                }
                            });
                        });
                    });
                </script>
            
                <script>
                    $(document).ready(function() {
                        $('.status-select').change(function() {
                            var novoStatus = $(this).val();
                            var equipamentoId = $(this).attr('id').replace('status_', '');
                            var empresaNome = '{{ $empresa->name }}';
                            var Idordem = '{{ $ordem->id ? $ordem->id : '' }}';;
                            // Enviar requisição AJAX para atualizar o status no banco de dados
                            $.ajax({
                                url: '/empresa/' + empresaNome + '/ordem/cadastrando_ordem/atualizar_status/' +
                                    Idordem + '/equipamento/' + equipamentoId,
                                method: 'GET',
                                data: {
                                    status: novoStatus
                                },
                                success: function(response) {
                                    //alert(response.message);
                                    window.location.reload();
                                },
                                error: function(xhr, status, error) {
                                    //alert(xhr.responseText);
                                    window.location.reload();
                                }
                            });
                        });
                    });
                </script>



            @endforeach
        @else
            <p>Nenhuma ordem de serviço encontrada.</p>
        @endif







    </div>




    <script>
        $(document).ready(function() {
            $('#cliente_search').keyup(function() {
                var query = $(this).val();
                if (query != '') {
                    $.ajax({
                        url: "{{ route('dashboard_ordem_buscar_cliente', ['empresa' => $empresa->name]) }}",
                        method: "GET",
                        data: {
                            query: query
                        },
                        success: function(data) {
                            $('#cliente_id').html(data);
                        }
                    });
                }
            });

        });
    </script>

    <script>
        // Função para formatar o nome completo com a primeira letra de cada palavra em maiúsculo
        $('#cliente_search').on('input', function() {
            var nomeCompleto = $(this).val().toLowerCase().replace(/(?:^|\s)\w/g, function(letter) {
                return letter.toUpperCase();
            });
            $(this).val(nomeCompleto);
        });
        // Função para formatar o nome completo com a primeira letra de cada palavra em maiúsculo
        $('#equipamento').on('input', function() {
            var nomeCompleto = $(this).val().toLowerCase().replace(/(?:^|\s)\w/g, function(letter) {
                return letter.toUpperCase();
            });
            $(this).val(nomeCompleto);
        });
    </script>

@endsection
