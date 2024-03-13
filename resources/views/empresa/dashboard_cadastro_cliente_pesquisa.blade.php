@extends('empresa.layouts.dashboard_empresa_menu')

@section('title', 'Dashboard')

@section('content')
    <div class="container">


        @if (session('success'))
            <div id="successAlert" class="alert alert-success">
                <i class="nav-icon fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div id="errorAlert" class="alert alert-danger">
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

<form action="{{ route('dashboard_cadastrando_cliente', ['empresa' => $empresa->name]) }}" method="POST" class="mt-5">
    @csrf
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="nomeCompleto">Nome Completo:</label>
            <input type="text" class="form-control" id="nomeCompleto" name="cliente_nome_completo" placeholder="Nome completo do cliente">
        </div>
        <div class="form-group col-md-6">
            <label for="celular">Celular:</label>
            <input type="tel" class="form-control" id="celular" name="cliente_celular" placeholder="34999999999">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-3">
            <label for="cep">CEP:</label>
            <input type="number" class="form-control" id="cep" name="cliente_cep" placeholder="CEP">
        </div>
        <div class="form-group col-md-3">
            <label for="numero">Número:</label>
            <input type="number" class="form-control" id="numero" name="cliente_numero" placeholder="Nº">
        </div>
        <div class="form-group col-md-6">
            <label for="endereco">Endereço:</label>
            <input type="text" class="form-control" id="endereco" name="cliente_endereco" placeholder="Endereço cliente">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="cidade">Cidade:</label>
            <input type="text" class="form-control" id="cidade" name="cliente_cidade" placeholder="Cidade">
        </div>
        <div class="form-group col-md-6">
            <label for="uf">UF:</label>
            <input type="text" class="form-control" id="uf" name="cliente_uf" placeholder="UF">
        </div>
    </div>
    <button type="submit" class="btn btn-primary col-12">CADASTRAR CLIENTE</button>
