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
            @foreach($itens as $item)

                <div class="card mb-3">
                    <div class="card-header bg-dark">
                        <h5 class="card-title text-white">{{ $item->equipamento }}</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <p><strong>Listagem</strong> - R$ {{ number_format($item->carrinhos()->sum('valor'), 2, ',', '.') }}</p>
                                <label>Produtos</label>
                                @foreach ($item->carrinhos as $cart)
                                    <p>{{ $cart->produto }}</p>
                                @endforeach
                            </div>
                            <div class="col-md-4 mb-3">
                                <p><strong>Terceiros</strong> - R$ {{ number_format($item->terceiro()->sum('valor'), 2, ',', '.') }}</p>
                                <label>Serviços</label>
                                @foreach ($item->terceiro as $terceiro)
                                    <p>{{ $terceiro->tipo_servico }} - R$ {{ number_format($terceiro->valor, 2, ',', '.') }}</p>
                                @endforeach
                            </div>
                            <div class="col-md-4 mb-3">
                                <p><strong>Mão de Obra</strong> - R$ {{ number_format($item->maodeobra()->sum('valor'), 2, ',', '.') }}</p>
                                <label>Mão de Obra</label>
                                @foreach ($item->maodeobra as $maodeobra)
                                    <p>{{ $maodeobra->tipo }} - R$ {{ number_format($maodeobra->valor, 2, ',', '.') }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="card-footer rounded-bottom" style="text-align: left!important;">
                        @if( $item->valorComDesconto &&
                        $item->MeioPagamento &&
                        $item->valorTroco &&
                        $item->parcelaTotal &&
                        $item->valorParcelas &&
                        $item->valorPago )
                        <p><span class="badge pill-badge bg-purple">Equipamento pago</span></p>
                        @elseif($item->valorPago)
                        <p><span class="badge pill-badge bg-success">Equipamento processado</span></p>
                        @else
                        <p><span class="badge pill-badge bg-warning">Aguardando orçamento</span></p>
                        @endif

                            @if($item->valorTotal)
                                <p><strong>Total:</strong> R$ {{ $item->valorTotal }}</span></p>
                            @endif

                            @if($item->desconto)
                                <p class="text-red"><strong>Desconto:</strong> {{ $item->desconto }}%</span></p>
                            @endif

                            @if($item->valorComDesconto)
                                <p><strong>subTotal:</strong> R$ {{ $item->valorComDesconto }}</span></p>
                            @endif

                            @if($item->valorPago)
                                <p class="text-green"><strong>Pago:</strong> R$ {{ $item->valorPago }}</span></p>
                            @endif

                            @if($item->valorTroco < 0 )
                                <p class="text-orange"><strong>Pagamento Pendente:</strong> R$ {{ $item->valorTroco }}</span></p>
                            @endif

                            <p class="text-muted">Em: {{ $item->updated_at->format('d/m/Y \a\s H:i:s') }}</p>
                      
                  

                    </div>
                </div>
            @endforeach
<button onclick="imprimirPDF()" type="button" class="btn btn-success mb-3 col-12 removerNaImpressao"><i class="fa-solid fa-file-pdf mr-2"></i>Imprimir PDF</button>
<a href="{{ URL::route('dashboard_ordem_servico', ['empresa'=>$empresa->name]) }}"><button class="btn bg-purple col-12 mb-5">VOLTAR</button></a>
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
