<?php

use App\Http\Controllers\DashController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;




Route::get('/', [LoginController::class, 'login'])->name('login');
Route::get('/cadastro', [LoginController::class, 'cadastro'])->name('cadastro');
Route::post('/cadastro/registrando', [LoginController::class, 'cadastro_registrando_user'])->name('cadastro_registrando_user');
Route::post('/acessando', [DashController::class, 'acessando_request'])->name('acessando_request');

Route::get('/dashboard', [DashController::class, 'dashboard'])->name('dashboard')->middleware('auth');
Route::post('/dashboard/cadastro_empresa', [DashController::class, 'dashboard_cadastro_empresa'])->name('dashboard_cadastro_empresa')->middleware('auth');
Route::post('/dashboard/atualiza_cadastro_empresa/{id}', [DashController::class, 'dashboard_atualiza_cadastro_empresa'])->name('dashboard_atualiza_cadastro_empresa')->middleware('auth');
Route::post('/dashboard/delete_cadastro_empresa/{id}', [DashController::class, 'delete_cadastro_empresa'])->name('delete_cadastro_empresa')->middleware('auth');
Route::get('/logout', [DashController::class, 'logout'])->name('logout')->middleware('auth');




Route::middleware('empresas')->group(function () {

    Route::get('/empresa/{empresa}', [EmpresaController::class, 'login_empresa'])->name('login_empresa');
    Route::get('/empresa/{empresa}/cadastro', [EmpresaController::class, 'cadastro_empresa'])->name('cadastro_empresa');
    Route::post('/empresa/{empresa}/cadastro/registrando', [EmpresaController::class, 'request_cadastro_empresa'])->name('request_cadastro_empresa');
    Route::post('/empresa/{empresa}/acessando_dashboard', [EmpresaController::class, 'request_login_empresa'])->name('request_login_empresa');

    Route::get('/empresa/{empresa}/dashboard', [EmpresaController::class, 'dashboard_empresa'])->name('dashboard_empresa');
    Route::get('/empresa/{empresa}/dashboard/personalizado', [EmpresaController::class, 'dashboard_empresa_pesquisa_personalizada'])->name('dashboard_empresa_pesquisa_personalizada');

    Route::get('/empresa/{empresa}/cadastro_cliente', [EmpresaController::class, 'dashboard_cadastro_cliente'])->name('dashboard_cadastro_cliente');
    Route::post('/empresa/{empresa}/cadastro_cliente/import_csv', [EmpresaController::class, 'dashboard_import_csv'])->name('dashboard_import_csv');
    Route::get('/empresa/{empresa}/cadastro_cliente/pesquisa', [EmpresaController::class, 'dashboard_cadastro_cliente_pesquisa'])->name('dashboard_cadastro_cliente_pesquisa');
    Route::post('/empresa/{empresa}/cadastro_cliente/cadastrando', [EmpresaController::class, 'dashboard_cadastrando_cliente'])->name('dashboard_cadastrando_cliente');
    Route::post('/empresa/{empresa}/cadastro_cliente/deletar_cliente/{id}', [EmpresaController::class, 'dashboard_deletando_cliente'])->name('dashboard_deletando_cliente');
    Route::post('/empresa/{empresa}/cadastro_cliente/atualizar_cliente/{id}', [EmpresaController::class, 'dashboard_atualizar_cliente'])->name('dashboard_atualizar_cliente');
    
    Route::get('/empresa/{empresa}/revendas', [EmpresaController::class, 'dashboard_revendas'])->name('dashboard_revendas');
    Route::post('/empresa/{empresa}/revendas/cadastro', [EmpresaController::class, 'dashboard_cadastro_revendas'])->name('dashboard_cadastro_revendas');
    Route::post('/empresa/{empresa}/revendas/atualizar/{id}', [EmpresaController::class, 'dashboard_atualizar_revendas'])->name('dashboard_atualizar_revendas');
    Route::post('/empresa/{empresa}/revendas/deletar/{id}', [EmpresaController::class, 'dashboard_deletar_revendas'])->name('dashboard_deletar_revendas');
    
    Route::get('/empresa/{empresa}/produtos', [EmpresaController::class, 'dashboard_produtos'])->name('dashboard_produtos');
    Route::post('/empresa/{empresa}/produtos/cadastro', [EmpresaController::class, 'dashboard_produtos_cadastro'])->name('dashboard_produtos_cadastro');
    Route::post('/empresa/{empresa}/produtos/import_csv', [EmpresaController::class, 'dashboard_produto_import_csv'])->name('dashboard_produto_import_csv');
    Route::get('/empresa/{empresa}/produtos/cadastro/pesquisando', [EmpresaController::class, 'dashboard_produtos_pesquisa'])->name('dashboard_produtos_pesquisa');
    Route::post('/empresa/{empresa}/produtos/atualizar/{id}', [EmpresaController::class, 'dashboard_produtos_atualizar'])->name('dashboard_produtos_atualizar');
    Route::post('/empresa/{empresa}/produtos/deletar/{id}', [EmpresaController::class, 'dashboard_produtos_deletar'])->name('dashboard_produtos_deletar');

    Route::get('/empresa/{empresa}/ordem', [EmpresaController::class, 'dashboard_ordem_servico'])->name('dashboard_ordem_servico');
    Route::get('/empresa/{empresa}/ordem/set-open-card/{cardId}/{id_ordem}', [EmpresaController::class, 'setOpenCard'])->name('setOpenCard');
    Route::get('/empresa/{empresa}/ordem/buscar_cliente', [EmpresaController::class, 'dashboard_ordem_buscar_cliente'])->name('dashboard_ordem_buscar_cliente');
    Route::get('/empresa/{empresa}/ordem/get_ordem_servico', [EmpresaController::class, 'dashboard_buscar_ordem'])->name('dashboard_buscar_ordem');
    Route::post('/empresa/{empresa}/ordem/cadastro_equipamento', [EmpresaController::class, 'dashboard_ordem_cadastro_equipamento'])->name('dashboard_ordem_cadastro_equipamento');
    Route::get('/empresa/{empresa}/ordem/deletando_equipamento/{id}', [EmpresaController::class, 'deletando_equipamento'])->name('deletando_equipamento');
    Route::post('/empresa/{empresa}/ordem/cadastrando_ordem', [EmpresaController::class, 'dashboard_cadastrando_ordem'])->name('dashboard_cadastrando_ordem');

    Route::post('/empresa/{empresa}/ordem/cancelamento/{id_ordem}', [EmpresaController::class, 'dashboard_ordem_cancelamento'])->name('dashboard_ordem_cancelamento');

    Route::post('/empresa/{empresa}/ordem/deletar/{id_ordem}', [EmpresaController::class, 'dashboard_ordem_deletar_registro'])->name('dashboard_ordem_deletar_registro');
    Route::get('/empresa/{empresa}/ordem/cadastrando_ordem/atualizar_status/{id_ordem}/equipamento/{id}', [EmpresaController::class, 'dashboard_ordem_atualiza_status'])->name('dashboard_ordem_atualiza_status');
    Route::get('/empresa/{empresa}/ordem/atualizar_status_select/{id_ordem}/equipamento/{id}', [EmpresaController::class, 'dashboard_ordem_atualizar_status_select'])->name('dashboard_ordem_atualizar_status_select');
    Route::post('/empresa/{empresa}/ordem/atualizar/{id_ordem}/equipamento/{id}', [EmpresaController::class, 'dashboard_ordem_atualizar_equipamento'])->name('dashboard_ordem_atualizar_equipamento');
    Route::post('/empresa/{empresa}/ordem/atualizar/{id_ordem}/equipamento/{id}/garantia', [EmpresaController::class, 'dashboard_ordem_atualizar_garantia_equipamento'])->name('dashboard_ordem_atualizar_garantia_equipamento');
    Route::post('/empresa/{empresa}/ordem/atualizar/{id_ordem}/equipamento/{id}/dados_status', [EmpresaController::class, 'dashboard_ordem_atualizar_dados_status'])->name('dashboard_ordem_atualizar_dados_status');
    Route::post('/empresa/{empresa}/ordem/atualizar/{id_ordem}/equipamento/{id}/nao_autorizado', [EmpresaController::class, 'dashboard_ordem_atualizar_status_nao_autorizado'])->name('dashboard_ordem_atualizar_status_nao_autorizado');
    
    Route::get('/empresa/{empresa}/ordem/listagem/{id_ordem}/equipamento/{id_equipamento}', [EmpresaController::class, 'dashboard_listar_items_ordem'])->name('dashboard_listar_items_ordem');

    Route::get('/empresa/{empresa}/ordem/listagem/{id_ordem}/buscar_produto', [EmpresaController::class, 'dashboard_ordem_buscar_produto'])->name('dashboard_ordem_buscar_produto');
    Route::post('/empresa/{empresa}/ordem/listagem/{id_ordem}/buscar_produto/listar_item', [EmpresaController::class, 'dashboard_os_listar_item'])->name('dashboard_os_listar_item');
    Route::post('/empresa/{empresa}/ordem/listagem/{id_ordem}/buscar_produto/sub_item', [EmpresaController::class, 'dashboard_os_sub_item'])->name('dashboard_os_sub_item');
    Route::post('/empresa/{empresa}/ordem/listagem/{id_ordem}/buscar_produto/deletar_item/{id}', [EmpresaController::class, 'dashboard_os_deletar_item'])->name('dashboard_os_deletar_item');
    Route::post('/empresa/{empresa}/ordem/listagem/{id_ordem}/cadastro_mao_de_obra', [EmpresaController::class, 'dashboard_cadastro_mao_de_obra'])->name('dashboard_cadastro_mao_de_obra');
    Route::post('/empresa/{empresa}/ordem/listagem/{id_ordem}/cadastro_mao_de_obra/deletar/{id}', [EmpresaController::class, 'dashboard_deletar_mao_de_obra'])->name('dashboard_deletar_mao_de_obra');
    Route::post('/empresa/{empresa}/ordem/listagem/{id_ordem}/cadastro_anotacao', [EmpresaController::class, 'dashboard_anotacao_os'])->name('dashboard_anotacao_os');
    Route::post('/empresa/{empresa}/ordem/listagem/{id_ordem}/aguardando_pecas/{id_equipamento}', [EmpresaController::class, 'dashboard_aguardando_pecas'])->name('dashboard_aguardando_pecas');

    Route::post('/empresa/{empresa}/ordem/listagem/{id_ordem}/processar_item/{id_equipamento}', [EmpresaController::class, 'dashboard_processa_item'])->name('dashboard_processa_item');

    Route::post('/empresa/{empresa}/ordem/listagem/{id_ordem}/equipamento/{id_equipamento}/substatus', [EmpresaController::class, 'dashboard_substatus'])->name('dashboard_substatus');
    


    Route::post('/empresa/{empresa}/ordem/cadastrando_ordem/listar_terceiros/{id_ordem}/cadastro', [EmpresaController::class, 'dashboard_cadastro_terceiros'])->name('dashboard_cadastro_terceiros');
    Route::post('/empresa/{empresa}/ordem/cadastrando_ordem/listar_terceiros/{id_ordem}/atualizar/{id_terceiro}', [EmpresaController::class, 'dashboard_atualizar_terceiros'])->name('dashboard_atualizar_terceiros');
    Route::post('/empresa/{empresa}/ordem/cadastrando_ordem/listar_terceiros/{id_ordem}/deletar/{id_terceiro}', [EmpresaController::class, 'dashboard_deletar_terceiros'])->name('dashboard_deletar_terceiros');
    Route::post('/empresa/{empresa}/ordem/cadastrando_ordem/atualizar/{id_ordem}', [EmpresaController::class, 'dashboard_ordem_atualizar_dados'])->name('dashboard_ordem_atualizar_dados');
    Route::post('/empresa/{empresa}/ordem/deletar_ordem/{id}', [EmpresaController::class, 'dashboard_deletar_ordem'])->name('dashboard_deletar_ordem');
    Route::get('/empresa/{empresa}/ordem/gerar/pdf/{id_ordem}', [EmpresaController::class, 'dashboard_gerador_pdf_route'])->name('dashboard_gerador_pdf_route');

    Route::get('/empresa/{empresa}/ordem/genProtocolo/{id_ordem}', [EmpresaController::class, 'dashboard_gen_protocolo'])->name('dashboard_gen_protocolo');
    
    Route::get('/empresa/{empresa}/vendas', [EmpresaController::class, 'dashboard_vendas'])->name('dashboard_vendas');
    Route::get('/empresa/{empresa}/vendas/get_busca_produto', [EmpresaController::class, 'dashboard_get_busca_produto'])->name('dashboard_get_busca_produto');

    Route::post('/empresa/{empresa}/vendas/busca_os', [EmpresaController::class, 'dashboard_vendas_busca_os'])->name('dashboard_vendas_busca_os');
    Route::post('/empresa/{empresa}/vendas/deletar_os/{id}', [EmpresaController::class, 'dashboard_vendas_deletar_os'])->name('dashboard_vendas_deletar_os');
    Route::post('/empresa/{empresa}/vendas/busca_produto', [EmpresaController::class, 'dashboard_vendas_busca_produto'])->name('dashboard_vendas_busca_produto');
    Route::post('/empresa/{empresa}/vendas/deletar_produto/{id}', [EmpresaController::class, 'dashboard_vendas_deletar_produto'])->name('dashboard_vendas_deletar_produto');
    Route::post('/empresa/{empresa}/vendas/request_ordem', [EmpresaController::class, 'dashboard_vendas_request_modal_ordem_servico'])->name('dashboard_vendas_request_modal_ordem_servico');
    Route::post('/empresa/{empresa}/vendas/request_vendas', [EmpresaController::class, 'dashboard_vendas_request_modal_produtos'])->name('dashboard_vendas_request_modal_produtos');
    Route::post('/empresa/{empresa}/vendas/concluidas_produtos', [EmpresaController::class, 'dashboard_vendas_request_produtos_concluidas'])->name('dashboard_vendas_request_produtos_concluidas');
    Route::post('/empresa/{empresa}/vendas/concluidas_ordem', [EmpresaController::class, 'dashboard_vendas_request_ordem_concluidas'])->name('dashboard_vendas_request_ordem_concluidas');
    Route::post('/empresa/{empresa}/vendas/excluir_venda/{hash}', [EmpresaController::class, 'dashboard_vendas_detalhes_excluir_venda'])->name('dashboard_vendas_detalhes_excluir_venda');

    Route::post('/empresa/{empresa}/vendas/dados_garantia/{hash}', [EmpresaController::class, 'dashboard_dados_garantia'])->name('dashboard_dados_garantia');


    Route::get('/empresa/{empresa}/logout', [EmpresaController::class, 'logout_empresa'])->name('logout_empresa');
});