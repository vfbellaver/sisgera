<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


use Sisgera\Sisgera;

Route::get('/', function () {
    return redirect()->route('login');
});

// Sisgera
Route::get('sisgera.js', function () {
    $json = json_encode(array_merge(Sisgera::scriptVariables(), []));
    $js = <<<js
    window.Sisgera = {$json};
js;
    return response($js)->header('Content-Type', 'text/javascript');
})->name('sisgera.js');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//INVITATION
Route::post('registro/cadastro', 'Auth\RegisterController@registerInvitation')->name('registro-cadastro');
Route::get('registro/cadastro/{token}', 'Auth\RegisterController@invitation')->name('cadastro');


//USERS
Route::resource('user', 'Api\UsuarioController');
Route::get('current/user', 'UsuarioController@getCurrentUser')->name('current.user');
Route::get('configuracoes/perfil', 'PerfilController@perfilUsuario')->name('perfil-usuario');
Route::put('perfil/{user}/password', 'PerfilController@atualizaPassword')->name('atualiza.password');

//CONTAS
Route::resource('contas', 'Api\ContaController',['except'=>['show']]);
Route::get('lista/contas', 'Api\ContaController@listaContas')->name('lista-contas');
Route::get('todas/contas', 'Api\ContaController@todasContas')->name('lista-todas');

//RETORNA OS USUARIOS
Route::get('get/coord', 'UsuarioController@getCoordenadores')->name('get-coordenadores');
Route::get('get/alunos', 'UsuarioController@getAlunos')->name('get-alunos');
Route::get('get/cerel', 'UsuarioController@getCerel')->name('get-cerel');
Route::get('get/publico/externo', 'UsuarioController@getExterno')->name('get-externo');

Route::get('usuarios/coordenadores', 'UsuarioController@listaCoordenadores')->name('lista-coordenadores');
Route::get('usuarios/alunos', 'UsuarioController@listaAlunos')->name('lista-alunos');
Route::get('usuarios/cerel', 'UsuarioController@listaCerel')->name('lista-cerel');
Route::get('usuarios/externo', 'UsuarioController@listaExterno')->name('lista-externo');

//REQUERIMENTOS DO USUARIO LOGADO
Route::resource('requerimento', 'Api\RequerimentoController');
Route::get('get/meus/enviados', 'MeusRequerimentosController@getEnviados')->name('meus-req-enviados');
Route::get('get/meus/recebidos', 'MeusRequerimentosController@getRecebidos')->name('meus-req-recebidos');
Route::get('get/meus/deferidos', 'MeusRequerimentosController@getDeferidos')->name('meus-req-deferidos');
Route::get('get/meus/indeferidos', 'MeusRequerimentosController@getIndeferidos')->name('meus-req-indeferidos');

Route::get('tipo/solicitacao', 'MeusRequerimentosController@TiposdeSolicitacao')->name('tipos-solicitacao');
Route::get('meus/requerimentos/enviados', 'MeusRequerimentosController@requerimentosEnviados')->name('meus-requerimentos-enviados');
Route::get('meus/requerimentos/recebidos', 'MeusRequerimentosController@requerimentosRecebidos')->name('meus-requerimentos-recebidos');
Route::get('meus/requerimentos/deferidos', 'MeusRequerimentosController@requerimentosDeferidos')->name('meus-requerimentos-deferidos');
Route::get('meus/requerimentos/indeferidos', 'MeusRequerimentosController@requerimentosIndeferidos')->name('meus-requerimentos-indeferidos');


//TODOS REQUERIMENTOS INDEPENDENTE DE USUARIO LOGADO
Route::get('requerimentos/enviados','RequerimentosController@requerimentosEnviados')->name('view-req-enviados');
Route::get('get/requerimentos/enviados','RequerimentosController@getEnviados')->name('get-requerimentos-enviados');

Route::get('requerimentos/recebidos','RequerimentosController@requerimentosRecebidos')->name('view-req-recebidos');
Route::get('get/requerimentos/recebidos','RequerimentosController@getRecebidos')->name('get-requerimentos-recebidos');

Route::get('requerimentos/deferidos','RequerimentosController@requerimentosDeferidos')->name('view-req-deferidos');
Route::get('get/requerimentos/deferidos','RequerimentosController@getDeferidos')->name('get-requerimentos-deferidos');

Route::get('requerimentos/deferidos/parcialmente','RequerimentosController@requerimentosDeferidosParcialmente')->name('view-req-deferidos-parcialmente');
Route::get('get/requerimentos/parcialmente','RequerimentosController@getDeferidosParcialmente')->name('get-requerimentos-deferidos-parcialmente');

Route::get('requerimentos/indeferidos','RequerimentosController@requerimentosIndeferidos')->name('view-req-indeferidos');
Route::get('get/requerimentos/indeferidos','RequerimentosController@getIndeferidos')->name('get-requerimentos-indeferidos');

Route::get('requerimentos/todos','RequerimentosController@requerimentosList')->name('view-req-list');
Route::get('get/requerimentos/todos','RequerimentosController@getAll')->name('get-requerimentos-todos');

//ANEXO UPLOAD
Route::post('anexo/upload', 'FileUploadController@fileUpload')->name('file.upload');
