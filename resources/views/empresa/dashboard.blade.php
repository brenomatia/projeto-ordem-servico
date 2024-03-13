@extends('empresa.layouts.dashboard_empresa_menu')

@section('title', 'Dashboard')

@section('content')

    <div class="container col-11 mt-4">
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>R$ {{ number_format($sumMensal, 2, ',', '.') }}</h3>
                        <p>FATURAMENTO</p>
                    </div>
                    <div class="icon">
                        <i class="fa-solid fa-file-invoice-dollar mt-3 mr-1" style="font-size: 60px;"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $atendimentoTotal }}</h3>
                        <p>CLIENTES ATENDIDOS</p>
                    </div>
                    <div class="icon">
                        <i class="fa-solid fa-people-carry-box mt-3 mr-1" style="font-size: 60px;"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>R$ {{ number_format($sumMensal / $atendimentoTotal, 2, ',', '.') }}</h3>
                        <p>TIQUETE MÉDIO</p>
                    </div>
                    <div class="icon">
                        <i class="fa-solid fa-hand-holding-dollar mt-3 mr-1" style="font-size: 60px;"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $sumOrdem }}</h3>
                        <p>O.S EM ABERTO</p>
                    </div>
                    <div class="icon">
                        <i class="fa-regular fa-clock mt-3 mr-1" style="font-size: 60px;"></i>
                    </div>
                </div>
            </div>

        </div>

        <div class="container col-12" style="justify-content: center;">
            <form action="{{ route('dashboard_empresa_pesquisa_personalizada', ['empresa' => $empresa->name]) }}"
                method="GET" class="mt-5">
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                            <input type="date" class="form-control" name="start_date" placeholder="Data de Início" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                            <input type="date" class="form-control" name="end_date" placeholder="Data Final" required>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success col-12"><i class="fas fa-search"></i> Pesquisar</button>
            </form>
        </div>


        <div class="row mt-5">
            <div class="col-md-6 mt-2 mb-2">
                <div class="card">
                    <div class="container" style="text-align: center"><label class="p-3">Comparação semanal</label></div>
                    <div class="chart p-3" style="border: 1px solid #dee2e6; padding: 10px;">
                        <canvas id="barChart1"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-2 mb-2">
                <div class="card">
                    <div class="container" style="text-align: center"><label class="p-3">Vendas mensal</label></div>
                    <div class="chart p-3" style="border: 1px solid #dee2e6; padding: 10px;">
                        <canvas id="barChart2"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-2 mb-2">
                <div class="card">
                    <div class="container" style="text-align: center"><label class="p-3">Porcentagens por meio de pagamentos</label></div>
                    <div class="chart  p-3" style="border: 1px solid #dee2e6; padding: 10px;">
                        <canvas id="donutChart1"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-2 mb-2">
                <div class="card">
                    <div class="container" style="text-align: center"><label class="p-3">Fonte de receita</label></div>
                    <div class="chart p-3" style="border: 1px solid #dee2e6; padding: 10px;">
                        <canvas id="donutChart2"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-5">
            <div class="card-header">
                <h3 class="card-title">TOP VENDAS PRODUTOS</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Quantidade</th>
                                <th>Ultima venda por</th>
                                <th>Valor Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topProdutos as $produto)
                                <tr>
                                    <td>{{ $produto->cod_produto }}</td>
                                    <td>{{ $produto->qtd_produto }}</td>
                                    <td>{{ $produto->user->name }}</td>
                                    <td>R$ {{ number_format($produto->valorTotal, 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.card-body -->
        </div>



    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('barChart1').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
                    datasets: [{
                        label: 'Semana atual',
                        data: @json($vendasSemanaAtual),
                        backgroundColor: '#28A745',
                        borderColor: '#28A745',
                        borderWidth: 1
                    }, {
                        label: 'Semana Passada',
                        data: @json($vendasSemanaPassada),
                        backgroundColor: '#DC3545',
                        borderColor: '#DC3545',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        yAxes: [{
                            ticks: {
                                callback: function(value, index, values) {
                                    return 'R$ ' + value.toFixed(2);
                                }
                            }
                        }]
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                var datasetLabel = data.datasets[tooltipItem.datasetIndex].label || '';
                                var value = tooltipItem.yLabel;
                                return datasetLabel + ': R$ ' + value.toFixed(2);
                            }
                        }
                    }
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('barChart2').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: 'Total de Vendas mensal',
                        data: @json($totalVendas),
                        backgroundColor: 'rgba(54, 162, 235, 0.2)', // Azul claro
                        borderColor: 'rgba(54, 162, 235, 1)', // Azul
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                callback: function(value, index, values) {
                                    return 'R$ ' + value.toFixed(2);
                                }
                            }
                        }]
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                var datasetLabel = data.datasets[tooltipItem.datasetIndex].label || '';
                                var value = tooltipItem.yLabel;
                                return datasetLabel + ': R$ ' + value.toFixed(2);
                            }
                        }
                    }
                }
            });
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('donutChart1').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: @json(array_keys($porcentagens)),
                    datasets: [{
                        data: @json(array_values($porcentagens)),
                        backgroundColor: [
                            '#ff6384', // Vermelho
                            '#36a2eb', // Azul
                            '#ffce56', // Amarelo
                            '#4bc0c0', // Verde-azulado
                            '#9966ff', // Roxo
                            '#ff9f40', // Laranja
                            '#ffcd56', // Amarelo claro
                            '#ff6384', // Vermelho (repetido)
                            '#36a2eb', // Azul (repetido)
                            '#ffce56' // Amarelo (repetido)
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    legend: {
                        position: 'right'
                    },
                    cutoutPercentage: 70,
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                var datasetLabel = data.labels[tooltipItem.index];
                                var value = data.datasets[0].data[tooltipItem.index];
                                return datasetLabel + ': ' + value.toFixed(2) + '%';
                            }
                        }
                    }
                }
            });
        });
    </script>






