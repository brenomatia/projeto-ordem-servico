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


    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center" style="background-color: #343A40;">
        <img class="animation__shake" src="{{ asset('logos/logo.png') }}" height="128" width="128">
    </div>

    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <img class="" src="{{ asset('logos/logo2.png') }}" height="128" width="128">
            </div>
            <div class="card-body">

                @if (session('success'))
                    <div id="successAlert" class="alert alert-success">
                        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div id="errorAlert" class="alert alert-danger">
                        <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
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


                <form action="{{ route('acessando_request') }}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" name="user_email" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="user_password" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-2">
                        <button type="submit" class="btn btn-success col-12">Entrar</button>
                    </div>

                </form>

                <div class="input-group">
                    <a href="{{ URL::to('cadastro') }}" class="col-12 btn btn-primary">Cadastrar</a>
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
