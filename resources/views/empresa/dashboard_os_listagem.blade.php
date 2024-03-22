@extends('empresa.layouts.dashboard_empresa_menu')

@section('title', 'Dashboard')

@section('content')

    <div class="container mt-5 col-11">



        <div class="row">
            <div class="col-lg-3 col-md-6">

                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>R$ {{ number_format($sumListagem, 2, ',', '.') }}</h3>
                        <p>LISTAGEM</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">

                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>R$ {{ number_format($sumTerceiro, 2, ',', '.') }}</h3>
                        <p>TERCEIROS</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">

                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>R$ {{ number_format($sumMao, 2, ',', '.') }}</h3>
                        <p>MÃO DE OBRA</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">

                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>R$
                            {{ is_null($equipamento->valorComDesconto) ? number_format($sumTotal, 2, ',', '') : number_format($equipamento->valorComDesconto, 2, ',', '') }}
                        </h3>
                        <p>TOTAL OS</p>
                    </div>
                </div>
            </div>

        </div>









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
            <div class="card-header bg-light">
                <ul class="nav nav-tabs card-header-tabs mb-1" id="myTab" role="tablist">

                    <li class="nav-item mr-3">

                        <select class="form-control status-select mb-2 mr-2" id="status_{{ $equipamento->id }}"
                            style="border: 1px solid #007BFF;">
                            <option value="AGUARDANDO ORÇAMENTO"
                                {{ $equipamento->status == 'AGUARDANDO ORÇAMENTO' ? 'selected' : '' }}>AGUARDANDO ORÇAMENTO
                            </option>
                            <option value="AGUARDANDO AUTORIZAÇÃO"
                                {{ $equipamento->status == 'AGUARDANDO AUTORIZAÇÃO' ? 'selected' : '' }}>AGUARDANDO
                                AUTORIZAÇÃO</option>
                            <option value="AUTORIZADO" {{ $equipamento->status == 'AUTORIZADO' ? 'selected' : '' }}>
                                AUTORIZADO</option>
                            <option value="AGUARDANDO PEÇAS"
                                {{ $equipamento->status == 'AGUARDANDO PEÇAS' ? 'selected' : '' }}>AGUARDANDO PEÇAS
                            </option>
                            <option value="NÃO AUTORIZADO"
                                {{ $equipamento->status == 'NÃO AUTORIZADO' ? 'selected' : '' }}>NÃO AUTORIZADO</option>
                            <option value="PRONTO" {{ $equipamento->status == 'PRONTO' ? 'selected' : '' }}>PRONTO</option>
                            <option value="ENTREGUE" {{ $equipamento->status == 'ENTREGUE' ? 'selected' : '' }}>ENTREGUE
                            </option>
                        </select>


                    </li>
                    @if ($equipamento->status == 'NÃO AUTORIZADO')

                        <li class="nav-item">
                            <a class="nav-link" id="naoTAB-tab" data-toggle="tab" href="#naoTAB" role="tab"
                                aria-controls="naoTAB" aria-selected="true">NÃO AUTORIZADO</a>
                        </li>
                    @else
                        @if ( $equipamento->status == 'AGUARDANDO ORÇAMENTO' || $equipamento->status == 'AGUARDANDO AUTORIZAÇÃO' || $equipamento->status == 'PRONTO' || $equipamento->status == 'ENTREGUE')

                            <li class="nav-item">
                                <a class="nav-link @if ($equipamento->status == 'NÃO AUTORIZADO') @else active @endif" id="home-tab"
                                    data-toggle="tab" href="#home" role="tab" aria-controls="home"
                                    aria-selected="true">LISTAR PRODUTOS</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                                    aria-controls="profile" aria-selected="false">TERCEIROS</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="mao-tab" data-toggle="tab" href="#mao" role="tab"
                                    aria-controls="mao" aria-selected="false">MÃO DE OBRA</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="obs-tab" data-toggle="tab" href="#obs" role="tab"
                                    aria-controls="obs" aria-selected="false">ANOTAÇÕES</a>
                            </li>
                            @if($equipamento->status == 'PRONTO' || $equipamento->status == 'ENTREGUE')
                            <li class="nav-item">
                                <a class="nav-link" id="aguardando-tab" data-toggle="tab" href="#aguardando"
                                    role="tab" aria-controls="aguardando" aria-selected="false">AGUARDANDO PEÇAS</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pagamento-tab" data-toggle="tab" href="#pagamento"
                                    role="tab" aria-controls="pagamento" aria-selected="false">PAGAMENTO</a>
                            </li>
                            @endif
                        @else

                            @if ($equipamento->status == 'AGUARDANDO PEÇAS')
                                <li class="nav-item">
                                    <a class="nav-link" id="aguardando-tab" data-toggle="tab" href="#aguardando"
                                        role="tab" aria-controls="aguardando" aria-selected="false">AGUARDANDO PEÇAS</a>
                                </li>
                            @endif

                            @if ($equipamento->status == 'AUTORIZADO')
                                <li class="nav-item">
                                    <a class="nav-link" id="pagamento-tab" data-toggle="tab" href="#pagamento"
                                        role="tab" aria-controls="pagamento" aria-selected="false">PAGAMENTO</a>
                                </li>
                            @endif

                        @endif

                    @endif

                    @if ($OrdemServico->status != 'ABERTA')
                        <div class="nav-item ml-2">
                            <a class="btn bg-danger" class="nav-link"
                                href="{{ route('dashboard_buscar_ordem', ['empresa' => $empresa->name, 'search_os' => $id_ordem]) }}"><i
                                    class="fa-solid fa-right-from-bracket"></i> VOLTAR</a>
                        </div>
                    @else
                        <div class="nav-item ml-2">
                            <a class="btn bg-primary" class="nav-link"
                                href="{{ route('dashboard_ordem_servico', ['empresa' => $empresa->name]) }}"><i
                                    class="fa-solid fa-right-from-bracket"></i> VOLTAR</a>
                        </div>
                    @endif

                </ul>
            </div>
            <div class="tab-content p-3" id="myTabContent">
                <div class="tab-pane fade @if (
                    $equipamento->status == 'NÃO AUTORIZADO' ||
                        $equipamento->status == 'AUTORIZADO' ||
                        $equipamento->status == 'AGUARDANDO PEÇAS') @else show active @endif" id="home"
                    role="tabpanel" aria-labelledby="home-tab">
                    <div class="container mt-5 mb-5">

                        <div class="row">
                            <div class="col-md-5">

                                <form
                                    action="{{ route('dashboard_os_listar_item', ['empresa' => $empresa->name, 'id_ordem' => $id_ordem]) }}"
                                    method="POST">
                                    @csrf
                                    <label>Listar produtos:</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="produto_search"
                                            placeholder="NOME/SKU" aria-label="Listar produtos"
                                            aria-describedby="button-addon2" required>
                                        <div class="input-group-append">
                                            <button class="btn bg-primary" type="button" id="button-addon2"><i
                                                    class="fa-solid fa-magnifying-glass-plus"></i></button>
                                        </div>
                                    </div>
                                    <label>Produto:</label>
                                    <select name="produto_id" id="produto_id" class="custom-select" required>
                                        <option value=""></option>
                                    </select>
                                    <input type="hidden" value="{{ $id_equipamento }}" name="id_equipamento" />
                                    <button type="submit" class="btn btn-primary mt-3 col-12" id="criar_ordem">LISTAR
                                        PRODUTO</button>
                                </form>

                            </div>
                            <div class="col-md-2"></div>
                            <div class="col-md-5">

                                <form
                                    action="{{ route('dashboard_os_sub_item', ['empresa' => $empresa->name, 'id_ordem' => $id_ordem]) }}"
                                    method="POST">
                                    @csrf
                                    <label>Produto:</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-append">
                                            <button class="btn bg-purple rounded-left" type="button"
                                                id="button-addon2"><i class="fa-solid fa-cart-plus"></i></button>
                                        </div>
                                        <input type="text" class="form-control" placeholder="Digite o nome do produto"
                                            name="name_produto">
                                    </div>
                                    <label>Valor:</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-append">
                                            <button class="btn bg-purple rounded-left" type="button"
                                                id="button-addon2">R$</button>
                                        </div>
                                        <input type="text" class="form-control" placeholder="Valor de venda"
                                            name="valor_produto">
                                    </div>
                                    <input type="hidden" value="{{ $id_equipamento }}" name="id_equipamento" />
                                    <button type="submit" class="btn bg-purple col-12">SALVAR PRODUTO</button>
                                </form>

                            </div>
                        </div>





                        @if ($carrinhos->isNotempty())
                            <div class="table-responsive mt-5">
                                <table class="table table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center">#SKU</th>
                                            <th class="text-center">PRODUTO</th>
                                            <th class="text-center">QUANTIDADE</th>
                                            <th class="text-center">VALOR</th>
                                            <th class="text-center">OPÇÕES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($carrinhos as $carrinho)
                                            <tr>
                                                <td class="align-middle text-center">{{ $carrinho->sku }}</td>
                                                <td class="align-middle text-center">{{ $carrinho->produto }}</td>
                                                <td class="align-middle text-center">{{ $carrinho->qtd_produto }}</td>
                                                <td class="align-middle text-center">R$ {{ $carrinho->valor }}</td>
                                                <td class="align-middle text-center">

                                                    <!-- APAGAR PRODUTO -->
                                                    <button type="button" class="btn btn-danger" data-toggle="modal"
                                                        data-target="#confirmDeleteModal{{ $carrinho->id }}"
                                                        data-toggle="tooltip" title="Excluir itens do carrinho">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>

                                                    <div class="modal fade" id="confirmDeleteModal{{ $carrinho->id }}"
                                                        tabindex="-1" role="dialog"
                                                        aria-labelledby="confirmDeleteModalLabel{{ $carrinho->id }}"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="confirmDeleteModalLabel{{ $carrinho->id }}">
                                                                        Confirmar Exclusão</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Fechar">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Tem certeza de que deseja excluir o item
                                                                    "{{ $carrinho->produto }}" do carrinho ?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Cancelar</button>
                                                                    <form
                                                                        action="{{ route('dashboard_os_deletar_item', ['empresa' => $empresa->name, 'id_ordem' => $id_ordem, 'id' => $carrinho->id]) }}"
                                                                        method="POST" style="display: inline-block;">
                                                                        @csrf
                                                                        <button type="submit"
                                                                            class="btn btn-danger">Excluir</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- FIM APAGAR PRODUTO -->



                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif






                    </div>
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="container mt-5 mb-5">

                        <form
                            action="{{ route('dashboard_cadastro_terceiros', ['empresa' => $empresa->name, 'id_ordem' => $id_ordem]) }}"
                            method="POST">
                            @csrf

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="nome_empresa">Nome da Empresa</label>
                                    <input type="text" class="form-control" id="nome_empresa" name="nome_empresa"
                                        placeholder="Nome da Empresa" required>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="tipo_servico">Tipo de Serviço</label>
                                    <select class="form-control" id="tipo_servico" name="tipo_servico" required>
                                        <option value="" selected disabled>Selecione o Tipo de Serviço
                                        </option>
                                        <option value="Torno">Torno</option>
                                        <option value="Solda">Solda</option>
                                        <option value="Limpeza">Limpeza</option>
                                        <option value="Outros">Outros</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="valor">Valor a ser Cobrado</label>
                                    <input type="number" class="form-control" id="valor" name="valor"
                                        step="any" placeholder="Valor" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="observacoes">Observações</label>
                                    <textarea class="form-control" id="observacoes" name="obs" placeholder="Observações" rows="3"></textarea>
                                </div>
                            </div>
                            <input type="hidden" value="{{ $id_equipamento }}" name="id_equipamento" />
                            <button type="submit" class="btn btn-primary col-12">CADASTRAR TERCEIRO</button>
                        </form>


                        @if ($terceiros->isNotEmpty())
                            <div class="table-responsive mt-5">
                                <table class="table table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center">EMPRESA</th>
                                            <th class="text-center">SERVIÇO PRESTADO</th>
                                            <th class="text-center">VALOR</th>
                                            <th class="text-center">OBSERVAÇÕES</th>
                                            <th class="rounded-right text-center">OPÇÕES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Aqui você pode usar seu foreach para preencher dinamicamente os dados -->
                                        @foreach ($terceiros as $terceiro)
                                            <tr>
                                                </td>
                                                <td class="align-middle text-center">{{ $terceiro->nome_empresa }}
                                                </td>
                                                <td class="align-middle text-center">{{ $terceiro->tipo_servico }}
                                                </td>
                                                <td class="align-middle text-center">R$ {{ $terceiro->valor }}
                                                </td>
                                                <td class="align-middle text-center">{{ $terceiro->obs }}</td>
                                                <td class="align-middle text-center">

                                                    <!-- VER TERCEIRO -->
                                                    <button type="button" class="btn bg-primary" data-toggle="modal"
                                                        data-target="#dadosterceiro{{ $terceiro->id }}"
                                                        data-toggle="tooltip" title="Ver dados">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </button>
                                                    <div class="modal fade" id="dadosterceiro{{ $terceiro->id }}"
                                                        tabindex="-1" role="dialog"
                                                        aria-labelledby="dadosterceiro{{ $terceiro->id }}"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="dadosterceiro{{ $terceiro->id }}">
                                                                        {{ $terceiro->nome_empresa }}</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Fechar">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body" style="text-align: left;">


                                                                    <form
                                                                        action="{{ route('dashboard_atualizar_terceiros', ['empresa' => $empresa->name, 'id_ordem' => $id_ordem, 'id_terceiro' => $terceiro->id]) }}"
                                                                        method="POST">
                                                                        @csrf

                                                                        <div class="form-row">
                                                                            <div class="form-group col-md-6">
                                                                                <label for="nome_empresa">Nome da
                                                                                    Empresa</label>
                                                                                <input type="text" class="form-control"
                                                                                    id="nome_empresa" name="nome_empresa"
                                                                                    value="{{ $terceiro->nome_empresa }}">
                                                                            </div>

                                                                            <div class="form-group col-md-6">
                                                                                <label for="tipo_servico">Tipo de
                                                                                    Serviço</label>
                                                                                <select class="form-control"
                                                                                    id="tipo_servico" name="tipo_servico">
                                                                                    <option value="" disabled>
                                                                                        Selecione o Tipo de
                                                                                        Serviço</option>
                                                                                    <option value="Torno"
                                                                                        {{ $terceiro->tipo_servico == 'Torno' ? 'selected' : '' }}>
                                                                                        Torno</option>
                                                                                    <option value="Solda"
                                                                                        {{ $terceiro->tipo_servico == 'Solda' ? 'selected' : '' }}>
                                                                                        Solda</option>
                                                                                    <option value="Limpeza"
                                                                                        {{ $terceiro->tipo_servico == 'Limpeza' ? 'selected' : '' }}>
                                                                                        Limpeza</option>
                                                                                    <option value="Outros"
                                                                                        {{ $terceiro->tipo_servico == 'Outros' ? 'selected' : '' }}>
                                                                                        Outros</option>
                                                                                </select>

                                                                            </div>
                                                                        </div>

                                                                        <div class="form-row">
                                                                            <div class="form-group col-md-12">
                                                                                <label for="valor">Valor a ser
                                                                                    Cobrado</label>
                                                                                <input type="number" class="form-control"
                                                                                    id="valor" name="valor"
                                                                                    value="{{ $terceiro->valor }}"
                                                                                    step="any">
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-row">
                                                                            <div class="form-group col-md-12">
                                                                                <label
                                                                                    for="observacoes">Observações</label>
                                                                                <textarea class="form-control" id="observacoes" name="obs" rows="3">{{ $terceiro->obs }}</textarea>
                                                                            </div>
                                                                        </div>

                                                                        <button type="submit"
                                                                            class="btn btn-primary col-12">ATUALIZAR
                                                                            TERCEIRO</button>
                                                                    </form>



                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- FIM VER TERCEIRO -->

                                                    <!-- EXCLUIR TERCEIRO -->
                                                    <button type="button" class="btn btn-danger" data-toggle="modal"
                                                        data-target="#confirmDeleteModal{{ $terceiro->id }}"
                                                        data-toggle="tooltip" title="Excluir terceiro">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                    <div class="modal fade" id="confirmDeleteModal{{ $terceiro->id }}"
                                                        tabindex="-1" role="dialog"
                                                        aria-labelledby="confirmDeleteModalLabel{{ $terceiro->id }}"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="confirmDeleteModalLabel{{ $terceiro->id }}">
                                                                        Confirmar Exclusão</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Fechar">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Tem certeza de que deseja excluir o terceiro
                                                                    "{{ $terceiro->nome_empresa }}"?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Cancelar</button>
                                                                    <form
                                                                        action="{{ route('dashboard_deletar_terceiros', ['empresa' => $empresa->name, 'id_ordem' => $id_ordem, 'id_terceiro' => $terceiro->id]) }}"
                                                                        method="POST" style="display: inline-block;">
                                                                        @csrf
                                                                        <button type="submit"
                                                                            class="btn btn-danger">Excluir</button>
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
                        @endif




                    </div>
                </div>
                <div class="tab-pane fade" id="mao" role="tabpanel" aria-labelledby="mao-tab">
                    <div class="container mt-5 mb-5">

                        <form
                            action="{{ route('dashboard_cadastro_mao_de_obra', ['empresa' => $empresa->name, 'id_ordem' => $id_ordem]) }}"
                            method="POST">
                            @csrf

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="nome_empresa">Tipo de mão de obra</label>
                                    <input type="text" class="form-control" id="mao_obra" name="mao_obra"
                                        placeholder="Tipo mão de obra" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="valor">Valor a ser Cobrado</label>
                                    <input type="number" class="form-control" id="valor" name="valor"
                                        step="any" placeholder="Valor" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="observacoes">Observações</label>
                                    <textarea class="form-control" id="observacoes" name="obs" placeholder="Observações" rows="3"></textarea>
                                </div>
                            </div>
                            <input type="hidden" value="{{ $id_equipamento }}" name="id_equipamento" />
                            <button type="submit" class="btn btn-primary col-12">CADASTRAR MÃO DE OBRA</button>
                        </form>



                        @if ($maodeobras->isNotEmpty())
                            <div class="table-responsive mt-5">
                                <table class="table table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="rounded-left text-center">TIPO</th>
                                            <th class="text-center">VALOR</th>
                                            <th class="text-center">OBSERVAÇÕES</th>
                                            <th class="rounded-right text-center">OPÇÕES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Aqui você pode usar seu foreach para preencher dinamicamente os dados -->
                                        @foreach ($maodeobras as $mao)
                                            <tr>
                                                <td class="align-middle text-center">{{ $mao->tipo }}</td>
                                                <td class="align-middle text-center">R$ {{ $mao->valor }}</td>
                                                <td class="align-middle text-center">{{ $mao->obs }}</td>
                                                <td class="align-middle text-center">

                                                    <!-- EXCLUIR TERCEIRO -->
                                                    <button type="button" class="btn btn-danger" data-toggle="modal"
                                                        data-target="#confirmDeleteModal{{ $mao->id }}"
                                                        data-toggle="tooltip" title="Excluir terceiro">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                    <div class="modal fade" id="confirmDeleteModal{{ $mao->id }}"
                                                        tabindex="-1" role="dialog"
                                                        aria-labelledby="confirmDeleteModalLabel{{ $mao->id }}"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="confirmDeleteModalLabel{{ $mao->id }}">
                                                                        Confirmar Exclusão</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Fechar">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Tem certeza de que deseja excluir o registro
                                                                    "{{ $mao->tipo }}"?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Cancelar</button>
                                                                    <form
                                                                        action="{{ route('dashboard_deletar_mao_de_obra', ['empresa' => $empresa->name, 'id_ordem' => $id_ordem, 'id' => $mao->id]) }}"
                                                                        method="POST" style="display: inline-block;">
                                                                        @csrf
                                                                        <button type="submit"
                                                                            class="btn btn-danger">Excluir</button>
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
                        @endif



                    </div>
                </div>

                <div class="tab-pane fade" id="aguardando" role="tabpanel" aria-labelledby="aguardando-tab">
                    <div class="container mt-5 mb-5">

                        <form
                            action="{{ route('dashboard_aguardando_pecas', ['empresa' => $empresa->name, 'id_ordem' => $id_ordem, 'id_equipamento' => $equipamento->id]) }}"
                            method="POST" id="FORMAGUARDANDOPEÇAS">
                            @csrf

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="nome_empresa">Pedido em:</label>
                                    <input type="date" class="form-control" id="dataInicial" name="DataInicial"
                                        value="{{ $equipamento->pedidoPecas ?? '' }}" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="valor">Entrega Prevista:</label>
                                    <input type="date" class="form-control" id="DataFinal" name="DataFinal"
                                        value="{{ $equipamento->entregaPecas ?? '' }}" required>
                                </div>
                            </div>

                            <textarea class="form-control mb-2" style="text-align: left;" id="aguardando_obs" name="aguardando_obs" placeholder="Observações" rows="5">@if ($equipamento->pedidoOBS){{ $equipamento->pedidoOBS }}@endif</textarea>
                            <button class="btn bg-primary col-12 mb-3 mt-2">SALVAR DADOS</button>
                        </form>


                    </div>
                </div>

                <div class="tab-pane fade" id="naoTAB" role="tabpanel" aria-labelledby="naoTAB-tab">

                    <div class="container mt-5 mb-5">


                        <form action="{{ route('dashboard_substatus', ['empresa' => $empresa->name, 'id_ordem' => $id_ordem, 'id_equipamento' => $equipamento->id]) }}" method="POST" id="myForm1">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 offset-md-3">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="checkbox1" name="checkbox" value="ITEM PARA DESCARTE" {{ $equipamento->substatus == 'ITEM PARA DESCARTE' ? 'checked' : '' }} {{ empty($equipamento->substatus) ? '' : 'disabled' }}>
                                            <label class="custom-control-label" for="checkbox1">
                                                ITEM PARA DESCARTE
                                            </label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="checkbox2" name="checkbox" value="ITEM ENTREGUE PARA CLIENTE" {{ $equipamento->substatus == 'ITEM ENTREGUE PARA CLIENTE' ? 'checked' : '' }} {{ empty($equipamento->substatus) ? '' : 'disabled' }}>
                                            <label class="custom-control-label" for="checkbox2">
                                                ITEM ENTREGUE PARA CLIENTE
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="observacoes">OBSERVAÇÕES:</label>
                                        <textarea class="form-control" id="observacoes" name="obs_naoaut" placeholder="Observações" rows="3"{{ empty($equipamento->obs_naoautorizado) ? '' : ' disabled' }}>@if($equipamento->obs_naoautorizado){{ $equipamento->obs_naoautorizado }}@endif</textarea>
                                            
                                        

                                    </div>
                                    @if(!empty($equipamento->obs_naoautorizado) || !empty($equipamento->substatus))@else
                                        <button type="submit" class="btn bg-primary col-12" id="submitButton">SALVAR DADOS</button>
                                    @endif
                                </div>
                            </div>
                        </form>
                        
                        <script>
                            document.getElementById("myForm1").addEventListener("submit", function(event){
                                event.preventDefault(); // Prevenir envio automático do formulário
                                
                                // Exibir alerta de confirmação
                                if(confirm("Tem certeza que deseja salvar os dados?")) {
                                    this.submit(); // Enviar formulário se confirmado
                                }
                            });
                        </script>
                        
                        






                    </div>



                </div>
                <div class="tab-pane fade" id="obs" role="tabpanel" aria-labelledby="obs-tab">
                    <div class="container mt-5 mb-5">

                        <form
                            action="{{ route('dashboard_anotacao_os', ['empresa' => $empresa->name, 'id_ordem' => $id_ordem]) }}"
                            method="POST" id="formAnotacoes">
                            @csrf

                            <label for="anotacoes">Anotações:</label>
                            <textarea class="form-control mb-2" style="text-align: left;" id="anotacoes" name="anotacoes" placeholder="Anotações da ordem de serviços" rows="5">@if ($anotacaoConteudo){{ $anotacaoConteudo }}@endif</textarea>
                            
                            
                            <input type="hidden" value="{{ $id_equipamento }}" name="id_equipamento" />
                            <button class="btn bg-primary col-12 mb-3 mt-2">SALVAR ANOTAÇÕES</button>

                        </form>


                    </div>
                </div>

                <div class="tab-pane fade" id="pagamento" role="tabpanel" aria-labelledby="pagamento-tab">



                    <div class="container mt-5 mb-5">
                        <form
                            action="{{ route('dashboard_processa_item', ['empresa' => $empresa->name, 'id_ordem' => $id_ordem, 'id_equipamento' => $equipamento->id]) }}"
                            method="POST">
                            @csrf
                            <input type="hidden" name="valor_total" value="{{ $sumTotal }}" />
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="total">Total</label>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text">R$</span>
                                        </div>
                                        <input type="text" class="form-control" name="total" id="total"
                                            value="{{ is_null($equipamento->valorComDesconto) ? $sumTotal : $equipamento->valorComDesconto }}"
                                            readonly>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="DescontoProdutos">Desconto (%)</label>
                                    <div class="input-group">

                                        <input type="text" class="form-control" name="descontoProdutos"
                                            id="DescontoProdutos" placeholder="Digite aqui o desconto"
                                            value="{{ !empty($equipamento->desconto) ? $equipamento->desconto : '' }}">

                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="tipo_pagamentoProdutos">Tipo de Pagamento</label>
                                    <select class="form-control" id="tipo_pagamentoProdutos"
                                        name="tipo_pagamentoProdutos">
                                        <option value="">Selecione um meio de pagamento</option>
                                        <option value="PIX"
                                            {{ $equipamento->MeioPagamento == 'PIX' ? 'selected' : '' }}>PIX</option>
                                        <option value="DINHEIRO"
                                            {{ $equipamento->MeioPagamento == 'DINHEIRO' ? 'selected' : '' }}>DINHEIRO
                                        </option>
                                        <option value="CARTÃO DE CRÉDITO"
                                            {{ $equipamento->MeioPagamento == 'CARTÃO DE CRÉDITO' ? 'selected' : '' }}>
                                            CARTÃO DE CRÉDITO</option>
                                        <option value="CARTÃO DE DÉBITO"
                                            {{ $equipamento->MeioPagamento == 'CARTÃO DE DÉBITO' ? 'selected' : '' }}>
                                            CARTÃO DE DÉBITO</option>
                                        <option value="BOLETO A VISTA"
                                            {{ $equipamento->MeioPagamento == 'BOLETO A VISTA' ? 'selected' : '' }}>BOLETO
                                            A VISTA</option>
                                        <option value="BOLETO PARCELADO"
                                            {{ $equipamento->MeioPagamento == 'BOLETO PARCELADO' ? 'selected' : '' }}>
                                            BOLETO PARCELADO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="troco">Troco</label>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text">R$</span>
                                        </div>
                                        <input type="text" class="form-control" name="troco" id="troco"
                                            value="{{ !empty($equipamento->valorTroco) ? $equipamento->valorTroco : '' }}"
                                            readonly>
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="parcelas">Parcelas</label>
                                    @if (isset($equipamento->parcelaTotal) && isset($equipamento->valorParcelas))
                                        <span class="text-green ml-2">{{ $equipamento->parcelaTotal }}x R$
                                            {{ number_format($equipamento->valorParcelas, 2, ',', '') }}</span>
                                    @endif

                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fa-solid fa-list"></i></span>
                                        </div>
                                        <select class="form-control" name="parcelas" id="parcelas">
                                            <!-- Options will be added dynamically -->
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="valorPago">Valor Pago</label>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text">R$</span>
                                        </div>

                                        <input type="text" class="form-control" name="valorPago" id="valorPago"
                                            placeholder="Digite o valor pago"
                                            value="{{ isset($equipamento->valorPago) ? $equipamento->valorPago : '' }}">

                                    </div>
                                </div>
                            </div>
                            @if (
                                $equipamento->valorComDesconto ||
                                    $equipamento->MeioPagamento ||
                                    $equipamento->valorTroco ||
                                    $equipamento->parcelaTotal ||
                                    $equipamento->valorParcelas ||
                                    $equipamento->valorPago)
                                <p class="text-center text-danger">Este item já foi processado anteriormente.</p>
                            @else
                                <button type="submit" class="btn bg-primary col-12 mb-5">PROCESSAR ITEM</button>
                            @endif
                        </form>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    checkboxes.forEach(function(otherCheckbox) {
                        if (otherCheckbox !== checkbox) {
                            otherCheckbox.checked = false;
                        }
                    });
                });
            });
        });
    </script>

    <script>
        document.getElementById('DescontoProdutos').addEventListener('keyup', function() {
            // Obtém o valor total atual
            var sumTotal = parseFloat('{{ $sumTotal }}');

            // Obtém o valor do desconto ou considera 0 se o campo estiver vazio
            var desconto = parseFloat(this.value.replace(',', '.')) || 0;

            // Calcula o novo total com desconto
            var totalComDesconto = sumTotal * (1 - (desconto / 100));

            // Atualiza o valor total exibido
            document.getElementById('total').value = totalComDesconto.toFixed(2);

            // Atualiza o valor pago com o total com desconto
            document.getElementById('valorPago').value = totalComDesconto.toFixed(2);

            // Calcula e atualiza o troco
            calcularTroco();

            // Atualiza as opções de parcelamento
            atualizarParcelas(totalComDesconto);
        });

        // Função para calcular e atualizar o troco
        function calcularTroco() {
            var total = parseFloat(document.getElementById('total').value.replace(/[^\d.-]/g, ''));
            var valorPago = parseFloat(document.getElementById('valorPago').value.replace(/[^\d.-]/g, ''));
            if (!isNaN(valorPago)) {
                var troco = valorPago - total;
                document.getElementById('troco').value = troco.toFixed(2);
            } else {
                // Se o valorPago for null, definir o troco como null também
                document.getElementById('troco').value = null;
            }
        }

        // Função para atualizar as opções de parcelamento
        function atualizarParcelas(total) {
            var meioPagamento = document.getElementById('tipo_pagamentoProdutos').value;
            var selectParcelas = $('#parcelas');
            selectParcelas.empty(); // Limpa as opções atuais

            // Adiciona as opções de parcelas apenas se o meio de pagamento for "CARTAO CREDITO" ou "BOLETO PARCELADO"
            if (meioPagamento === 'CARTÃO DE CRÉDITO' || meioPagamento === 'BOLETO PARCELADO') {
                for (var i = 1; i <= 12; i++) {
                    var valorParcela = total / i;
                    var option = $('<option></option>').attr('value', i).text(i + 'x R$' + valorParcela.toFixed(2));
                    selectParcelas.append(option);
                }
                // Exibe o campo de parcelas
                $('#parcelasContainer').show();
            } else {
                // Se o meio de pagamento não for "CARTAO CREDITO" ou "BOLETO PARCELADO", esconde o campo de parcelas
                $('#parcelasContainer').hide();
            }
        }

        // Evento para atualizar o troco quando o valor pago é alterado
        document.getElementById('valorPago').addEventListener('keyup', calcularTroco);

        // Evento para atualizar as parcelas quando o método de pagamento é alterado
        $('#tipo_pagamentoProdutos').change(function() {
            var totalComDesconto = parseFloat(document.getElementById('total').value.replace(/[^\d.-]/g, ''));
            atualizarParcelas(totalComDesconto);
        });

        // Chamada inicial para atualizar as parcelas com base no método de pagamento inicial
        $(document).ready(function() {
            var totalComDescontoInicial = parseFloat(document.getElementById('total').value.replace(/[^\d.-]/g,
            ''));
            atualizarParcelas(totalComDescontoInicial);
        });
    </script>



    <script>
        $(document).ready(function() {
            $('.status-select').change(function() {
                var novoStatus = $(this).val();
                var equipamentoId = $(this).attr('id').replace('status_', '');
                var empresaNome = '{{ $empresa->name }}';
                var Idordem = '{{ $id_ordem ? $id_ordem : '' }}';;
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




    <script>
        $(document).ready(function() {
            $('#produto_search').keyup(function() {
                var query = $(this).val();
                if (query != '') {
                    $.ajax({
                        url: "{{ route('dashboard_ordem_buscar_produto', ['empresa' => $empresa->name, 'id_ordem' => $id_ordem]) }}",
                        method: "GET",
                        data: {
                            query: query
                        },
                        success: function(data) {
                            $('#produto_id').html(data);
                        }
                    });
                }
            });

        });
    </script>

    <script>
        // Função para formatar o nome completo com a primeira letra de cada palavra em maiúsculo
        $('#nome_empresa').on('input', function() {
            var nomeCompleto = $(this).val().toLowerCase().replace(/(?:^|\s)\w/g, function(letter) {
                return letter.toUpperCase();
            });
            $(this).val(nomeCompleto);
        });

        // Função para formatar o nome completo com a primeira letra de cada palavra em maiúsculo
        $('#observacoes').on('input', function() {
            var nomeCompleto = $(this).val().toLowerCase().replace(/(?:^|\s)\w/g, function(letter) {
                return letter.toUpperCase();
            });
            $(this).val(nomeCompleto);
        });
    </script>

    <script>
        // Armazena o ID do último nav item ativo no localStorage
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.nav-tabs .nav-link');
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    localStorage.setItem('lastActiveTab', this.getAttribute('id'));
                });
            });

            // Restaura o último nav item ativo
            const lastActiveTabId = localStorage.getItem('lastActiveTab');
            if (lastActiveTabId) {
                const lastActiveTab = document.getElementById(lastActiveTabId);
                if (lastActiveTab) {
                    lastActiveTab.click();
                }
            }
        });
    </script>
@endsection
