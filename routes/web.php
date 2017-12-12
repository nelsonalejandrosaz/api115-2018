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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'auth'], function () {
    /**
     * Rutas de producto
     */
    Route::name('productoLista')->get('/producto','ProductoController@ProductoLista');
    Route::name('productoNuevo')->get('/producto/nuevo','ProductoController@ProductoNuevo');
    Route::name('productoNuevoPost')->post('/producto/nuevo','ProductoController@ProductoNuevoPost');
    Route::name('productoVer')->get('/producto/{id}','ProductoController@ProductoVer');
    Route::name('productoEditar')->get('/producto/{id}/editar','ProductoController@ProductoEditar');
    Route::name('productoEditarPut')->put('/producto/{id}','ProductoController@ProductoEditarPut');
    Route::name('productoEliminar')->delete('/producto/{id}','ProductoController@ProductoEliminar');

    /**
     * Rutas de categoria
     */
    Route::name('categoriaLista')->get('/categoria','CategoriaController@CategoriaLista');
    Route::name('categoriaNuevo')->get('/categoria/nuevo','CategoriaController@CategoriaNuevo');
    Route::name('categoriaNuevoPost')->post('/categoria/nuevo','CategoriaController@CategoriaNuevoPost');
    Route::name('categoriaVer')->get('/categoria/{id}','CategoriaController@CategoriaVer');
    Route::name('categoriaEditar')->get('/categoria/{id}/editar','CategoriaController@CategoriaEditar');
    Route::name('categoriaEditarPut')->put('/categoria/{id}','CategoriaController@CategoriaEditarPut');
    Route::name('categoriaEliminar')->delete('/categoria/{id}','CategoriaController@CategoriaEliminar');
});
