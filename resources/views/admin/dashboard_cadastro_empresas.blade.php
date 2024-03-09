@extends('admin.layouts.dashboard_menu_admin')

@section('title', 'Dashboard - Cadastro empresarial')

@section('content')


<div class="container mt-5">

    <form action="{{  route('dashboard_cadastro_empresa')  }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="form-row">
        <div class="form-group col-md-6 mb-3">
            <label for="logoInput">Logo da Empresa</label>
            <div class="custom-file">
              <input type="file" class="custom-file-input" name="file" id="logoInput" onchange="updateFileName(this)">
              <label class="custom-file-label" for="logoInput">Escolher arquivo</label>
            </div>
          </div>
          
        <div class="form-group col-md-6">
          <label for="nomeEmpresaInput">Nome da Empresa</label>
          <input type="text" class="form-control" name="name" id="nomeEmpresaInput">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="enderecoInput">Endereço</label>
          <input type="text" class="form-control" name="endereco" id="enderecoInput">
        </div>
        <div class="form-group col-md-6">
          <label for="nomeBancoInput">Nome do Banco de Dados</label>
          <input type="text" class="form-control" name="database_name" id="nomeBancoInput">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="hostBancoInput">Host do Banco de Dados</label>
          <input type="text" class="form-control" name="database_host" id="hostBancoInput">
        </div>
        <div class="form-group col-md-6">
          <label for="portaBancoInput">Porta do Banco de Dados</label>
          <input type="text" class="form-control" name="database_port" id="portaBancoInput">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="usuarioBancoInput">Usuário do Banco de Dados</label>
          <input type="text" class="form-control" name="database_username" id="usuarioBancoInput">
        </div>
        <div class="form-group col-md-6">
          <label for="senhaBancoInput">Senha do Banco de Dados</label>
          <input type="password" class="form-control" name="database_password" id="senhaBancoInput">
        </div>
      </div>
      <div class="form-group">
        <button type="submit" class="btn bg-gradient-success col-12">Cadastrar empresa</button>
      </div>
    </form>




    <div class="table-responsive">
      <table class="table table-hover">
          <thead class="thead-light">
              <tr>
                  <th class="rounded-left text-center">Logo Empresa</th>
                  <th class="text-center">Nome</th>
                  <th class="text-center">Host do Banco de Dados</th>
                  <th class="text-center">Porta do Banco de Dados</th>
                  <th class="text-center">Nome do Banco de Dados</th>
                  <th class="rounded-right text-center">Ações</th>
              </tr>
          </thead>
          <tbody>
              @foreach ($empresas as $empresa)
                  <tr>
                      <td class="align-middle text-center">
                          <img src="/logos/{{ $empresa->logo }}" width="100" class="img-thumbnail">
                      </td>
                      <td class="align-middle text-center">{{ $empresa->name }}</td>
                      <td class="align-middle text-center">{{ $empresa->database_host }}</td>
                      <td class="align-middle text-center">{{ $empresa->database_port }}</td>
                      <td class="align-middle text-center">{{ $empresa->database_name }}</td>
                      <td class="align-middle text-center">

                      <!-- Botão para abrir modal -->
                      <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#AtulizaDadosEmpresa_{{ $empresa->id }}">
                        <i class="fa-solid fa-eye"></i>
                      </button>

                      <!-- Modal de confirmação de exclusão -->
                      <div class="modal fade" id="AtulizaDadosEmpresa_{{ $empresa->id }}" tabindex="-1" role="dialog" aria-labelledby="AtulizaDadosEmpresa_{{ $empresa->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="AtulizaDadosEmpresa_{{ $empresa->id }}">Atualizando dados da empresa {{ $empresa->name }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" style="text-align: left;">
                                  <form action="{{  route('dashboard_atualiza_cadastro_empresa', ['id'=>$empresa->id])  }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-row">
                                      <div class="form-group col-md-6 mb-3">
                                          <label for="logoInput">Logo da Empresa</label>
                                          <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="file" id="logoInput" onchange="updateFileName(this)">
                                            <label class="custom-file-label" for="logoInput">Escolher arquivo</label>
                                          </div>
                                        </div>
                                        
                                      <div class="form-group col-md-6">
                                        <label for="nomeEmpresaInput">Nome da Empresa</label>
                                        <input type="text" class="form-control" name="name" id="nomeEmpresaInput" value="{{ $empresa->name }}">
                                      </div>
                                    </div>
                                    <div class="form-row">
                                      <div class="form-group col-md-6">
                                        <label for="enderecoInput">Endereço</label>
                                        <input type="text" class="form-control" name="endereco" id="enderecoInput" value="{{ $empresa->endereco }}">
                                      </div>
                                      <div class="form-group col-md-6">
                                        <label for="nomeBancoInput">Nome do Banco de Dados</label>
                                        <input type="text" class="form-control" name="database_name" id="nomeBancoInput"  value="{{ $empresa->database_name }}">
                                      </div>
                                    </div>
                                    <div class="form-row">
                                      <div class="form-group col-md-6">
                                        <label for="hostBancoInput">Host do Banco de Dados</label>
                                        <input type="text" class="form-control" name="database_host" id="hostBancoInput"  value="{{ $empresa->database_host }}">
                                      </div>
                                      <div class="form-group col-md-6">
                                        <label for="portaBancoInput">Porta do Banco de Dados</label>
                                        <input type="text" class="form-control" name="database_port" id="portaBancoInput" value="{{ $empresa->database_port }}">
                                      </div>
                                    </div>
                                    <div class="form-row">
                                      <div class="form-group col-md-6">
                                        <label for="usuarioBancoInput">Usuário do Banco de Dados</label>
                                        <input type="text" class="form-control" name="database_username" id="usuarioBancoInput" value="{{ $empresa->database_username }}">
                                      </div>
                                      <div class="form-group col-md-6">
                                        <label for="senhaBancoInput">Senha do Banco de Dados</label>
                                        <input type="password" class="form-control" name="database_password" id="senhaBancoInput" >
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <button type="submit" class="btn bg-gradient-success col-12">Cadastrar empresa</button>
                                    </div>
                                  </form>
                                </div>
                            </div>
                        </div>
                      </div>
                      <!-- Fim do modal -->

                      <a class="btn btn-primary" target="_blank" href="{{ route('login_empresa', ['empresa'=>$empresa->name]) }}"><i class="fa-solid fa-door-open"></i></a>


                      <!-- Botão para abrir modal -->
                      <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal{{ $empresa->id }}">
                          <i class="fa-solid fa-trash-can"></i>
                      </button>

                      <!-- Modal de confirmação de exclusão -->
                      <div class="modal fade" id="confirmDeleteModal{{ $empresa->id }}" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel{{ $empresa->id }}" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                  <div class="modal-header">
                                      <h5 class="modal-title" id="confirmDeleteModalLabel{{ $empresa->id }}">Confirmar Exclusão</h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                          <span aria-hidden="true">&times;</span>
                                      </button>
                                  </div>
                                  <div class="modal-body">
                                      Tem certeza de que deseja excluir a empresa "{{ $empresa->name }}"?
                                  </div>
                                  <div class="modal-footer">
                                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                      <form action="{{ route('delete_cadastro_empresa', ['id'=>$empresa->id]) }}" method="POST" style="display: inline-block;">
                                          @csrf
                                          <button type="submit" class="btn btn-danger">Excluir</button>
                                      </form>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <!-- Fim do modal -->

                      </td>
                  </tr>
              @endforeach
          </tbody>
      </table>
  </div>
  

  </div>

<script>
function updateFileName(input) {
    var fileName = input.files[0].name;
    var label = input.nextElementSibling;
    label.innerText = fileName;
}
</script>
@endsection