<script>
    document.addEventListener('DOMContentLoaded', function() {
        var vendasOrdem = {!! json_encode($vendasOrdemValor->toArray()) !!};
        var transactionsProdutos = {!! $transactionsProdutosValor !!};

        // Convertendo os dados em arrays para processamento no gráfico
        var vendasOrdemTipos = Object.keys(vendasOrdem);
        var vendasOrdemValores = Object.values(vendasOrdem).map(function(value) {
            return parseFloat(value); // Convertendo para número decimal
        });

        var ctx2 = document.getElementById('donutChart2').getContext('2d');
        var chart2 = new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: vendasOrdemTipos.concat('PRODUTOS'),
                datasets: [{
                    data: vendasOrdemValores.concat(transactionsProdutos),
                    backgroundColor: [
                        'rgba(125, 217, 255, 0.7)', // Azul claro para transações de produtos
                        'rgba(255, 99, 132, 0.7)', // Vermelho para vendas de ordens
                        'rgba(255, 206, 86, 0.7)', // Amarelo para transações de produtos
                        'rgba(125, 217, 255, 0.7)', // Azul claro para transações de produtos
                    ],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                legend: {
                    position: 'right'
                },
                cutoutPercentage: 70,
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var datasetIndex = tooltipItem.datasetIndex;
                            var dataIndex = tooltipItem.index;

                            if (datasetIndex === 0) {
                                var valorLabel = vendasOrdemTipos[dataIndex];
                                if (valorLabel === undefined) {
                                    return 'PRODUTOS' + ': R$ ' + data.datasets[datasetIndex].data[dataIndex].toFixed(2);
                                } else {
                                    return valorLabel + ': R$ ' + data.datasets[datasetIndex].data[dataIndex].toFixed(2);
                                }
                            } else {
                                var datasetLabel = data.labels[datasetIndex];
                                var value = data.datasets[datasetIndex].data[dataIndex];
                                return datasetLabel + ': R$ ' + value.toFixed(2);
                            }
                        }
                    }
                }
            }
        });
    });
</script>







@endsection
