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
    Route::get('/producto','ProductoController@ProductoLista')->name('productoLista');
    Route::get('/producto/nuevo','ProductoController@ProductoNuevo')->name('productoNuevo');
    Route::post('/producto/nuevo','ProductoController@ProductoNuevoPost')->name('productoNuevoPost');
    Route::get('/producto/{id}','ProductoController@ProductoVer')->name('productoVer');
    Route::get('/producto/{id}/editar','ProductoController@ProductoEditar')->name('productoEditar');
    Route::put('/producto/{id}','ProductoController@ProductoEditarPut')->name('productoEditarPut');
    Route::delete('/producto/{id}','ProductoController@ProductoEliminar')->name('productoEliminar');

    /**
     * Rutas de categoria
     */
    Route::get('/categoria','CategoriaController@CategoriaLista')->name('categoriaLista');
    Route::get('/categoria/nuevo','CategoriaController@CategoriaNuevo')->name('categoriaNuevo');
    Route::post('/categoria/nuevo','CategoriaController@CategoriaNuevoPost')->name('categoriaNuevoPost');
    Route::get('/categoria/{id}','CategoriaController@CategoriaVer')->name('categoriaVer');
    Route::get('/categoria/{id}/editar','CategoriaController@CategoriaEditar')->name('categoriaEditar');
    Route::put('/categoria/{id}','CategoriaController@CategoriaEditarPut')->name('categoriaEditarPut');
    Route::delete('/categoria/{id}','CategoriaController@CategoriaEliminar')->name('categoriaEliminar');

    /**
     * Rutas de proveedores
     */
    Route::get('/proveedor','ProveedorController@ProveedorLista')->name('proveedorLista');
    Route::get('/proveedor/nuevo','ProveedorController@ProveedorNuevo')->name('proveedorNuevo');
    Route::post('/proveedor/nuevo','ProveedorController@ProveedorNuevoPost')->name('proveedorNuevoPost');
    Route::get('/proveedor/{id}','ProveedorController@ProveedorVer')->name('proveedorVer');
    Route::get('/proveedor/{id}/editar','ProveedorController@ProveedorEditar')->name('proveedorEditar');
    Route::put('/proveedor/{id}','ProveedorController@ProveedorEditarPut')->name('proveedorEditarPut');
    Route::delete('/proveedor/{id}','ProveedorController@ProveedorEliminar')->name('proveedorEliminar');

    /**
     * Rutas de cliente
     */
    Route::get('/cliente','ClienteController@ClienteLista')->name('clienteLista');
    Route::get('/cliente/nuevo','ClienteController@ClienteNuevo')->name('clienteNuevo');
    Route::post('/cliente/nuevo','ClienteController@ClienteNuevoPost')->name('clienteNuevoPost');
    Route::get('/cliente/{id}','ClienteController@ClienteVer')->name('clienteVer');
    Route::get('/cliente/{id}/editar','ClienteController@ClienteEditar')->name('clienteEditar');
    Route::put('/cliente/{id}','ClienteController@ClienteEditarPut')->name('clienteEditarPut');
    Route::delete('/cliente/{id}','ClienteController@ClienteEliminar')->name('clienteEliminar');

    /**
     * Movimientos
     */
    Route::get('/compra','CompraController@CompraLista')->name('compraLista');
    Route::get('/compra/nueva','CompraController@CompraNueva')->name('compraNueva');
    Route::post('/compra/nueva','CompraController@CompraNuevaPost')->name('compraNuevaPost');
    Route::get('/compra/{id}','CompraController@CompraVer')->name('compraVer');
    Route::get('/ordenPedido','OrdenPedidoController@OrdenPedidoLista')->name('ordenPedidoLista');
    Route::get('/ordenPedido/bodega','OrdenPedidoController@OrdenPedidoListaBodega')->name('ordenPedidoListaBodega');
    Route::get('/ordenPedido/nueva','OrdenPedidoController@OrdenPedidoNueva')->name('ordenPedidoNueva');
    Route::post('/ordenPedido/nueva','OrdenPedidoController@OrdenPedidoNuevaPost')->name('ordenPedidoNuevaPost');
    Route::put('/ordenPedido/{id}','OrdenPedidoController@OrdenPedidoBodegaPost')->name('ordenPedidoBodegaPost');
    Route::get('/ordenPedido/{id}','OrdenPedidoController@OrdenPedidoVer')->name('ordenPedidoVer');
    Route::get('/ordenPedido/{id}/bodega','OrdenPedidoController@OrdenPedidoVerBodega')->name('ordenPedidoVerBodega');
    Route::get('/ajuste','AjusteController@AjusteLista')->name('ajusteLista');
    Route::get('/ajuste/nuevo','AjusteController@AjusteNuevo')->name('ajusteNuevo');
    Route::post('/ajuste/nuevo','AjusteController@AjusteNuevoPost')->name('ajusteNuevoPost');
    Route::get('/ajuste/{id}','AjusteController@AjusteVer')->name('ajusteVer');

    /**
     * Rutas de inventario
     */
    Route::get('/inventario','InventarioController@InventarioLista')->name('inventarioLista');
    Route::get('/inventario/{id}','InventarioController@InventarioKardex')->name('kardexProducto');

    /**
     * Rutas de formulas
     */
    Route::get('/formula','FormulaController@FormulaLista')->name('formulaLista');
    Route::get('/formula/nuevo','FormulaController@FormulaNuevo')->name('formulaNuevo');
    Route::post('/formula/nuevo','FormulaController@FormulaNuevoPost')->name('formulaNuevoPost');
    Route::get('/formula/{id}','FormulaController@FormulaVer')->name('formulaVer');
    Route::get('/formula/{id}/editar','FormulaController@FormulaEditar')->name('formulaEditar');
    Route::put('/formula/{id}','FormulaController@FormulaEditarPut')->name('formulaEditarPut');

    /**
     * Rutas para la produccion
     */
    Route::get('/produccion','ProduccionController@ProduccionLista')->name('produccionLista');
    Route::get('/produccion/nuevo','ProduccionController@ProduccionNuevo')->name('produccionNuevo');
    Route::post('/produccion/nuevo','ProduccionController@ProduccionNuevoPost')->name('produccionNuevoPost');
    Route::get('/produccion/{id}','ProduccionController@ProduccionVer')->name('produccionVer');
    Route::get('/produccion/{id}/editar','ProduccionController@ProduccionEditar')->name('produccionEditar');
    Route::put('/produccion/{id}','ProduccionController@ProduccionEditarPut')->name('produccionEditarPut');

    /**
     * Rutas para generación de PDFs
     */
    Route::get('/pdf/ordenPedido/{id}','OrdenPedidoController@OrdenPedidoPDF')->name('ordenPedidoPDF');

    /**
     * Rutas de configuracion general del sistema LGL
     */
    Route::get('/configuracion/producto/cvs','ConfiguracionController@ImportarDatos')->name('importarDatos');
    Route::post('/configuracion/producto/cvs','ConfiguracionController@ImportarDatosPost')->name('importarDatosPost');
    Route::get('/conversionUnidades','ConfiguracionController@ConversionUnidadesLista')->name('conversionUnidadesLista');
    Route::get('/conversionUnidades/nuevo','ConfiguracionController@ConversionUnidadesNuevo')->name('conversionUnidadesNuevo');
    Route::post('/conversionUnidades/nuevo','ConfiguracionController@ConversionUnidadesNuevoPost')->name('conversionUnidadesNuevoPost');
    Route::get('/conversionUnidades/{id}','ConfiguracionController@ConversionUnidadesVer')->name('conversionUnidadesVer');

    /**
     * Rutas de pruebas
     */
    Route::get('dev/prueba','DevController@select2');
    Route::get('dev/unidadesMedidaJSON','DevController@UnidadesMedidaJSON')->name('unidadesMedidaJSON');
    Route::get('dev/unidadesConversionJSON','DevController@UnidadesConversionJSON')->name('unidadesMedidaJSON');
    Route::get('dev/factorJSON','DevController@FactorJSON')->name('factorJSON');


});
