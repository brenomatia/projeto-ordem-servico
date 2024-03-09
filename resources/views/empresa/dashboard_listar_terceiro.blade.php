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

        <form action="{{ route('dashboard_cadastro_terceiros', ['empresa' => $empresa->name, 'id_ordem' => $id_ordem]) }}"
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
                        <option value="" selected disabled>Selecione o Tipo de Serviço</option>
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
                    <input type="number" class="form-control" id="valor" name="valor" placeholder="Valor" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="observacoes">Observações</label>
                    <textarea class="form-control" id="observacoes" name="obs" placeholder="Observações" rows="3"></textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary col-12">CADASTRAR TERCEIRO</button>
        </form>





        @if ($terceiros->isNotEmpty())
        <div class="table-responsive mt-5">
            <table class="table table-hover">
                <thead class="thead-light">
                    <tr>
                        <th class="rounded-left text-center">#Nº</th>
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
                            <td class="align-middle text-center">{{ $terceiro->ospermited }}</td>
                            <td class="align-middle text-center">{{ $terceiro->nome_empresa }}</td>
                            <td class="align-middle text-center">{{ $terceiro->tipo_servico }}</td>
                            <td class="align-middle text-center">R$ {{ $terceiro->valor }}</td>
                            <td class="align-middle text-center">{{ $terceiro->obs }}</td>
                            <td class="align-middle text-center">
                                
                            <!-- VER TERCEIRO -->
                                <button type="button" class="btn bg-primary" data-toggle="modal"
                                    data-target="#dadosterceiro{{ $terceiro->id }}" data-toggle="tooltip" title="Ver dados">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                <div class="modal fade" id="dadosterceiro{{ $terceiro->id }}" tabindex="-1"
                                    role="dialog" aria-labelledby="dadosterceiro{{ $terceiro->id }}"
                                    aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"
                                                    id="dadosterceiro{{ $terceiro->id }}">
                                                    {{ $terceiro->nome_empresa }}</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Fechar">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body" style="text-align: left;">
                                                

                                                <form action="{{ route('dashboard_atualizar_terceiros', ['empresa' => $empresa->name, 'id_ordem' => $id_ordem, 'id_terceiro'=>$terceiro->id]) }}"
                                                    method="POST">
                                                    @csrf
                                        
                                                    <div class="form-row">
                                                        <div class="form-group col-md-6">
                                                            <label for="nome_empresa">Nome da Empresa</label>
                                                            <input type="text" class="form-control" id="nome_empresa" name="nome_empresa"
                                                                value="{{ $terceiro->nome_empresa }}">
                                                        </div>
                                        
                                                        <div class="form-group col-md-6">
                                                            <label for="tipo_servico">Tipo de Serviço</label>
                                                            <select class="form-control" id="tipo_servico" name="tipo_servico">
                                                                <option value="" disabled>Selecione o Tipo de Serviço</option>
                                                                <option value="Torno" {{ $terceiro->tipo_servico == 'Torno' ? 'selected' : '' }}>Torno</option>
                                                                <option value="Solda" {{ $terceiro->tipo_servico == 'Solda' ? 'selected' : '' }}>Solda</option>
                                                                <option value="Limpeza" {{ $terceiro->tipo_servico == 'Limpeza' ? 'selected' : '' }}>Limpeza</option>
                                                                <option value="Outros" {{ $terceiro->tipo_servico == 'Outros' ? 'selected' : '' }}>Outros</option>
                                                            </select>
                                                            
                                                        </div>
                                                    </div>
                                        
                                                    <div class="form-row">
                                                        <div class="form-group col-md-12">
                                                            <label for="valor">Valor a ser Cobrado</label>
                                                            <input type="number" class="form-control" id="valor" name="valor" value="{{ $terceiro->valor }}" step="any">
                                                        </div>
                                                    </div>
                                        
                                                    <div class="form-row">
                                                        <div class="form-group col-md-12">
                                                            <label for="observacoes">Observações</label>
                                                            <textarea class="form-control" id="observacoes" name="obs" rows="3">{{ $terceiro->obs }}</textarea>
                                                        </div>
                                                    </div>
                                        
                                                    <button type="submit" class="btn btn-primary col-12">ATUALIZAR TERCEIRO</button>
                                                </form>



                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- FIM VER TERCEIRO -->

                                <!-- EXCLUIR TERCEIRO -->
                                <button type="button" class="btn btn-danger" data-toggle="modal"
                                    data-target="#confirmDeleteModal{{ $terceiro->id }}" data-toggle="tooltip" title="Excluir terceiro">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                                <div class="modal fade" id="confirmDeleteModal{{ $terceiro->id }}" tabindex="-1"
                                    role="dialog" aria-labelledby="confirmDeleteModalLabel{{ $terceiro->id }}"
                                    aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"
                                                    id="confirmDeleteModalLabel{{ $terceiro->id }}">
                                                    Confirmar Exclusão</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Fechar">
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
                                                    action="{{ route('dashboard_deletar_terceiros', ['empresa' => $empresa->name, 'id_ordem' => $id_ordem, 'id_terceiro'=>$terceiro->id]) }}"
                                                    method="POST" style="display: inline-block;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger">Excluir</button>
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
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

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
@endsection
