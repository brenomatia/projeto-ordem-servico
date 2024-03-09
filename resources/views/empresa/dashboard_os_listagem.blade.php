@extends('empresa.layouts.dashboard_empresa_menu')

@section('title', 'Dashboard')

@section('content')

    <div class="container mt-5">



        <div class="row">
            <div class="col-lg-3 col-6">

                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>R$ {{ number_format($sumListagem, 2, ',', '.') }}</h3>
                        <p>LISTAGEM</p>
                    </div>
                    <div class="icon">
                        <i class="fa-solid fa-file-invoice-dollar mt-3 mr-1" style="font-size: 60px;"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">

                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>R$ {{ number_format($sumTerceiro, 2, ',', '.') }}</h3>
                        <p>TERCEIROS</p>
                    </div>
                    <div class="icon">
                        <i class="fa-solid fa-people-carry-box mt-3 mr-1" style="font-size: 60px;"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">

                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>R$ {{ number_format($sumMao, 2, ',', '.') }}</h3>
                        <p>MÃO DE OBRA</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">

                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>R$ {{ number_format($sumTotal, 2, ',', '.') }}</h3>
                        <p>TOTAL OS</p>
                    </div>
                    <div class="icon">
                        <i class="fa-solid fa-filter-circle-dollar mt-3 mr-1" style="font-size: 60px;"></i>
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
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                            aria-controls="home" aria-selected="true">LISTAR PRODUTOS</a>
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
                    @if($OrdemServico->status != 'ABERTA')
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
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="container mt-4">

                        
                        
                       
  
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <form action="{{ route('dashboard_os_listar_item', ['empresa' => $empresa->name, 'id_ordem' => $id_ordem]) }}" method="POST">
                                            @csrf
                                            <label>Listar produtos:</label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" id="produto_search" placeholder="NOME/SKU" aria-label="Listar produtos" aria-describedby="button-addon2" required>
                                                <div class="input-group-append">
                                                    <button class="btn bg-primary" type="button" id="button-addon2"><i class="fa-solid fa-magnifying-glass-plus"></i></button>
                                                </div>
                                            </div>
                                            <label>Produto:</label>
                                            <select name="produto_id" id="produto_id" class="custom-select" style="margin-top: 10px;" required>
                                                <option value=""></option>
                                            </select>
                                            <input type="hidden" value="{{ $id_equipamento }}" name="id_equipamento" />
                                            <button type="submit" class="btn btn-primary mt-3 col-12" id="criar_ordem">LISTAR PRODUTO</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <form action="{{ route('dashboard_os_sub_item', ['empresa' => $empresa->name, 'id_ordem' => $id_ordem]) }}" method="POST">
                                            @csrf
                                            <label>Produto:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-append">
                                                    <button class="btn bg-purple rounded-left" type="button" id="button-addon2"><i class="fa-solid fa-cart-plus"></i></button>
                                                </div>
                                                <input type="text" class="form-control" placeholder="Digite o nome do produto" name="name_produto">
                                            </div>
                                            <label>Valor:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-append">
                                                    <button class="btn bg-purple rounded-left" type="button" id="button-addon2">R$</button>
                                                </div>
                                                <input type="text" class="form-control" placeholder="Valor de venda" name="valor_produto">
                                            </div>
                                            <input type="hidden" value="{{ $id_equipamento }}" name="id_equipamento" />
                                            <button type="submit" class="btn bg-purple col-12">SALVAR PRODUTO</button>
                                        </form>
                                    </div>
                                </div>
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
                    <div class="container mt-4">
                        <div class="card">
                            <div class="card-body">
                                <form
                                    action="{{ route('dashboard_cadastro_terceiros', ['empresa' => $empresa->name, 'id_ordem' => $id_ordem]) }}"
                                    method="POST">
                                    @csrf

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="nome_empresa">Nome da Empresa</label>
                                            <input type="text" class="form-control" id="nome_empresa"
                                                name="nome_empresa" placeholder="Nome da Empresa" required>
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
                                                            <button type="button" class="btn bg-primary"
                                                                data-toggle="modal"
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
                                                                                        <input type="text"
                                                                                            class="form-control"
                                                                                            id="nome_empresa"
                                                                                            name="nome_empresa"
                                                                                            value="{{ $terceiro->nome_empresa }}">
                                                                                    </div>

                                                                                    <div class="form-group col-md-6">
                                                                                        <label for="tipo_servico">Tipo de
                                                                                            Serviço</label>
                                                                                        <select class="form-control"
                                                                                            id="tipo_servico"
                                                                                            name="tipo_servico">
                                                                                            <option value=""
                                                                                                disabled>Selecione o Tipo de
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
                                                                                        <input type="number"
                                                                                            class="form-control"
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
                                                            <button type="button" class="btn btn-danger"
                                                                data-toggle="modal"
                                                                data-target="#confirmDeleteModal{{ $terceiro->id }}"
                                                                data-toggle="tooltip" title="Excluir terceiro">
                                                                <i class="fa-solid fa-trash-can"></i>
                                                            </button>
                                                            <div class="modal fade"
                                                                id="confirmDeleteModal{{ $terceiro->id }}" tabindex="-1"
                                                                role="dialog"
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
                                                                            <button type="button"
                                                                                class="btn btn-secondary"
                                                                                data-dismiss="modal">Cancelar</button>
                                                                            <form
                                                                                action="{{ route('dashboard_deletar_terceiros', ['empresa' => $empresa->name, 'id_ordem' => $id_ordem, 'id_terceiro' => $terceiro->id]) }}"
                                                                                method="POST"
                                                                                style="display: inline-block;">
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
                    </div>
                </div>
                <div class="tab-pane fade" id="mao" role="tabpanel" aria-labelledby="mao-tab">
                    <div class="container mt-4">
                        <div class="card">
                            <div class="card-body">
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
                                                             <button type="button" class="btn btn-danger"
                                                             data-toggle="modal"
                                                             data-target="#confirmDeleteModal{{ $mao->id }}"
                                                             data-toggle="tooltip" title="Excluir terceiro">
                                                             <i class="fa-solid fa-trash-can"></i>
                                                         </button>
                                                         <div class="modal fade"
                                                             id="confirmDeleteModal{{ $mao->id }}" tabindex="-1"
                                                             role="dialog"
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
                                                                         <button type="button"
                                                                             class="btn btn-secondary"
                                                                             data-dismiss="modal">Cancelar</button>
                                                                         <form
                                                                             action="{{ route('dashboard_deletar_mao_de_obra', ['empresa' => $empresa->name, 'id_ordem' => $id_ordem, 'id' => $mao->id]) }}"
                                                                             method="POST"
                                                                             style="display: inline-block;">
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
                    </div>
                </div>
                <div class="tab-pane fade" id="obs" role="tabpanel" aria-labelledby="obs-tab">
                    <div class="container mt-4">
                    
                        <form action="{{ route('dashboard_anotacao_os',  ['empresa' => $empresa->name, 'id_ordem' => $id_ordem]) }}" method="POST" id="formAnotacoes">
                            @csrf
                            <div class="card">
                                <div class="card-body">
                                    <label for="anotacoes">Anotações:</label>
                                    <textarea class="form-control mb-2" style="text-align: left;" id="anotacoes" name="anotacoes" placeholder="Anotações da ordem de serviços" rows="5">@if($anotacaoConteudo){{$anotacaoConteudo}}@endif</textarea>
                                    <input type="hidden" value="{{ $id_equipamento }}" name="id_equipamento" />
                                    <button class="btn bg-primary col-12 mb-3 mt-2">SALVAR ANOTAÇÕES</button>
                                </div>
                            </div>
                        </form>
                        
               
                    </div>
                </div>
            </div>
        </div>


    </div>










    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


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
