@extends('empresa.layouts.dashboard_empresa_menu')

@section('title', 'Dashboard')

@section('content')
    <div class="container mt-5 col-11">

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

        <form action="{{ route('dashboard_cadastro_revendas', ['empresa' => $empresa->name]) }}" method="POST">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="responsavel">Nome do Responsável</label>
                    <input type="text" class="form-control" id="responsavel" name="revenda_nome"
                        placeholder="Digite o nome do responsável">
                </div>
                <div class="form-group col-md-3">
                    <label for="empresa">Nome da Empresa</label>
                    <input type="text" class="form-control" id="empresa" name="revenda_nome_empresa"
                        placeholder="Digite o nome da empresa">
                </div>
                <div class="form-group col-md-3">
                    <label for="cnpj">CNPJ/CPF da Empresa</label>
                    <input type="text" class="form-control" id="cnpj" name="revenda_cnpj"
                        placeholder="Digite o CNPJ/CPF da empresa">
                </div>
                <div class="form-group col-md-3">
                    <label for="desconto">Desconto (%)</label>
                    <input type="number" class="form-control" id="desconto" name="revenda_desconto"
                        placeholder="Digite o desconto">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-2">
                    <label for="cep">CEP</label>
                    <input type="text" class="form-control" id="cep" name="revenda_cep" placeholder="Digite o CEP">
                </div>
                <div class="form-group col-md-1">
                    <label for="numero">Número</label>
                    <input type="text" class="form-control" id="numero" name="revenda_numero"
                        placeholder="Nº">
                </div>
                <div class="form-group col-md-3">
                    <label for="endereco">Endereço</label>
                    <input type="text" class="form-control" id="endereco" name="revenda_endereco"
                        placeholder="Digite o endereço">
                </div>                
                <div class="form-group col-md-3">
                    <label for="email">E-mail</label>
                    <input type="email" class="form-control" id="email" name="revenda_email"
                        placeholder="Digite o e-mail">
                </div>
                <div class="form-group col-md-3">
                    <label for="celular">Celular</label>
                    <input type="text" class="form-control" id="celular" name="revenda_celular"
                        placeholder="Digite o celular">
                </div>
            </div>
            <div class="form-group">
                <label for="observacoes">Observações</label>
                <textarea class="form-control" id="observacoes" rows="3" name="revenda_obs"
                    placeholder="Digite observações (opcional)"></textarea>
            </div>
            <button type="submit" class="btn btn-primary col-12">CADASTRAR REVENDA</button>
        </form>
        @if ($revendas->isNotempty())
            <div class="table-responsive mt-5">
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center">Empresa</th>
                            <th class="text-center">Responsável</th>
                            <th class="text-center">Desconto</th>
                            <th class="text-center">Celular</th>
                            <th class="rounded-right text-center">Opções</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($revendas as $revenda)
                            <tr>
                                <td class="align-middle text-center">{{ $revenda->nome_empresa }}</td>
                                <td class="align-middle text-center">{{ $revenda->nome_responsavel }}</td>
                                <td class="align-middle text-center"><button class="btn bg-danger">{{ $revenda->desconto }} %</button></td>
                                <td class="align-middle text-center">{{ $revenda->celular }}</td>
                                <td class="align-middle text-center">
                                    <!-- BTN WHATSAPP -->
                                         <a href="https://api.whatsapp.com/send/?phone=55{{ str_replace(['(', ')', '-', ' '], '', $revenda->celular) }}"
                                            target="_Blank"><button type="button" class="btn btn-success" data-toggle="tooltip" title="Whatsapp">
                                                <i class="fa-brands fa-whatsapp text-white"></i>
                                            </button></a>
                                    <!-- FIM BTN WHATSAPP-->
                                    <!-- MODAL ATUALIZAR DADOS -->
                                    <button type="button" class="btn bg-purple" data-toggle="modal"
                                        data-target="#Dados{{ $revenda->id }}" data-toggle="tooltip"
                                        title="Excluir equipamento">
                                        <i class="fa-solid fa-eye text-white"></i>
                                    </button>
                                    <div class="modal fade" id="Dados{{ $revenda->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="Dados{{ $revenda->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="Dados{{ $revenda->id }}">
                                                        {{ $revenda->nome_empresa }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Fechar">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body" style="text-align: left;">


                                                    <form
                                                        action="{{ route('dashboard_atualizar_revendas', ['empresa' => $empresa->name, 'id'=>$revenda->id]) }}"
                                                        method="POST">
                                                        @csrf
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label for="responsavel">Nome do Responsável</label>
                                                                <input type="text" class="form-control"
                                                                    id="responsavel" name="revenda_nome"
                                                                    value="{{ $revenda->nome_responsavel }}">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="empresa">Nome da Empresa</label>
                                                                <input type="text" class="form-control" id="empresa"
                                                                    name="revenda_nome_empresa"
                                                                    value="{{ $revenda->nome_empresa }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-3">
                                                                <label for="cnpj">CNPJ da Empresa</label>
                                                                <input type="text" class="form-control" id="cnpj"
                                                                    name="revenda_cnpj"
                                                                    value="{{ $revenda->cnpj_empresa }}">
                                                            </div>
                                                            <div class="form-group col-md-2">
                                                                <label for="numero">Número</label>
                                                                <input type="text" class="form-control" id="numero"
                                                                    name="revenda_numero"
                                                                    value="{{ $revenda->numero }}">
                                                            </div>
                                                            <div class="form-group col-md-2">
                                                                <label for="desconto">Desconto (%)</label>
                                                                <input type="number" class="form-control" id="desconto"
                                                                    name="revenda_desconto"
                                                                    value="{{ $revenda->desconto }}">
                                                            </div>
                                                            <div class="form-group col-md-5">
                                                                <label for="endereco">Endereço</label>
                                                                <input type="text" class="form-control" id="endereco"
                                                                    name="revenda_endereco"
                                                                    value="{{ $revenda->endereco }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-4">
                                                                <label for="cep">CEP</label>
                                                                <input type="text" class="form-control" id="cep"
                                                                    name="revenda_cep" value="{{ $revenda->cep }}">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="email">E-mail</label>
                                                                <input type="email" class="form-control" id="email"
                                                                    name="revenda_email" value="{{ $revenda->email }}">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="celular">Celular</label>
                                                                <input type="text" class="form-control" id="celular"
                                                                    name="revenda_celular" value="{{ $revenda->celular }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="observacoes">Observações</label>
                                                            <textarea class="form-control" id="observacoes" rows="3" name="revenda_obs">{{ $revenda->obs }}</textarea>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary col-12">ATUALIZAR
                                                            REVENDA</button>
                                                    </form>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- FIM MODAL ATUALIZAR DADOS -->

                                    <!-- MODAL EXCLUIR -->
                                    <button type="button" class="btn btn-danger" data-toggle="modal"
                                        data-target="#confirmDeleteModal{{ $revenda->id }}" data-toggle="tooltip"
                                        title="Excluir equipamento">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                    <div class="modal fade" id="confirmDeleteModal{{ $revenda->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="confirmDeleteModalLabel{{ $revenda->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="confirmDeleteModalLabel{{ $revenda->id }}">
                                                        Confirmar Exclusão</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Fechar">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Tem certeza de que deseja excluir a revenda de
                                                    "{{ $revenda->nome_empresa }}"?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Cancelar</button>
                                                    <form
                                                        action="{{ route('dashboard_deletar_revendas', ['empresa' => $empresa->name, 'id' => $revenda->id]) }}"
                                                        method="POST" style="display: inline-block;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger">Excluir</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- FIM MODAL EXCLUIR -->
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $revendas->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif

    </div>



    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        // Função para formatar o nome completo com a primeira letra de cada palavra em maiúsculo
        $('#responsavel').on('input', function() {
            var nomeCompleto = $(this).val().toLowerCase().replace(/(?:^|\s)\w/g, function(letter) {
                return letter.toUpperCase();
            });
            $(this).val(nomeCompleto);
        });

        // Função para formatar o nome completo com a primeira letra de cada palavra em maiúsculo
        $('#empresa').on('input', function() {
            var nomeCompleto = $(this).val().toLowerCase().replace(/(?:^|\s)\w/g, function(letter) {
                return letter.toUpperCase();
            });
            $(this).val(nomeCompleto);
        });

        // Função para formatar o nome completo com a primeira letra de cada palavra em maiúsculo
        $('#endereco').on('input', function() {
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
                var cep = $(this).val().replace(/\D/g,
                    ''); // Remove todos os caracteres que não são dígitos
                if (cep.length != 8) {
                    return;
                }
                $.getJSON('https://viacep.com.br/ws/' + cep + '/json/', function(data) {
                    if (!data.erro) {
                        $('#endereco').val(data.logradouro + ', ' + data.bairro + ', ' + data
                            .localidade + ', ' + data.uf);
                    } else {
                        alert('CEP não encontrado.');
                    }
                });
            });
        });
    </script>
@endsection
