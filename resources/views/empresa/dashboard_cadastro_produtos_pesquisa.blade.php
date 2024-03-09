@extends('empresa.layouts.dashboard_empresa_menu')

@section('title', 'Dashboard')

@section('content')
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


        <form action="{{ route('dashboard_produtos_cadastro', ['empresa' => $empresa->name]) }}" method="POST">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="inputSKU">SKU</label>
                    <input type="text" class="form-control" id="inputSKU" placeholder="Digite o SKU" name="produto_sku">
                </div>
                <div class="form-group col-md-4">
                    <label for="inputDesc">Descrição</label>
                    <input type="text" class="form-control" id="inputDesc" placeholder="Digite a descrição"
                        name="produto_descricao">
                </div>
                <div class="form-group col-md-4">
                    <label for="inputNCM">NCM</label>
                    <input type="text" class="form-control" id="inputNCM" placeholder="Digite o NCM" name="produto_ncm">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="inputCST">CST</label>
                    <input type="text" class="form-control" id="inputCST" placeholder="Digite o CST" name="produto_cst">
                </div>
                <div class="form-group col-md-3">
                    <label for="inputLetra">Letra</label>
                    <input type="text" class="form-control" id="inputLetra" placeholder="Digite a letra"
                        name="produto_letra">
                </div>
                <div class="form-group col-md-3">
                    <label for="inputPIS">PIS</label>
                    <input type="text" class="form-control" id="inputPIS" placeholder="Digite o PIS" name="produto_pis">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="inputCOFINS">CONFINS</label>
                    <input type="text" class="form-control" id="inputCONFINS" placeholder="Digite o CONFINS"
                        name="produto_confins">
                </div>
                <div class="form-group col-md-3">
                    <label for="inputPVenda">Preço de Venda</label>
                    <input type="text" class="form-control" id="inputPVenda" placeholder="Digite o preço de venda"
                        name="produto_preco_venda">
                </div>
                <div class="form-group col-md-6">
                    <label for="inputUnidade">Unidade</label>
                    <select class="form-control" id="inputUnidade" name="produto_unidade">
                        <option value="">Escolha a Unidade</option>
                        <option value="PC">PC | PECA (1 x 1)</option>
                        <option value="JG">JG | JOGO (1 x 1)</option>
                        <option value="KT">KT | KIT (1 x 1)</option>
                        <option value="PR">PR | PAR (1 x 1)</option>
                        <option value="CJ">CJ | CONJUNTO (1 x 1)</option>
                        <option value="UN">UN | UNIDADE (1 x 1)</option>
                        <option value="DZ">DZ | DUZIA (1 x 1)</option>
                        <option value="KG">KG | KILO (1 x 1)</option>
                        <option value="MT">MT | METRO (1 x 1)</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary col-12">CADASTRAR PRODUTO</button>
        </form>



        <div>
            <form action="" method="POST" class="form-inline mt-5" enctype="multipart/form-data">
                @csrf
                <div class="input-group w-100">
                    <input type="file" class="form-control" name="arquivo_csv">
                    <button type="submit" class="btn bg-success"><i class="fa-solid fa-file-csv"></i></button>
            </form>
        </div>
        <div>
            <form action="{{ route('dashboard_produtos_pesquisa', ['empresa'=>$empresa->name]) }}" method="GET" class="form-inline mt-5">
                @csrf
                <div class="input-group w-100">
                    <input type="text" class="form-control" name="search" placeholder="Pesquisar produtos">
                    <div class="input-group-append">
                        <span class="input-group-text bg-primary"><i class="fas fa-search text-white"></i></span>
                    </div>
                </div>
                <a class="mt-3" href="{{ URL::route('dashboard_produtos', ['empresa' => $empresa->name ]) }}"><button type="button" class="btn bg-primary"><i class="fa-solid fa-right-from-bracket"></i> VOLTAR</button></a>
            </form>
        </div>


        @if ($produtos->isNotEmpty())
            <div class="table-responsive mt-5">
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th class="rounded-left text-center">Codigo</th>
                            <th class="text-center">Produto</th>
                            <th class="text-center">Preço Venda</th>
                            <th class="rounded-right text-center">Opções</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($produtos as $produto)
                            <tr>
                                <td class="align-middle text-center">{{ $produto->sku }}</td>
                                <td class="align-middle text-center">{{ $produto->descricao }}</td>
                                <td class="align-middle text-center">R$ {{ $produto->pvenda }}</td>
                                <td class="align-middle text-center">

                                    <!-- DADOS -->
                                    <button type="button" class="btn bg-primary" data-toggle="modal"
                                        data-target="#verProduto{{ $produto->id }}" data-toggle="tooltip"
                                        title="Histórico cliente">
                                        <i class="fa-solid fa-eye text-white"></i>
                                    </button>
                                    <div class="modal fade" id="verProduto{{ $produto->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="verProduto{{ $produto->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="verProduto{{ $produto->id }}">
                                                        {{ $produto->descricao }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Fechar">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body" style="text-align: left;">

                                                    <form
                                                        action="{{ route('dashboard_produtos_atualizar', ['empresa' => $empresa->name, 'id' => $produto->id]) }}"
                                                        method="POST">
                                                        @csrf
                                                        <div class="form-row">
                                                            <div class="form-group col-md-4">
                                                                <label for="inputSKU">SKU</label>
                                                                <input type="text" class="form-control" id="inputSKU"
                                                                    value="{{ $produto->sku }}" name="produto_sku">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="inputDesc">Descrição</label>
                                                                <input type="text" class="form-control" id="inputDesc"
                                                                    value="{{ $produto->descricao }}"
                                                                    name="produto_descricao">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="inputNCM">NCM</label>
                                                                <input type="text" class="form-control" id="inputNCM"
                                                                    value="{{ $produto->ncm }}" name="produto_ncm">
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label for="inputCST">CST</label>
                                                                <input type="text" class="form-control" id="inputCST"
                                                                    value="{{ $produto->cst }}" name="produto_cst">
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label for="inputLetra">Letra</label>
                                                                <input type="text" class="form-control"
                                                                    id="inputLetra" value="{{ $produto->letra }}"
                                                                    name="produto_letra">
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label for="inputPIS">PIS</label>
                                                                <input type="text" class="form-control" id="inputPIS"
                                                                    value="{{ $produto->pis }}" name="produto_pis">
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-3">
                                                                <label for="inputCOFINS">CONFINS</label>
                                                                <input type="text" class="form-control"
                                                                    id="inputCONFINS" value="{{ $produto->confins }}"
                                                                    name="produto_confins">
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label for="inputPVenda">Preço de Venda</label>
                                                                <input type="text" class="form-control"
                                                                    id="inputPVenda" value="{{ $produto->pvenda }}"
                                                                    name="produto_preco_venda">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="inputUnidade">Unidade</label>
                                                                <select class="form-control" id="inputUnidade"
                                                                    name="produto_unidade">
                                                                    <option value="">Escolha a Unidade</option>
                                                                    <option value="PC"
                                                                        {{ $produto->unidade == 'PC' ? 'selected' : '' }}>
                                                                        PC | PECA (1 x 1)</option>
                                                                    <option value="JG"
                                                                        {{ $produto->unidade == 'JG' ? 'selected' : '' }}>
                                                                        JG | JOGO (1 x 1)</option>
                                                                    <option value="KT"
                                                                        {{ $produto->unidade == 'KT' ? 'selected' : '' }}>
                                                                        KT | KIT (1 x 1)</option>
                                                                    <option value="PR"
                                                                        {{ $produto->unidade == 'PR' ? 'selected' : '' }}>
                                                                        PR | PAR (1 x 1)</option>
                                                                    <option value="CJ"
                                                                        {{ $produto->unidade == 'CJ' ? 'selected' : '' }}>
                                                                        CJ | CONJUNTO (1 x 1)</option>
                                                                    <option value="UN"
                                                                        {{ $produto->unidade == 'UN' ? 'selected' : '' }}>
                                                                        UN | UNIDADE (1 x 1)</option>
                                                                    <option value="DZ"
                                                                        {{ $produto->unidade == 'DZ' ? 'selected' : '' }}>
                                                                        DZ | DUZIA (1 x 1)</option>
                                                                    <option value="KG"
                                                                        {{ $produto->unidade == 'KG' ? 'selected' : '' }}>
                                                                        KG | KILO (1 x 1)</option>
                                                                    <option value="MT"
                                                                        {{ $produto->unidade == 'MT' ? 'selected' : '' }}>
                                                                        MT | METRO (1 x 1)</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary col-12">ATUALIZAR
                                                            PRODUTO</button>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- FIM DADOS -->

                                    <!-- APAGAR PRODUTO -->

                                    <button type="button" class="btn btn-danger" data-toggle="modal"
                                        data-target="#confirmDeleteModal{{ $produto->id }}" data-toggle="tooltip"
                                        title="Excluir cliente">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>

                                    <div class="modal fade" id="confirmDeleteModal{{ $produto->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="confirmDeleteModalLabel{{ $produto->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="confirmDeleteModalLabel{{ $produto->id }}">
                                                        Confirmar Exclusão</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Fechar">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Tem certeza de que deseja excluir o cliente
                                                    "{{ $produto->descricao }}"?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Cancelar</button>
                                                    <form
                                                        action="{{ route('dashboard_produtos_deletar', ['empresa' => $empresa->name, 'id' => $produto->id]) }}"
                                                        method="POST" style="display: inline-block;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger">Excluir</button>
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
@endsection