</form>

        <form action="{{ route('dashboard_cadastro_cliente_pesquisa', ['empresa'=>$empresa->name]) }}" method="GET" class="form-inline mt-5">
            @csrf
            <div class="input-group w-100">
                <input type="text" class="form-control" name="search" placeholder="Pesquisar clientes">
                <div class="input-group-append">
                    <span class="input-group-text bg-primary"><i class="fas fa-search text-white"></i></span>
                </div>
            </div>

            <a class="mt-3" href="{{ URL::route('dashboard_cadastro_cliente', ['empresa' => $empresa->name ]) }}"><button type="button" class="btn bg-primary"><i class="fa-solid fa-right-from-bracket"></i> VOLTAR</button></a>
        </form>





        @if ($clientes->isNotEmpty())
            <div class="table-responsive mt-5">
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th class="rounded-left text-center">#ID</th>
                            <th class="text-center">Nome</th>
                            <th class="text-center">Endereço</th>
                            <th class="text-center">Cidade</th>
                            <th class="text-center">Celular</th>
                            <th class="rounded-right text-center">Opções</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Aqui você pode usar seu foreach para preencher dinamicamente os dados -->
                        @foreach ($clientes as $cliente)
                            <tr>
                                <td class="align-middle text-center">{{ $cliente->id }}</td>
                                <td class="align-middle text-center">{{ $cliente->nome_completo }}</td>
                                <td class="align-middle text-center">{{ $cliente->endereco }}</td>
                                <td class="align-middle text-center">{{ $cliente->cidade.'-'.$cliente->uf }}</td>
                                <td class="align-middle text-center">{{ $cliente->celular }}</td>
                                <td class="align-middle text-center">

                                    <!-- Botão para abrir modal -->
                                    <a href="https://api.whatsapp.com/send/?phone=55{{ str_replace(['(', ')', '-', ' '], '', $cliente->celular) }}"
                                        target="_Blank"><button type="button" class="btn btn-success" data-toggle="tooltip" title="Whatsapp">
                                            <i class="fa-brands fa-whatsapp text-white"></i>
                                        </button></a>

                                    <!-- Botão para abrir modal -->
                                    <button type="button" class="btn bg-purple" data-toggle="modal"
                                        data-target="#viewHistoric{{ $cliente->id }}" data-toggle="tooltip" title="Histórico cliente">
                                        <i class="fa-solid fa-clock-rotate-left"></i>
                                    </button>

                                    <!-- Modal de confirmação de exclusão -->
                                    <div class="modal fade" id="viewHistoric{{ $cliente->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="viewHistoric{{ $cliente->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="viewHistoric{{ $cliente->id }}">
                                                        {{ $cliente->nome_completo }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Fechar">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">


                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped">
                                                            <thead class="thead-dark">
                                                            <tr>
                                                                <th class="text-center">ORDEM Nº</th>
                                                                <th class="text-center">VENDEDOR</th>
                                                                <th class="text-center">STATUS</th>
                                                                <th class="text-center">ABERTURA</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($ordemServicos as $ordem)
                                                                @if($ordem->id_cliente == $cliente->id)
                                                                    
                                                                    <tr>
                                                                        <td class="align-middle text-center" style="cursor: pointer;" onclick="window.location='{{ route('dashboard_gerador_pdf_route', ['empresa'=>$empresa->name, 'id_ordem'=>$ordem->id]) }}';" >{{ $ordem->id }}</td>
                                                                        <td class="align-middle text-center" style="cursor: pointer;" onclick="window.location='{{ route('dashboard_gerador_pdf_route', ['empresa'=>$empresa->name, 'id_ordem'=>$ordem->id]) }}';" >{{ $ordem->abertura_da_ordem }}</td>
                                                                        <td class="align-middle text-center" style="cursor: pointer;" onclick="window.location='{{ route('dashboard_gerador_pdf_route', ['empresa'=>$empresa->name, 'id_ordem'=>$ordem->id]) }}';" >{{ $ordem->status }}</td>
                                                                        <td class="align-middle text-center" style="cursor: pointer;" onclick="window.location='{{ route('dashboard_gerador_pdf_route', ['empresa'=>$empresa->name, 'id_ordem'=>$ordem->id]) }}';" >{{ $ordem->created_at->format('d/m/Y H:i:s') }}</td>
                                                                    </tr> 
                                                                @endif
                                                                
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fim do modal -->

                                    <!-- Botão para abrir modal -->
                                    <button type="button" class="btn btn-warning text-white" data-toggle="modal"
                                        data-target="#viewModalCliente{{ $cliente->id }}" data-toggle="tooltip" title="Dados cliente">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    <!-- Modal de confirmação de exclusão -->
                                    <div class="modal fade" id="viewModalCliente{{ $cliente->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="viewModalCliente{{ $cliente->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="viewModalCliente{{ $cliente->id }}">
                                                        Atualizar dados de {{ $cliente->nome_completo }}
                                                    </h5>

                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Fechar">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body" style="text-align: left;">

                                                    <form
                                                        action="{{ route('dashboard_atualizar_cliente', ['empresa' => $empresa->name, 'id'=>$cliente->id]) }}"
                                                        method="POST">
                                                        @csrf
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label for="nomeCompleto">Nome Completo:</label>
                                                                <input type="text" class="form-control"
                                                                    id="nomeCompleto" name="cliente_nome_completo"
                                                                    value="{{ $cliente->nome_completo }}">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="celular">Celular:</label>
                                                                <input type="tel" class="form-control" id="celular"
                                                                    name="cliente_celular"
                                                                    value="{{ $cliente->celular }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label for="endereco">Endereço:</label>
                                                                <input type="text" class="form-control" id="endereco"
                                                                    name="cliente_endereco"
                                                                    value="{{ $cliente->endereco }}">
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label for="endereco">Numero:</label>
                                                                <input type="number" class="form-control" id="numero"
                                                                    name="cliente_numero" value="{{ $cliente->numero }}">
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label for="endereco">Cep:</label>
                                                                <input type="number" class="form-control" id="cep"
                                                                    name="cliente_cep" value="{{ $cliente->cep }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label for="cidade">Cidade:</label>
                                                                <input type="text" class="form-control" id="cidade" name="cliente_cidade" value="{{ $cliente->cidade }}">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="uf">UF:</label>
                                                                <input type="text" class="form-control" id="uf" name="cliente_uf" value="{{ $cliente->uf }}">
                                                            </div>
                                                        </div>
                                                        <button type="submit" class="btn btn-success col-12">ATUALIZAR
                                                            CLIENTE</button>
                                                    </form>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fim do modal -->

                                    <!-- Botão para abrir modal -->
                                    <button type="button" class="btn btn-danger" data-toggle="modal"
                                        data-target="#confirmDeleteModal{{ $cliente->id }}" data-toggle="tooltip" title="Excluir cliente">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>

                                    <!-- Modal de confirmação de exclusão -->
                                    <div class="modal fade" id="confirmDeleteModal{{ $cliente->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="confirmDeleteModalLabel{{ $cliente->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="confirmDeleteModalLabel{{ $cliente->id }}">
                                                        Confirmar Exclusão</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Fechar">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Tem certeza de que deseja excluir o cliente
                                                    "{{ $cliente->nome_completo }}"?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Cancelar</button>
                                                    <form
                                                        action="{{ route('dashboard_deletando_cliente', ['empresa' => $empresa->name, 'id' => $cliente->id]) }}"
                                                        method="POST" style="display: inline-block;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger">Excluir</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fim do modal -->


                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $clientes->links('pagination::bootstrap-5') }}
                </div>                
            </div>
        @endif
    </div>


    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        // Função para formatar o nome completo com a primeira letra de cada palavra em maiúsculo
        $('#nomeCompleto').on('input', function() {
            var nomeCompleto = $(this).val().toLowerCase().replace(/(?:^|\s)\w/g, function(letter) {
                return letter.toUpperCase();
            });
            $(this).val(nomeCompleto);
        });


        document.getElementById("celular").addEventListener("input", function() {
            let inputValue = this.value.replace(/\D/g, ''); // Remove todos os caracteres que não são dígitos

            let formattedValue = '';

            if (inputValue.length > 0) {
                formattedValue += '(' + inputValue.substring(0, 2);
            }
            if (inputValue.length > 2) {
                formattedValue += ') ' + inputValue.substring(2, 7);
            }
            if (inputValue.length > 7) {
                formattedValue += '-' + inputValue.substring(7, 11);
            }

            this.value = formattedValue;
        });



        $(document).ready(function() {
            $('#cep').on('blur', function() {
                var cep = $(this).val().replace(/\D/g, ''); // Remove todos os caracteres que não são dígitos
                if (cep.length != 8) {
                    return;
                }
                $.getJSON('https://viacep.com.br/ws/' + cep + '/json/', function(data) {
                    if (!data.erro) {
                        $('#endereco').val(data.logradouro + ', ' + data.bairro);
                        $('#cidade').val(data.localidade);
                        $('#uf').val(data.uf);
                    } else {
                        alert('CEP não encontrado.');
                    }
                });
            });
        });

    </script>

@endsection
