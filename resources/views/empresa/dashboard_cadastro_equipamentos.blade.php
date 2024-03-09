@extends('empresa.layouts.dashboard_empresa_menu')

@section('title', 'Dashboard')

@section('content')
    <div class="container">
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

        <div class="row">
            <div class="col-md-6">
                <form action="{{ route('dashboard_equipamentos_cadastro', ['empresa' => $empresa->name]) }}" method="POST" class="mt-4">
                    @csrf
                    <div class="form-group">
                        <label for="equipamento" data-toggle="tooltip" title="Digite o nome do equipamento">Equipamento</label>
                        <input type="text" class="form-control" id="equipamento" name="nome_equipamento" placeholder="Nome do Equipamento">
                    </div>
                    <button type="submit" class="btn bg-primary col-12">CADASTRAR EQUIPAMENTO</button>
                </form>

                <div class="container">
                    


                    @if ($equipamentos->isNotempty())
                    <div class="table-responsive mt-5">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center">Equipamento</th>
                                    <th class="rounded-right text-center">Opções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($equipamentos as $eq)
                                    <tr>
                                        <td class="align-middle text-center">{{ $eq->nome_equipamento }}</td>
                                        <td class="align-middle text-center">

                                            <!-- VER DADOS MARCA -->
                                            <button type="button" class="btn bg-primary" data-toggle="modal"
                                                data-target="#verdadosmarca{{ $eq->id }}" data-toggle="tooltip" title="Excluir terceiro">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                            <div class="modal fade" id="verdadosmarca{{ $eq->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="verdadosmarca{{ $eq->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="confirmDeleteModalLabel{{ $eq->id }}">
                                                                Atualizar {{ $eq->nome_equipamento }}</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Fechar">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="container">
                                                                <form action="{{ route('dashboard_equipamento_atualizar_dados', ['empresa'=>$empresa->name, 'id'=>$eq->id]) }}" method="POST">
                                                                    @csrf
                                                                    <input class="form-control mb-2" name="nome_equipamento" value="{{ $eq->nome_equipamento }}">
                                                                    <button type="submit" class="btn bg-primary col-12">SALVAR DADOS</button>
                                                                </form>
                                                            </div>
                                                        </div>  
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- FIM VER DADOS MARCA -->

                                            <!-- EXCLUIR REGISTRO DE MARCA -->
                                            <button type="button" class="btn btn-danger" data-toggle="modal"
                                                data-target="#confirmDeleteModal{{ $eq->id }}" data-toggle="tooltip" title="Excluir terceiro">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                            <div class="modal fade" id="confirmDeleteModal{{ $eq->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="confirmDeleteModalLabel{{ $eq->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="confirmDeleteModalLabel{{ $eq->id }}">
                                                                Confirmar Exclusão</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Fechar">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Tem certeza de que deseja excluir o equipamento
                                                            "{{ $eq->nome_equipamento }}"?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Cancelar</button>
                                                            <form
                                                                action="{{ route('dashboard_equipamento_deletar', ['empresa'=>$empresa->name, 'id'=>$eq->id]) }}"
                                                                method="POST" style="display: inline-block;">
                                                                @csrf
                                                                <button type="submit" class="btn btn-danger">Excluir</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- FIM REGISTRO DE MARCA -->
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif


                </div>
            </div>
            <div class="col-md-6">
                <form action="{{ route('dashboard_marca_cadastro', ['empresa' => $empresa->name]) }}" method="POST" class="mt-4">
                    @csrf
                    <div class="form-group">
                        <label for="equipamento" data-toggle="tooltip" title="Digite o nome do equipamento">Marca</label>
                        <input type="text" class="form-control" id="equipamento" name="marca_equipamento" placeholder="Ex: Bosh,Sthil,...">
                    </div>
                    <div class="form-group">
                        <select class="form-control" id="marca" name="id_equipamento">
                            <option value="">Selecione um equipamento referente:</option>
                            @foreach ($equipamentos as $eq)
                            <option value="{{ $eq->id }}">{{ $eq->nome_equipamento }}</option>
                            @endforeach
                            <!-- Adicione outras opções conforme necessário -->
                        </select>
                    </div>
                   
                    <button type="submit" class="btn bg-primary col-12">CADASTRAR MARCA</button>
                </form>


                <div class="container">

                    @if ($marcas->isNotempty())
                    <div class="table-responsive mt-5">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center">Marca</th>
                                    <th class="rounded-right text-center">Equipamento referente</th>
                                    <th class="rounded-right text-center">Opções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($marcas as $mr)
                                    <tr>
                                        <td class="align-middle text-center">{{ $mr->marca }}</td>
                                        <td class="align-middle text-center">{{ $mr->equipamento }}</td>
                                        <td class="align-middle text-center">

                                            <!-- VER DADOS MARCA -->
                                            <button type="button" class="btn bg-primary" data-toggle="modal"
                                                data-target="#verdadosmarca{{ $mr->id }}" data-toggle="tooltip" title="Excluir terceiro">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                            <div class="modal fade" id="verdadosmarca{{ $mr->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="verdadosmarca{{ $mr->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="confirmDeleteModalLabel{{ $mr->id }}">
                                                                Atualizar marcas</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Fechar">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="container">
                                                                <form action="{{ route('dashboard_marca_atualizar', ['empresa'=>$empresa->name, 'id'=>$mr->id]) }}" method="POST">
                                                                    @csrf
                                                                    <input class="form-control mb-2" name="marca" value="{{ $mr->marca }}">
                                                                    <button type="submit" class="btn bg-primary col-12">SALVAR DADOS</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- FIM VER DADOS MARCA -->

                                            <!-- EXCLUIR REGISTRO DE MARCA -->
                                            <button type="button" class="btn btn-danger" data-toggle="modal"
                                                data-target="#confirmDeleteModal{{ $mr->id }}" data-toggle="tooltip" title="Excluir terceiro">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                            <div class="modal fade" id="confirmDeleteModal{{ $mr->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="confirmDeleteModalLabel{{ $mr->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="confirmDeleteModalLabel{{ $mr->id }}">
                                                                Confirmar Exclusão</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Fechar">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Tem certeza de que deseja excluir a marca
                                                            "{{ $mr->marca }}"?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Cancelar</button>
                                                            <form
                                                                action="{{ route('dashboard_marca_deletar', ['empresa'=>$empresa->name, 'id'=>$mr->id]) }}"
                                                                method="POST" style="display: inline-block;">
                                                                @csrf
                                                                <button type="submit" class="btn btn-danger">Excluir</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- FIM REGISTRO DE MARCA -->


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



    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        // Função para formatar o nome completo com a primeira letra de cada palavra em maiúsculo
        $('#equipamento').on('input', function() {
            var nomeCompleto = $(this).val().toLowerCase().replace(/(?:^|\s)\w/g, function(letter) {
                return letter.toUpperCase();
            });
            $(this).val(nomeCompleto);
        });

        // Função para formatar o nome completo com a primeira letra de cada palavra em maiúsculo
        $('#marca').on('input', function() {
            var nomeCompleto = $(this).val().toLowerCase().replace(/(?:^|\s)\w/g, function(letter) {
                return letter.toUpperCase();
            });
            $(this).val(nomeCompleto);
        });
    </script>
@endsection
