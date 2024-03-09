@extends('empresa.layouts.dashboard_empresa_menu')

@section('title', 'Dashboard')

@section('content')

<div class="container" id="conteudoParaImprimir">
    <div class="container col-11">
    <img src="{{ asset('logos/' . $empresa->logo) }}" style="width: 248px;" />
    <div class="row">
        <div class="col-md-6">
            <h5>Dados da Empresa</h5>
            <p>Nome fantasia: {{ $empresa->name }}</p>
            <p>Responsável: {{ $ordem->abertura_da_ordem }}</p>
        </div>
        <div class="col-md-6">
            <h5>Dados do Cliente</h5>
            <p>Nome: {{ $ordem->nome_cliente }}</p>
            <p>ID ordem: #{{ $ordem->id }}</p>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <h4>Itens da Ordem de Serviço</h4>
            @php
                $total = 0;
                $restantes = [];
            @endphp
            @foreach($itens as $item)
                @php
                    $subtotal = $item->carrinhos()->sum('valor') + $item->terceiro()->sum('valor') + $item->maodeobra()->sum('valor');
                    if($item->tipo_pagamento_autorizado != null){
                        $subtotal = 0;
                    }
                    $total += $subtotal;
                    $restante = $item->valor_final_autorizado - $item->valor_pago_autorizado;
                    if ($restante > 0) {
                        $restantes[] = $restante;
                    }
                @endphp
                <div class="card mb-3">
                    <div class="card-header bg-dark">
                        <h5 class="card-title text-white">{{ $item->equipamento }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label>Listagem</label>
                                <p><strong>Total:</strong> R$ {{ number_format($item->carrinhos()->sum('valor'), 2, ',', '.') }}</p>
                                <label>Produtos:</label>
                                @foreach ($item->carrinhos as $cart)
                                    <p>{{ $cart->produto }}</p>
                                @endforeach
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Terceiro</label>
                                <p><strong>Total:</strong> R$ {{ number_format($item->terceiro()->sum('valor'), 2, ',', '.') }}</p>
                                <label>Serviços:</label>
                                @foreach ($item->terceiro as $terceiro)
                                    <p>{{ $terceiro->tipo_servico }} - R$ {{ number_format($terceiro->valor, 2, ',', '.') }}</p>
                                @endforeach
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Mão de Obra</label>
                                <p><strong>Total:</strong> R$ {{ number_format($item->maodeobra()->sum('valor'), 2, ',', '.') }}</p>
                                <label>Mão de Obra:</label>
                                @foreach ($item->maodeobra as $maodeobra)
                                    <p>{{ $maodeobra->tipo }} - R$ {{ number_format($maodeobra->valor, 2, ',', '.') }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="card-footer rounded-bottom">
                        @if($item->valor_final_autorizado != $item->valor_pago_autorizado)
                        <div class="row">
                            <div class="col-md-6">
                            <label class="text-green">Pago: R$ {{  number_format($item->valor_pago_autorizado, 2, ',', '.') }}</label><br>
                            <label class="text-red">Restante: R$ {{  number_format($restante, 2, ',', '.') }}</label>
                            <p class="text-orange">Pagamento efetuado parcialmente.</p>
                            <p><span class="text-muted">Tipo de pagamento: <strong>{{ $item->tipo_pagamento_autorizado }} em {{ $item->updated_at->format('d/m/Y H:i:s')}}</strong></span></p>
                            </div>
                            <div class="col-md-6 d-flex justify-content-center align-items-center">
                                <a href="{{ route('dashboard_listar_items_ordem', ['empresa'=>$empresa->name, 'id_ordem'=>$ordem, 'id_equipamento'=>$item->id]) }}"><button class="btn bg-dark removerNaImpressao"><i class="fa-solid fa-magnifying-glass mr-2"></i> ANALISAR EQUIPAMENTO</button></a>
                            </div>
                        </div>
                        @elseif(!empty($item->valor_final_autorizado) && !empty($item->valor_pago_autorizado))
                            @if($item->valor_final_autorizado == $item->valor_pago_autorizado)
                            <span class="text-green">pagamento total efetuado em {{ $item->updated_at->format('d/m/Y H:i:s')}}</span>
                            @endif
                        @else
                            <label class="text-green">Subtotal: R$ {{  number_format($total, 2, ',', '.') }}</label><br>
                        @endif
                    </div>
                </div>
            @endforeach
            @php
                $total += array_sum($restantes);
            @endphp
            <div class="row mb-3 mt-1">
                <div class="col-md-12">
                    <div class="card p-3">
                        TOTAL R$ {{ number_format($total, 2, ',', '.') }}
                    </div>
                    <button onclick="imprimirPDF()" type="button" class="btn btn-success mb-5 col-12 removerNaImpressao"><i class="fa-solid fa-file-pdf mr-2"></i>Imprimir PDF</button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    function imprimirPDF() {
        var conteudo = document.getElementById('conteudoParaImprimir').innerHTML;
        var estilosBootstrap = document.createElement('link');
        estilosBootstrap.href = 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css';
        estilosBootstrap.rel = 'stylesheet';
        
        // Copia o conteúdo da div
        var conteudoParaImpressao = document.createElement('div');
        conteudoParaImpressao.innerHTML = conteudo;

        // Remove os botões pelo nome da classe
        var botoes = conteudoParaImpressao.querySelectorAll('.removerNaImpressao');
        for (var i = 0; i < botoes.length; i++) {
            botoes[i].parentNode.removeChild(botoes[i]);
        }
        
        var janelaDeImpressao = window.open('', '', 'height=600,width=800');
        janelaDeImpressao.document.write('<html><head><title>PDF</title>');
        // Inclua os estilos CSS do Bootstrap
        janelaDeImpressao.document.head.appendChild(estilosBootstrap);
        janelaDeImpressao.document.write('</head><body>');
        janelaDeImpressao.document.write(conteudoParaImpressao.innerHTML);
        janelaDeImpressao.document.write('</body></html>');
        janelaDeImpressao.document.close();
    }
</script>








@endsection
