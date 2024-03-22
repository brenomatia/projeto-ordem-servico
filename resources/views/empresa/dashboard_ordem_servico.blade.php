@extends('empresa.layouts.dashboard_empresa_menu')

@section('title', 'Dashboard')

@section('content')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <div class="container col-11 mt-5">

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
                    <button type="submit" class="btn btn-primary col-12 mb-5" type="button" id="button-addon2">LISTAR EQUIPAMENTO</button>
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
                                        <!-- Botão de exclusão -->
                                        <button type="button" class="btn btn-danger ml-2" onclick="excluirItem({{ $new->id }})">Excluir</button>
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

                    <button class="btn btn-primary col-12" type="button" onclick="ativarClientes()">LANÇAR ORDEM DE SERVIÇO</button>


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

                                <i class="fas {{ Session::has('openCardId') && Session::get('openCardId') == $loop->iteration ? 'fa-circle-arrow-up text-red' : 'fa-circle-arrow-down text-green' }}"></i>

                            </div>
                        </div>
                    </a>
                    <div id="collapse{{ $loop->iteration }}"
                        class="collapse {{ Session::get('openCardId') == $loop->iteration ? 'show' : '' }}">
                        <div class="card-body">

                            
                                <div class="card">
                                    <div class="card-body">

                                        <a href="{{ URL::route('dashboard_gerador_pdf_route', ['empresa'=>$empresa->name, 'id_ordem'=>$ordem->id]) }}"><button class="btn bg-purple mb-3"><i class="fa-solid fa-file-pdf mr-2"></i>VISUALIZAR PDF</button></a>
                                        <a href="{{ URL::route('dashboard_gen_protocolo', ['empresa'=>$empresa->name, 'id_ordem'=>$ordem->id]) }}"><button class="btn bg-gray mb-3"><i class="fa-solid fa-clipboard-list mr-2"></i>GERAR PROTOCOLO</button></a>
                                        
                                        <button type="button" class="btn btn-warning mb-3 text-white" data-toggle="modal" data-target="#cancelar_ordem_{{ $ordem->id }}" data-toggle="tooltip" title="Excluir cliente">
                                            <i class="fa-solid fa-triangle-exclamation mr-2"></i> CANCELAR OS
                                        </button>
                                            
                                        <div class="modal fade" id="cancelar_ordem_{{ $ordem->id }}" tabindex="-1" role="dialog" aria-labelledby="cancelar_ordem_{{ $ordem->id }}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="cancelar_ordem_{{ $ordem->id }}">
                                                            Confirmar Cancelamento</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Fechar">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('dashboard_ordem_cancelamento', ['empresa' => $empresa->name, 'id_ordem' => $ordem->id]) }}" method="POST">
                                                            @csrf
                                                            <div class="form-group">
                                                                <label for="motivoCancelamento">Motivo do Cancelamento:</label>
                                                                <textarea class="form-control" name="obs" id="motivoCancelamento" rows="3" {{ $ordem->obs ? 'disabled' : '' }}>{{ $ordem->obs ?: '' }}</textarea>
                                                            </div>
                                                            @if(!$ordem->obs != null)
                                                            <button type="submit" class="btn btn-danger col-12">CANCELAR</button>
                                                            @endif
                                                        </form>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>

                                        <button type="button" class="btn btn-danger mb-3" data-toggle="modal" data-target="#deletarORDEM{{ $ordem->id }}" data-toggle="tooltip" title="Excluir cliente">
                                            <i class="fa-solid fa-trash-can mr-2"></i> EXCLUIR ORDEM
                                        </button>
                                            
                                        <div class="modal fade" id="deletarORDEM{{ $ordem->id }}" tabindex="-1" role="dialog" aria-labelledby="deletarORDEM{{ $ordem->id }}" aria-hidden="true">
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
                                                                         
                                                                                

                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="small-box bg-purple">
                                                    <div class="inner">
                                                        <h3>R$ {{ number_format($ordem->carrinhos()->sum('valor'), 2, ',', '.') }}</h3>
                                                        <p>LISTAGEM</p>
                                                    </div>
                                                </div>
                                            </div>
                                        
                                            <div class="col-lg-4 col-md-6">
                                                <div class="small-box bg-info">
                                                    <div class="inner">
                                                        <h3>R$ {{ number_format($ordem->terceiros()->sum('valor'), 2, ',', '.') }}</h3>
                                                        <p>TERCEIROS</p>
                                                    </div>
                                                </div>
                                            </div>
                                        
                                            <div class="col-lg-4 col-md-6">
                                                <div class="small-box bg-warning">
                                                    <div class="inner">
                                                        <h3>R$ {{ number_format($ordem->maoDeObras()->sum('valor'), 2, ',', '.') }}</h3>
                                                        <p>MÃO DE OBRA</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6">
                                                <div class="small-box bg-danger">
                                                    <div class="inner">
                                                        <h3>R$ {{ number_format(($ordem->carrinhos()->sum('valor') + $ordem->terceiros()->sum('valor')) + $ordem->maoDeObras()->sum('valor'), 2, ',', '.') }}</h3>
                                                        <p>TOTAL O.S</p>
                                                    </div>
                                                </div> 
                                            </div>  
                                        
                                            <div class="col-lg-6 col-md-6">
                                                <div class="small-box bg-success">
                                                    <div class="inner">
                                                        <h3>R$ {{ number_format($ordem->equipamentosOS->sum('valorComDesconto'), 2, ',', '.') }}</h3>
                                                        <p>TOTAL C/ DESCONTO</p>
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
                                                            <td class="align-middle text-center" style="cursor: pointer;" onclick="window.location='{{ route('dashboard_listar_items_ordem', ['empresa'=>$empresa->name, 'id_ordem'=>$ordem->id, 'id_equipamento'=>$equipamento->id]) }}';">
                                                                <strong>{{ $equipamento->equipamento }}</strong>

                                                                @if( $equipamento->valorComDesconto &&
                                                                $equipamento->MeioPagamento &&
                                                                $equipamento->valorTroco &&
                                                                $equipamento->parcelaTotal &&
                                                                $equipamento->valorParcelas &&
                                                                $equipamento->valorPago )
                                                                <p><span class="badge pill-badge bg-purple">Equipamento pago</span></p>
                                                                @elseif($equipamento->valorPago)
                                                                <p><span class="badge pill-badge bg-success">Equipamento processado</span></p>
                                                                @endif

                                                                <p class="text-black">{{ $equipamento->status }}</p>
                                                            </td>
                                                            <td class="align-middle text-center" style="cursor: pointer;" onclick="window.location='{{ route('dashboard_listar_items_ordem', ['empresa'=>$empresa->name, 'id_ordem'=>$ordem->id, 'id_equipamento'=>$equipamento->id]) }}';"> 
                                                                @if($equipamento->valorComDesconto)
                                                                    R$ {{ number_format($equipamento->valorComDesconto, 2, ',', '.') }}
                                                                @else
                                                                    R$ {{ number_format(($equipamento->Carrinhos()->sum('valor') + $equipamento->Terceiro()->sum('valor')) + $equipamento->MaoDeObra()->sum('valor'), 2, ',', '.') }}
                                                                @endif
                                                            </td>
                                                            <td class="align-middle text-center">

                                                                <!-- APAGAR ATUALIZAR EQUIPAMENTO -->

                                                                <button type="button" class="btn btn-primary"
                                                                    data-toggle="modal"
                                                                    data-target="#atualizardados_{{ $equipamento->id }}"
                                                                    data-toggle="tooltip" title="Alterar nome equipamento">
                                                                    <i class="fa-solid fa-pen-to-square"></i>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        @else
            <p>Nenhuma ordem de serviço encontrada.</p>
        @endif







    </div>

    <script>
    function excluirItem(id) {
        if (confirm("Tem certeza que deseja excluir este item?")) {
            // Construindo a rota com o ID do equipamento
            var rota = "{{ route('deletando_equipamento', ['empresa' => $empresa->name, 'id' => ':id']) }}";
            // Substituindo o placeholder :id pelo ID do equipamento
            rota = rota.replace(':id', id);
            // Redirecionar para a rota de exclusão
            window.location.href = rota;
        }
    }
</script>



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
