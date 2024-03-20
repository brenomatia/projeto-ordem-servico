@extends('empresa.layouts.dashboard_empresa_menu')

@section('title', 'Dashboard')

@section('content')

<div class="container" id="conteudoParaImprimir">
    <div class="container col-11">
    <img src="{{ asset('logos/' . $empresa->logo) }}" style="width: 248px;" />
    <div class="row">
        <div class="col-md-6">
            <p>Empresa: <strong>{{ $empresa->name }}</strong></p>
            <p>Endereço: <strong>{{ $empresa->endereco }}</strong></p>
            <p>Data: <strong>{{ $ordem->created_at->format('d/m/Y \a\s H:i:s') }}</strong></p>
        </div>
        <div class="col-md-6">
            <h5>Dados do Cliente</h5>
            <p>Nome: <strong>{{ $ordem->nome_cliente }}</strong></p>
            <p>Nº ordem: <strong>{{ $ordem->id }}</strong></p>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header bg-dark">
            <h5 class="card-title text-white">EQUIPAMENTOS DEIXADOS:</h5>
        </div>
        <div class="card-body">
            @foreach($itens as $item)
                <div class="card mb-3">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="card-title">{{ $item->equipamento }}</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text"><strong>Atendimento efetuado por:</strong> {{ $item->q_aut }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    

<button onclick="imprimirPDF()" type="button" class="btn bg-primary mb-5 col-12 removerNaImpressao"><i class="fa-solid fa-file-pdf mr-2"></i>Imprimir Protocolo</button>

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
