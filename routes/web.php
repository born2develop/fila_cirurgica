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

/* Routing without controller

Route::get('/', function () {
    return view('welcome');
}); 

*/

// Authentication Routes...
$this->get('/', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('/', 'Auth\LoginController@login');
$this->post('logout', 'Auth\LoginController@logout')->name('logout');
// Registration Routes...
$this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');

// System routes
Route::get('home', 'HomeController@index')->name('home');

Route::group(['prefix'=>'pedidos'], function() {
	Route::get('cadastrar', 'PedidoController@create')->name('pedidos.create');
	Route::post('store', 'PedidoController@store')->name('pedidos.store');
	Route::get('{id}/destroy', 'PedidoController@destroy')->name('pedidos.destroy');
	Route::get('{id}', 'PedidoController@edit')->name('pedidos.edit');
	Route::get('{id}/update', 'PedidoController@update')->name('pedidos.update');
	Route::post('setId', 'PedidoController@setId')->name('setId');
});

// Route::match(['get', 'post'], 'paciente/{id}', 'PacienteController@find')->name('paciente');
Route::match(['get', 'post'], 'prontuario/{prontuario}', 'PacienteController@find')->name('prontuario');
Route::match(['get', 'post'], 'paciente/{paciente}', 'PacienteController@find_by_cod_paciente')->name('paciente');
Route::match(['get', 'post'], 'cid/{id}', 'PedidoController@cidFind')->name('find_cid');


Route::get('json_cids',array('as'=>'json_cids','uses'=>'PedidoController@dataAjaxCid'));
Route::get('json_pacientes', 'PedidoController@dataAjaxDadosPaciente')->name('json_pacientes');
Route::get('json_procedimentos', 'PedidoController@dataAjaxProcedimentosCirurgicos')->name('json_procedimentos');



//Route::get('search',array('as'=>'search','uses'=>'SearchController@search'));
//Route::get('ajax',array('as'=>'autocomplete','uses'=>'SearchController@dataAjax'));

//Route::get('autocomplete',array('as'=>'autocomplete','uses'=>'FormController@index'));
//Route::get('searchajax',array('as'=>'searchajax','uses'=>'FormController@autoComplete'));