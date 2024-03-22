<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrativo Msoftware</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <!-- Fontawesome -->
    <script src="https://kit.fontawesome.com/b4b9e9fb10.js" crossorigin="anonymous"></script>
</head>

<body class="hold-transition login-page">

    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <img src="{{ asset('logos/' . $empresa->logo) }}" class="brand-image"
                style="width: 300px;">
            </div>
            <div class="card-body">

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

                <form action="{{ route('request_cadastro_empresa', ['empresa'=>$empresa->name]) }}" method="POST">
                    @csrf
                    <div class="input-group mb-3">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <i class="fa-solid fa-user"></i>
                            </div>
                        </div>
                        <input type="text" class="form-control" name="user_name" placeholder="Nome completo">
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        <input type="email" class="form-control" name="user_email" placeholder="Email">
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <input type="password" class="form-control" name="user_password" placeholder="Password">
                    </div>

                    <div class="input-group mb-2">
                        <button type="submit" class="btn btn-success col-12">Cadastrar</button>
                    </div>

                </form>

                <div class="input-group">
                    <a href="{{ URL::route('login_empresa', ['empresa'=>$empresa->name]) }}" class="col-12 btn btn-primary">Voltar</a>
                </div>

            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
</body>

</html>
