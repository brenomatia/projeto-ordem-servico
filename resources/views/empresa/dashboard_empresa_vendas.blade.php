@extends('empresa.layouts.dashboard_empresa_menu')

@section('title', 'Dashboard')

@section('content')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>



    <div class="container mt-3">

        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mt-3 p-3" role="alert"  style="font-size: 20px;">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2 fa-lg mr-2"></i>
                <span class="fw-bold">{{ session('success') }}</span>
     
            </div>
        </div>
    @endif
    
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mt-3 p-3" role="alert" style="font-size: 20px;">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle me-2 fa-lg mr-2"></i>
                <span class="fw-bold">{{ session('error') }}</span>
          
            </div>
        </div>
    @endif
    
    <script>
        // Função para ocultar o alerta após 4 segundos
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                alert.style.opacity = 0;
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 4000); // 4000 milissegundos = 4 segundos
    </script>
    
    
    



        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="vendas-tab" data-toggle="tab" href="#vendas" role="tab"
                                    aria-controls="vendas" aria-selected="true">VENDAS PRODUTO</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="fechar-ordem-tab" data-toggle="tab" href="#fechar-ordem"
                                    role="tab" aria-controls="fechar-ordem" aria-selected="false">FECHAR ORDEM</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="vendas" role="tabpanel" aria-labelledby="vendas-tab">
                            <div class="container p-4">
                                <div class="row">
                                    <div class="col-md-8">

                                        <form action="{{ route('dashboard_vendas_busca_produto', ['empresa'=>$empresa->name]) }}" method="POST">
                                            @csrf
                                            <div class="row">
                                                <div class="form-group col-md-10">
                                                    <label for="codigo_produto">Código do Produto</label>
                                                    <input type="text" class="form-control" id="cod_produto"
                                                        name="cod_produto" placeholder="Código do Produto">
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label for="quantidade">Quantidade</label>
                                                    <input type="number" class="form-control" id="qtd" name="qtd" value="1" min="1" pattern="[1-9][0-9]*">
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-block mt-4">LANÇAR PRODUTO</button>
                                        </form>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <table class="table p-4 mt-4">
                                                    <tbody>
                                                        <tr>
                                                            <th style="border: none; font-size: 20px;">Total</th>
                                                            <td id="subtotal" style="border: none; font-size: 20px;">R$
                                                                {{ number_format($sumTotalProdutos, 2, ',', '.') }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <button class="btn bg-primary col-12 mt-3" data-toggle="modal"
                                                    data-target="#modalPagamentoProdutos">FINALIZAR VENDA</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div class="modal fade" id="modalPagamentoProdutos" tabindex="-1" role="dialog"
                                aria-labelledby="modalPagamentoLabel1" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button class="btn bg-success" id="modalPagamentoLabel1"
                                                style="font-size: 20px;">Total R$ <span
                                                    id="TotalProdutos">{{ $sumTotalProdutos }}</span></button>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('dashboard_vendas_request_modal_produtos', ['empresa' => $empresa->name]) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="desconto_aplicadoProdutos" id="TotalProdutosAplicado" />
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group" id="descontoContainerProdutos">
                                                            <label for="DescontoProdutos">Desconto (%)</label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" name="descontoProdutos" id="DescontoProdutos">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">%</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="tipo_pagamentoProdutos">Tipo de Pagamento</label>
                                                            <select class="form-control" id="tipo_pagamentoProdutos" name="tipo_pagamentoProdutos">
                                                                <option value="PIX">PIX</option>
                                                                <option value="DINHEIRO">DINHEIRO</option>
                                                                <option value="CARTAO_CREDITO">CARTÃO DE CRÉDITO</option>
                                                                <option value="CARTAO_DEBITO">CARTÃO DE DÉBITO</option>
                                                                <option value="BOLETO_A_VISTA">BOLETO A VISTA</option>
                                                                <option value="BOLETO_PARCELADO">BOLETO PARCELADO</option>
                                                                <option value="REVENDA">REVENDA</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group" id="parcelasContainerProdutos" style="display:none;">
                                                            <label for="parcelasProdutos">Parcelas</label>
                                                            <select class="form-control" id="parcelasProdutos" name="parcelasProdutos">
                                                                <!-- Options serão adicionados dinamicamente pelo JavaScript -->
                                                            </select>
                                                        </div>
                                                        <div class="form-group" id="parcelasRevendaContainerProdutos" style="display:none;">
                                                            <label for="parcelasRevendaProdutos">Parcelas para Revenda</label>
                                                            <select class="form-control" id="parcelasRevendaProdutos" name="parcelasRevendaProdutos">
                                                                <!-- Options serão adicionados dinamicamente pelo JavaScript -->
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-12" id="revendaContainerProdutos" style="display:none;">
                                                        <label for="select_revendaProdutos">Selecione a Revenda</label>
                                                        <select class="form-control" id="select_revendaProdutos" name="id_revendaProdutos">
                                                            <option value="">Selecione a revenda</option>
                                                            @foreach ($revendas as $revenda)
                                                                <option value="{{ $revenda->id }}" data-desconto="{{ $revenda->desconto }}">
                                                                    {{ $revenda->nome_empresa }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                                    <button type="submit" class="btn btn-primary" id="finalizarPagamentoProdutos">Finalizar Pagamento</button>
                                                </div>
                                            </form>




                                        </div>
                                    </div>
                                </div>


                            </div>


                            @if ($vendasProdutos->isNotEmpty())

                            <form action="{{ route('dashboard_vendas_request_produtos_concluidas', ['empresa' => $empresa->name]) }}" method="POST" id="form_vendas_produtos">
                                @csrf
                                @foreach ($vendasProdutos as $venda)
                                    <input type="hidden" name="cod_produto[]" value="{{ $venda->cod_produto }}">
                                    <input type="hidden" name="qtd_produto[]" value="{{ $venda->qtd }}">
                                    <input type="hidden" name="valor_total[]" value="{{ $venda->total }}">
                                @endforeach
                            
                                <!-- Campos de informações do cliente e da venda -->
                                <input type="hidden" name="cliente_nome" value="{{ session('cliente_nomeProdutos') }}">
                                <input type="hidden" name="desconto" value="{{ session('descontoProdutos') }}">
                                <input type="hidden" name="valor_pago" value="{{ session('valor_pagoProdutos') }}">
                                <input type="hidden" name="tipo_pagamento" value="{{ session('tipo_pagamentoProdutos') }}">
                                <input type="hidden" name="parcelas" value="{{ session('parcelasProdutos') }}">
                                <input type="hidden" name="id_revenda" value="{{ session('id_revendaProdutos') }}">

                            </form>

                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table  p-3" style="font-size: 18px;">
                                                    <thead>
                                                        <tr>
                                                            <th class="align-middle">CODIGO</th>
                                                            <th class="align-middle">PRODUTO</th>
                                                            <th class="align-middle">QTD.</th>
                                                            <th class="align-middle">VALOR</th>
                                                            <th class="align-middle">OPÇÕES</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($vendasProdutos as $venda)
                                                            <tr>
                                                                <td class="align-middle">{{ $venda->cod_produto }}</td>
                                                                <td class="align-middle">{{ $venda->nome_produto }}</td>
                                                                <td class="align-middle">{{ $venda->qtd }}</td>
                                                                <td class="align-middle">R${{ number_format($venda->total, 2, ',', '.') }}</td>

                                                                <td class="align-middle">


                                                                    <!-- EXCLUIR TERCEIRO -->
                                                                    <button type="button" class="btn btn-danger"
                                                                        data-toggle="modal"
                                                                        data-target="#excluirdadosVendaProduto{{ $venda->id }}"
                                                                        data-toggle="tooltip"
                                                                        title="Excluir ordem de serviço">
                                                                        <i class="fa-solid fa-trash-can"></i>
                                                                    </button>

                                                                    <div class="modal fade"
                                                                        id="excluirdadosVendaProduto{{ $venda->id }}"
                                                                        tabindex="-1" role="dialog"
                                                                        aria-labelledby="excluirdadosVendaProduto{{ $venda->id }}"
                                                                        aria-hidden="true">
                                                                        <div class="modal-dialog" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title"
                                                                                        id="excluirdadosVendaProduto{{ $venda->id }}">
                                                                                        Confirmar Exclusão</h5>
                                                                                    <button type="button" class="close"
                                                                                        data-dismiss="modal"
                                                                                        aria-label="Fechar">
                                                                                        <span
                                                                                            aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    Tem certeza de que deseja excluir o item
                                                                                    "{{ $venda->nome_produto }}" do carrinho ?
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button"
                                                                                        class="btn btn-secondary"
                                                                                        data-dismiss="modal">CANCELAR</button>
                                                                                    <form
                                                                                        action="{{ route('dashboard_vendas_deletar_produto', ['empresa' => $empresa->name, 'id' => $venda->id]) }}"
                                                                                        method="POST">
                                                                                        @csrf
                                                                                        <button type="submit"
                                                                                            class="btn bg-danger">EXCLUIR</button>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- FIM EXCLUIR TERCEIRO -->

                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @endif


                            </div>
                        </div>
                        <div class="tab-pane fade" id="fechar-ordem" role="tabpanel" aria-labelledby="fechar-ordem-tab">




                            <div class="container p-4">
                                <div class="row">
                                    <div class="col-md-8">
                                        <form action="{{ route('dashboard_vendas_busca_os', ['empresa' => $empresa->name]) }}"
                                            method="POST">
                                            @csrf
                                            <label>Nº da ordem de serviço:</label>
                                            <div class="form-group input-group">
                                                <input type="text" class="form-control" id="id_ordem" name="id_ordem"
                                                    placeholder="Código da ordem de serviço" required>
                                                <div class="input-group-append">
                                                    <button class="btn bg-primary" type="submit"><i
                                                            class="fa-solid fa-plus"></i></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <table class="table p-4 mt-4">
                                                    <tbody>
                                                        <tr>
                                                            <th style="border: none; font-size: 20px;">Total</th>
                                                            <td id="subtotal" style="border: none; font-size: 20px;">R$
                                                                {{ number_format($sumTotalOrdem, 2, ',', '.') }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <button class="btn bg-primary col-12 mt-3" data-toggle="modal" data-target="#modalPagamento">FINALIZAR VENDA</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <div class="modal fade" id="modalPagamento" tabindex="-1" role="dialog"
                                aria-labelledby="modalPagamentoLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button class="btn bg-success" id="modalPagamentoLabel"
                                                style="font-size: 20px;">Total R$ <span
                                                    id="TotalOrdem">{{ $sumTotalOrdem }}</span></button>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('dashboard_vendas_request_modal_ordem_servico', ['empresa' => $empresa->name]) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="desconto_aplicadoOrdem" id="desconto_aplicadoOrdem" />
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group" id="descontoContainerOrdem">
                                                            <label for="Desconto">Desconto (%)</label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" name="descontoOrdem" id="DescontoOrdem">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">%</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="tipo_pagamento">Tipo de Pagamento</label>
                                                            <select class="form-control" id="tipo_pagamentoOrdem" name="tipo_pagamento">
                                                                <option value="PIX">PIX</option>
                                                                <option value="DINHEIRO">DINHEIRO</option>
                                                                <option value="CARTAO_CREDITO">CARTÃO DE CRÉDITO</option>
                                                                <option value="CARTAO_DEBITO">CARTÃO DE DÉBITO</option>
                                                                <option value="BOLETO_A_VISTA">BOLETO A VISTA</option>
                                                                <option value="BOLETO_PARCELADO">BOLETO PARCELADO</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group" id="parcelasContainerOrdem" style="display:none;">
                                                            <label for="parcelas">Parcelas</label>
                                                            <select class="form-control" id="parcelasOrdem" name="parcelas">
                                                                <!-- Options serão adicionados dinamicamente pelo JavaScript -->
                                                            </select>
                                                        </div>
                                                        <div class="form-group" id="parcelasRevendaContainerOrdem" style="display:none;">
                                                            <label for="parcelasRevenda">Parcelas para Revenda</label>
                                                            <select class="form-control" id="parcelasRevendaOrdem" name="parcelasRevenda">
                                                                <!-- Options serão adicionados dinamicamente pelo JavaScript -->
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                                    <button type="submit" class="btn btn-primary" id="finalizarPagamento">Finalizar Pagamento</button>
                                                </div>
                                            </form>
                                    

                                        </div>
                                    </div>
                                </div>


                            </div>
                            @if ($vendasOrdem->isNotEmpty())

                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table  p-3" style="font-size: 18px;">
                                                    <thead>
                                                        <tr>
                                                            <th class="align-middle">Nº ordem</th>
                                                            <th class="align-middle">Cliente</th>
                                                            <th class="align-middle">Listagem</th>
                                                            <th class="align-middle">Terceiros</th>
                                                            <th class="align-middle">Mão de obra</th>
                                                            <th class="align-middle">Total</th>
                                                            <th class="align-middle">Ações</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($vendasOrdem as $venda)
                                                            <tr>
                                                                <form
                                                                    action="{{ route('dashboard_vendas_request_ordem_concluidas', ['empresa' => $empresa->name]) }}"
                                                                    method="POST" id="form_vendas_Ordem">
                                                                    @csrf
                                                                    

                                                                    <input type="hidden" name="cod_os" value="{{ $venda->cod_os }}">
                                                                    <input type="hidden" name="cliente_os" value="{{ $venda->cliente_os }}">
                                                                    <input type="hidden" name="desconto" value="{{ session('desconto') }}">
                                                                    <input type="hidden" name="valor_total" value="{{ $venda->total }}">
                                                                    <input type="hidden" name="valor_pago" value="{{ session('valor_pago') }}">
                                                                    <input type="hidden" name="parcelas" value="{{ session('parcelas') }}">
                                                                    <input type="hidden" name="tipo_pagamento" value="{{ session('tipo_pagamento') }}">
                                                                  

                                                                </form>
                                                                <td class="align-middle">{{ $venda->cod_os }}</td>
                                                                <td class="align-middle">{{ $venda->cliente_os }}</td>
                                                                <td class="align-middle">R$
                                                                    {{ number_format($venda->ordemServico->carrinhos()->sum('valor'), 2, ',', '.') }}
                                                                </td>
                                                                <td class="align-middle">R$
                                                                    {{ number_format($venda->ordemServico->terceiros()->sum('valor'), 2, ',', '.') }}
                                                                </td>
                                                                <td class="align-middle">R$
                                                                    {{ number_format($venda->ordemServico->maoDeObras()->sum('valor'), 2, ',', '.') }}
                                                                </td>
                                                                <td class="align-middle">R$
                                                                    {{ number_format($venda->total, 2, ',', '.') }}</td>
                                                                <td class="align-middle">


                                                                    <!-- EXCLUIR TERCEIRO -->
                                                                    <button type="button" class="btn btn-danger"
                                                                        data-toggle="modal"
                                                                        data-target="#excluirdados{{ $venda->id }}"
                                                                        data-toggle="tooltip"
                                                                        title="Excluir ordem de serviço">
                                                                        <i class="fa-solid fa-trash-can"></i>
                                                                    </button>

                                                                    <div class="modal fade"
                                                                        id="excluirdados{{ $venda->id }}"
                                                                        tabindex="-1" role="dialog"
                                                                        aria-labelledby="excluirdados{{ $venda->id }}"
                                                                        aria-hidden="true">
                                                                        <div class="modal-dialog" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title"
                                                                                        id="excluirdados{{ $venda->id }}">
                                                                                        Confirmar Exclusão</h5>
                                                                                    <button type="button" class="close"
                                                                                        data-dismiss="modal"
                                                                                        aria-label="Fechar">
                                                                                        <span
                                                                                            aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    Tem certeza de que deseja excluir a
                                                                                    ordem do cliente
                                                                                    "{{ $venda->cliente_os }}"?
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button"
                                                                                        class="btn btn-secondary"
                                                                                        data-dismiss="modal">CANCELAR</button>
                                                                                    <form
                                                                                        action="{{ route('dashboard_vendas_deletar_os', ['empresa' => $empresa->name, 'id' => $venda->id]) }}"
                                                                                        method="POST">
                                                                                        @csrf
                                                                                        <button type="submit"
                                                                                            class="btn bg-danger">EXCLUIR</button>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- FIM EXCLUIR TERCEIRO -->

                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @endif

                        </div>
                    </div>
                </div>

            </div>





        </div>



<script>
            $(document).ready(function() {
                function adicionarParcelas(sumTotal, parcelasContainer, desconto) {
                    var selectParcelas = parcelasContainer;
                    selectParcelas.empty(); // Limpa as opções atuais
        
                    // Aplica o desconto se houver
                    if (desconto) {
                        sumTotal *= (1 - (desconto / 100));
                    }
        
                    // Adiciona as opções de parcelas de 1x a 12x
                    for (var i = 1; i <= 12; i++) {
                        var valorParcela = sumTotal / i;
                        var option = $('<option></option>').attr('value', i).text(i + 'x R$' + valorParcela.toFixed(2));
                        selectParcelas.append(option);
                    }
                }
        
                $('#tipo_pagamentoProdutos').change(function() {
                    var tipoPagamento = $(this).val();
                    if (tipoPagamento == 'BOLETO_PARCELADO' || tipoPagamento == 'CARTAO_CREDITO') {
                        $('#parcelasContainerProdutos').show();
                        $('#revendaContainerProdutos').hide();
                        $('#parcelasRevendaContainerProdutos').hide();
                        $('#descontoContainerProdutos').show();
        
                        var sumTotal = parseFloat("{{ $sumTotalProdutos }}"); // Substitua 1000 pelo valor total real
                        var desconto = parseFloat($('#DescontoProdutos').val()) || 0; // Se o campo de desconto estiver vazio, considera desconto como 0
                        adicionarParcelas(sumTotal, $('#parcelasProdutos'), desconto);
                        $('#TotalProdutos').text((sumTotal * (1 - (desconto / 100))).toFixed(2)); // Aplica o desconto no valor total exibido
                        $('#TotalProdutosAplicado').val((sumTotal * (1 - (desconto / 100))).toFixed(2)); // Aplica o desconto no valor total exibido
                    } else if (tipoPagamento == 'REVENDA') {
                        $('#parcelasContainerProdutos').show();
                        $('#revendaContainerProdutos').show();
                        $('#parcelasRevendaContainerProdutos').hide();
                        $('#descontoContainerProdutos').hide();
        
                        $('#select_revendaProdutos').change(function() {
                            var desconto = parseFloat($(this).find(':selected').data('desconto'));
                            var sumTotal = parseFloat("{{ $sumTotalProdutos }}");
                            adicionarParcelas(sumTotal, $('#parcelasProdutos'), desconto);
                            $('#TotalProdutos').text((sumTotal * (1 - (desconto / 100))).toFixed(2));
                            $('#TotalProdutosAplicado').val((sumTotal * (1 - (desconto / 100))).toFixed(2)); // Aplica o desconto no valor total exibido
                        });
                    } else if (tipoPagamento == 'BOLETO_A_VISTA' || tipoPagamento == 'PIX' || tipoPagamento == 'DINHEIRO' || tipoPagamento == 'CARTAO_DEBITO') {
                        $('#parcelasContainerProdutos').hide();
                        $('#revendaContainerProdutos').hide();
                        $('#parcelasRevendaContainerProdutos').hide();
                        $('#descontoContainerProdutos').show();
        
                        var sumTotal = parseFloat("{{ $sumTotalProdutos }}"); // Substitua 1000 pelo valor total real
                        $('#TotalProdutos').text(sumTotal.toFixed(2)); // Exibe o valor total sem desconto
                        $('#TotalProdutosAplicado').val(sumTotal.toFixed(2)); 
                    } else {
                        $('#parcelasContainerProdutos').hide();
                        $('#revendaContainerProdutos').hide();
                        $('#parcelasRevendaContainerProdutos').hide();
                        $('#descontoContainerProdutos').hide();
                    }
                });
                
                $('#DescontoProdutos').keyup(function() {
                    var valorDesconto = parseFloat($(this).val());
                    var sumTotal = parseFloat("{{ $sumTotalProdutos }}"); // Substitua 1000 pelo valor total real
                    var tipoPagamento = $('#tipo_pagamentoProdutos').val();
                    if (tipoPagamento == 'BOLETO_PARCELADO' || tipoPagamento == 'CARTAO_CREDITO' || tipoPagamento == 'REVENDA') {
                        adicionarParcelas(sumTotal, $('#parcelasProdutos'), valorDesconto);
                    }
                    var totalComDesconto = sumTotal * (1 - (valorDesconto / 100));
                    $('#TotalProdutos').text((isNaN(totalComDesconto) ? sumTotal : totalComDesconto).toFixed(2)); // Aplica o desconto no valor total exibido
                    $('#TotalProdutosAplicado').val((isNaN(totalComDesconto) ? sumTotal : totalComDesconto).toFixed(2)); // Aplica o desconto no valor total exibido
                });

            });    
</script>



        <script>
            $(document).ready(function() {
                function adicionarParcelas(sumTotal, parcelasContainer, desconto) {
                    var selectParcelas = parcelasContainer;
                    selectParcelas.empty(); // Limpa as opções atuais
        
                    // Aplica o desconto se houver
                    if (desconto) {
                        sumTotal *= (1 - (desconto / 100));
                    }
        
                    // Adiciona as opções de parcelas de 1x a 12x
                    for (var i = 1; i <= 12; i++) {
                        var valorParcela = sumTotal / i;
                        var option = $('<option></option>').attr('value', i).text(i + 'x R$' + valorParcela.toFixed(2));
                        selectParcelas.append(option);
                    }
                }
        
                $('#tipo_pagamentoOrdem').change(function() {
                    var tipoPagamento = $(this).val();
                    if (tipoPagamento == 'BOLETO_PARCELADO' || tipoPagamento == 'CARTAO_CREDITO') {
                        $('#parcelasContainerOrdem').show();
                        $('#revendaContainerOrdem').hide();
                        $('#parcelasRevendaContainerOrdem').hide();
                        $('#descontoContainerOrdem').show();
        
                        var sumTotal = parseFloat("{{ $sumTotalOrdem }}"); // Substitua 1000 pelo valor total real
                        var desconto = parseFloat($('#DescontoOrdem').val()) || 0; // Se o campo de desconto estiver vazio, considera desconto como 0
                        adicionarParcelas(sumTotal, $('#parcelasOrdem'), desconto);
                        $('#TotalOrdem').text((sumTotal * (1 - (desconto / 100))).toFixed(2)); // Aplica o desconto no valor total exibido
                        $('#desconto_aplicadoOrdem').val((sumTotal * (1 - (desconto / 100))).toFixed(2)); // Aplica o desconto no valor total exibido

                    }else if (tipoPagamento == 'REVENDA') {
                        $('#parcelasContainerOrdem').show();
                        $('#revendaContainerOrdem').show();
                        $('#parcelasRevendaContainerOrdem').hide();
                        $('#descontoContainerOrdem').hide();

                        $('#select_revendaOrdem').change(function() {
                            var desconto = parseFloat($(this).find(':selected').data('desconto'));
                            var sumTotal = parseFloat("{{ $sumTotalOrdem }}");
                            adicionarParcelas(sumTotal, $('#parcelasOrdem'), desconto);
                            $('#TotalOrdem').text((sumTotal * (1 - (desconto / 100))).toFixed(2));
                            $('#desconto_aplicadoOrdem').val((sumTotal * (1 - (desconto / 100)).toFixed(2)));
                        });
                    } else if (tipoPagamento == 'BOLETO_A_VISTA' || tipoPagamento == 'PIX' || tipoPagamento == 'DINHEIRO' || tipoPagamento == 'CARTAO_DEBITO') {
                        $('#parcelasContainerOrdem').hide();
                        $('#revendaContainerOrdem').hide();
                        $('#parcelasRevendaContainerOrdem').hide();
                        $('#descontoContainerOrdem').show();
        
                        var sumTotal = parseFloat("{{ $sumTotalOrdem }}"); // Substitua 1000 pelo valor total real
                        $('#TotalOrdem').text(sumTotal.toFixed(2)); // Exibe o valor total sem desconto
                        $('#desconto_aplicadoOrdem').val(sumTotal.toFixed(2)); // Exibe o valor total sem desconto
                    } else {
                        $('#parcelasContainerOrdem').hide();
                        $('#revendaContainerOrdem').hide();
                        $('#parcelasRevendaContainerOrdem').hide();
                        $('#descontoContainerOrdem').hide();
                    }
                });
        
                $('#DescontoOrdem').keyup(function() {
                    var valorDesconto = parseFloat($(this).val());
                    var sumTotal = parseFloat("{{ $sumTotalOrdem }}"); // Substitua 1000 pelo valor total real
                    var tipoPagamento = $('#tipo_pagamentoOrdem').val();
                    if (tipoPagamento == 'BOLETO_PARCELADO' || tipoPagamento == 'CARTAO_CREDITO' || tipoPagamento == 'REVENDA') {
                        adicionarParcelas(sumTotal, $('#parcelasOrdem'), valorDesconto);
                    }
                    var totalComDesconto = sumTotal * (1 - (valorDesconto / 100));
                    $('#TotalOrdem').text((isNaN(totalComDesconto) ? sumTotal : totalComDesconto).toFixed(2)); // Aplica o desconto no valor total exibido
                    $('#desconto_aplicadoOrdem').val((isNaN(totalComDesconto) ? sumTotal : totalComDesconto).toFixed(2)); // Aplica o desconto no valor total exibido
                });
            });
        </script>
        

        
        @if (session('ativa_form'))
            <div class="modal" id="myModalProdutos" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-body p-4" style="text-align: center;">
                            <img src="{{ asset('gif/loading.gif') }}" class="mt-3" style="width: 200px;" />
                            <p style="font-size: 18px;" class="mt-3 mb-4"><strong>Processando venda...</strong></p>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                // Exibir modal após um intervalo de tempo (em milissegundos)
                setTimeout(function() {
                    var modal = document.getElementById('myModalProdutos');
                    modal.style.display = "block";

                    // Submeter o formulário após outro intervalo de tempo (após 3 segundos)
                    setTimeout(function() {
                        document.getElementById("form_vendas_produtos").submit();
                    }, 2000);
                }, 0); // 3000 milissegundos = 3 segundos
            </script>
        @endif


        @if (session('submit_formulario'))
            <div class="modal" id="myModalOrdem" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-body p-4" style="text-align: center;">
                            <img src="{{ asset('gif/loading.gif') }}" class="mt-3" style="width: 200px;" />
                            <p style="font-size: 18px;" class="mt-3 mb-4"><strong>Processando venda...</strong></p>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                // Exibir modal após um intervalo de tempo (em milissegundos)
                setTimeout(function() {
                    var modal = document.getElementById('myModalOrdem');
                    modal.style.display = "block";

                    // Submeter o formulário após outro intervalo de tempo (após 3 segundos)
                    setTimeout(function() {
                        document.getElementById("form_vendas_Ordem").submit();
                    }, 2000);
                }, 0); // 3000 milissegundos = 3 segundos
            </script>
        @endif

        <script>
            $(document).ready(function() {
                // Adiciona um evento para armazenar a aba ativa quando ela for alterada
                $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                    var target = $(e.target).attr("href"); // Tab ativada
                    localStorage.setItem('activeTab', target);
                });

                // Obtém a aba ativa armazenada no localStorage e a ativa
                var activeTab = localStorage.getItem('activeTab');
                if (activeTab) {
                    $('#myTab a[href="' + activeTab + '"]').tab('show');
                }
            });
        </script>
    @endsection