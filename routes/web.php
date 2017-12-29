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
    return redirect()->route('login');
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

    /**
     * Rutas de proveedores
     */
    Route::name('proveedorLista')->get('/proveedor','ProveedorController@ProveedorLista');
    Route::name('proveedorNuevo')->get('/proveedor/nuevo','ProveedorController@ProveedorNuevo');
    Route::name('proveedorNuevoPost')->post('/proveedor/nuevo','ProveedorController@ProveedorNuevoPost');
    Route::name('proveedorVer')->get('/proveedor/{id}','ProveedorController@ProveedorVer');
    Route::name('proveedorEditar')->get('/proveedor/{id}/editar','ProveedorController@ProveedorEditar');
    Route::name('proveedorEditarPut')->put('/proveedor/{id}','ProveedorController@ProveedorEditarPut');
    Route::name('proveedorEliminar')->delete('/proveedor/{id}','ProveedorController@ProveedorEliminar');

    /**
     * Rutas de cliente
     */
    Route::name('clienteLista')->get('/cliente','ClienteController@ClienteLista');
    Route::name('clienteNuevo')->get('/cliente/nuevo','ClienteController@ClienteNuevo');
    Route::name('clienteNuevoPost')->post('/cliente/nuevo','ClienteController@ClienteNuevoPost');
    Route::name('clienteVer')->get('/cliente/{id}','ClienteController@ClienteVer');
    Route::name('clienteEditar')->get('/cliente/{id}/editar','ClienteController@ClienteEditar');
    Route::name('clienteEditarPut')->put('/cliente/{id}','ClienteController@ClienteEditarPut');
    Route::name('clienteEliminar')->delete('/cliente/{id}','ClienteController@ClienteEliminar');

    /**
     * Movimientos
     */
    Route::name('compraLista')->get('/compra','CompraController@CompraLista');
    Route::name('compraNueva')->get('/compra/nueva','CompraController@CompraNueva');
    Route::name('compraNuevaPost')->post('/compra/nueva','CompraController@CompraNuevaPost');
    Route::name('compraVer')->get('/compra/{id}','CompraController@CompraVer');
    Route::name('ordenPedidoLista')->get('/ordenPedido','OrdenPedidoController@OrdenPedidoLista');
    Route::name('ordenPedidoNueva')->get('/ordenPedido/nueva','OrdenPedidoController@OrdenPedidoNueva');
    Route::name('ordenPedidoNuevaPost')->post('/ordenPedido/nueva','OrdenPedidoController@OrdenPedidoNuevaPost');
    Route::name('ordenPedidoVer')->get('/ordenPedido/{id}','OrdenPedidoController@OrdenPedidoVer');
    Route::name('ajusteLista')->get('/ajuste','AjusteController@AjusteLista');
    Route::name('ajusteNuevo')->get('/ajuste/nuevo','AjusteController@AjusteNuevo');
    Route::name('ajusteNuevoPost')->post('/ajuste/nuevo','AjusteController@AjusteNuevoPost');
    Route::name('ajusteVer')->get('/ajuste/{id}','AjusteController@AjusteVer');

    /**
     * Rutas de inventario
     */
    Route::name('inventarioLista')->get('/inventario','InventarioController@InventarioLista');
    Route::name('kardexProducto')->get('/inventario/{id}','InventarioController@InventarioKardex');

    /**
     * Rutas de formulas
     */
    Route::name('formulaLista')->get('/formula','FormulaController@FormulaLista');
    Route::name('formulaNuevo')->get('/formula/nuevo','FormulaController@FormulaNuevo');
    Route::name('formulaNuevoPost')->post('/formula/nuevo','FormulaController@FormulaNuevoPost');
    Route::name('formulaVer')->get('/formula/{id}','FormulaController@FormulaVer');
    Route::name('formulaEditar')->get('/formula/{id}/editar','FormulaController@FormulaEditar');
    Route::name('formulaEditarPut')->put('/formula/{id}','FormulaController@FormulaEditarPut');

});
