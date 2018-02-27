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
    Route::get('/producto/desactivado','ProductoController@ProductoDesactivadoLista')->name('productoDesactivadoLista');
    Route::get('/producto/nuevo','ProductoController@ProductoNuevo')->name('productoNuevo');
    Route::post('/producto/nuevo','ProductoController@ProductoNuevoPost')->name('productoNuevoPost');
    Route::get('/producto/{id}','ProductoController@ProductoVer')->name('productoVer');
    Route::get('/producto/{id}/precio','ProductoController@ProductoPrecio')->name('productoPrecio');
    Route::post('/producto/{id}/precio','ProductoController@ProductoPrecioPost')->name('productoPrecioPost');
    Route::get('/producto/{id}/editar','ProductoController@ProductoEditar')->name('productoEditar');
    Route::put('/producto/{id}','ProductoController@ProductoEditarPut')->name('productoEditarPut');
    Route::delete('/producto/{id}','ProductoController@ProductoEliminar')->name('productoEliminar');
    Route::post('/producto/precio/{id}','ProductoController@ProductoPrecioNuevoPost')->name('productoPrecioNuevoPost');
    Route::delete('/producto/precio/{id}','ProductoController@ProductoPrecioEliminar')->name('productoPrecioEliminar');

    /**
     * Rutas de categoria
     */
    Route::group(['middleware' => ['administrador']], function () {
        Route::get('/categoria','CategoriaController@CategoriaLista')->name('categoriaLista');
        Route::get('/categoria/nuevo','CategoriaController@CategoriaNuevo')->name('categoriaNuevo');
        Route::post('/categoria/nuevo','CategoriaController@CategoriaNuevoPost')->name('categoriaNuevoPost');
        Route::get('/categoria/{id}','CategoriaController@CategoriaVer')->name('categoriaVer');
        Route::get('/categoria/{id}/editar','CategoriaController@CategoriaEditar')->name('categoriaEditar');
        Route::put('/categoria/{id}','CategoriaController@CategoriaEditarPut')->name('categoriaEditarPut');
        Route::delete('/categoria/{id}','CategoriaController@CategoriaEliminar')->name('categoriaEliminar');
    });

    /**
     * Rutas de proveedores
     */
    Route::group(['middleware' => ['administrador']], function () {
        Route::get('/proveedor','ProveedorController@ProveedorLista')->name('proveedorLista');
        Route::get('/proveedor/nuevo','ProveedorController@ProveedorNuevo')->name('proveedorNuevo');
        Route::post('/proveedor/nuevo','ProveedorController@ProveedorNuevoPost')->name('proveedorNuevoPost');
        Route::get('/proveedor/{id}','ProveedorController@ProveedorVer')->name('proveedorVer');
        Route::get('/proveedor/{id}/editar','ProveedorController@ProveedorEditar')->name('proveedorEditar');
        Route::put('/proveedor/{id}','ProveedorController@ProveedorEditarPut')->name('proveedorEditarPut');
        Route::delete('/proveedor/{id}','ProveedorController@ProveedorEliminar')->name('proveedorEliminar');
    });


    /**
     * Rutas de cliente
     */
    Route::group(['middleware' => ['vendedor']], function () {
        Route::get('/cliente','ClienteController@ClienteLista')->name('clienteLista');
        Route::get('/cliente/nuevo','ClienteController@ClienteNuevo')->name('clienteNuevo');
        Route::post('/cliente/nuevo','ClienteController@ClienteNuevoPost')->name('clienteNuevoPost');
        Route::get('/cliente/{id}','ClienteController@ClienteVer')->name('clienteVer');
        Route::get('/cliente/{id}/editar','ClienteController@ClienteEditar')->name('clienteEditar');
        Route::put('/cliente/{id}','ClienteController@ClienteEditarPut')->name('clienteEditarPut');
        Route::delete('/cliente/{id}','ClienteController@ClienteEliminar')->name('clienteEliminar');
    });

    /**
     * Cuentas por cobrar
     */
    Route::group(['middleware' => ['vendedor']], function () {
        Route::get('/cxc/ventas','ClienteController@ClienteVentaLista')->name('clienteVentaLista');
        Route::get('/cxc/ventas/{id}','ClienteController@VentasPorClienteVer')->name('ventasPorClienteVer');
        Route::get('/cxc/saldo','ClienteController@CuentasPorCobrar')->name('cuentasPorCobrar');
        Route::get('/cxc/saldo/{id}','ClienteController@CuentasPorCobrarVer')->name('cuentasPorCobrarVer');
    });

    /**
     * Rutas Compras
     */
    Route::group(['middleware' => ['administrador']], function () {
        Route::get('/compra','CompraController@CompraLista')->name('compraLista');
        Route::get('/compra/nueva','CompraController@CompraNueva')->name('compraNueva');
        Route::post('/compra/nueva','CompraController@CompraNuevaPost')->name('compraNuevaPost');
        Route::get('/compra/{id}','CompraController@CompraVer')->name('compraVer');
        Route::post('/compra/{id}','CompraController@CompraProcesar')->name('compraProcesar');
        Route::delete('/compra/{id}','CompraController@CompraEliminar')->name('compraEliminar');
    });


    /**
     * Rutas Orden de pedido
     */
    Route::group(['middleware' => ['vendedor']], function () {
        Route::get('/orden-pedido','OrdenPedidoController@OrdenPedidoLista')->name('ordenPedidoLista');
        Route::get('/orden-pedido/nueva','OrdenPedidoController@OrdenPedidoNueva')->name('ordenPedidoNueva');
        Route::post('/orden-pedido/nueva','OrdenPedidoController@OrdenPedidoNuevaPost')->name('ordenPedidoNuevaPost');
        Route::get('/orden-pedido/{id}','OrdenPedidoController@OrdenPedidoVer')->name('ordenPedidoVer');
        Route::delete('/orden-pedido/{id}','OrdenPedidoController@OrdenPedidoEliminar')->name('ordenPedidoEliminar');
    });
    Route::group(['middleware' => ['bodeguero']], function () {
        Route::get('/orden-pedido-bodega','OrdenPedidoController@OrdenPedidoListaBodega')->name('ordenPedidoListaBodega');
        Route::get('/orden-pedido-bodega/procesada','OrdenPedidoController@OrdenPedidoListaProcesadoBodega')->name('ordenPedidoListaProcesadoBodega');
        Route::get('/orden-pedido-bodega/{id}/bodega','OrdenPedidoController@OrdenPedidoVerBodega')->name('ordenPedidoVerBodega');
        Route::put('/orden-pedido-bodega/{id}','OrdenPedidoController@OrdenPedidoBodegaPost')->name('ordenPedidoBodegaPost');
    });


    /**
     * Rutas Ventas
     */
    Route::group(['middleware' => ['vendedor']], function () {
        Route::get('/venta/ordenes','VentaController@VentaOrdenesLista')->name('ventaOrdenesLista');
        Route::get('/venta/nueva/{id}','VentaController@VentaNueva')->name('ventaNueva');
        Route::post('/venta/nueva/{id}','VentaController@VentaNuevaPost')->name('ventaNuevaPost');
        Route::get('/venta/factura/{id}','VentaController@VentaVerFactura')->name('ventaVerFactura');
        Route::get('/venta/ccf/{id}','VentaController@VentaVerCCF')->name('ventaVerCFF');
        Route::get('/venta/tipo/{tipo}','VentaController@VentaLista')->name('ventaLista');
    });
    Route::group(['middleware' => ['administrador']], function () {
        Route::delete('/venta/anular/{id}','VentaController@VentaAnular')->name('ventaAnular');
        Route::get('/venta/sin-orden/nueva','VentaController@VentaSinOrdenNueva')->name('ventaSinOrdenNueva');
        Route::post('/venta/sin-orden/nueva','VentaController@VentaSinOrdenPost')->name('ventaSinOrdenPost');
        Route::get('/venta/sin-orden/anular','VentaController@VentaSinOrdenAnuladaNueva')->name('ventaSinOrdenAnuladaNueva');
    });



    /**
     * Rutas Ajustes
     */
    Route::get('/ajuste','AjusteController@AjusteLista')->name('ajusteLista');
    Route::get('/ajuste/existencia/nuevo','AjusteController@AjusteNuevo')->name('ajusteNuevo');
    Route::get('/ajuste/costo/nuevo','AjusteController@AjusteCostoNuevo')->name('ajusteCostoNuevo');
    Route::post('/ajuste/existencia/nuevo','AjusteController@AjusteNuevoPost')->name('ajusteNuevoPost');
    Route::post('/ajuste/costo/nuevo','AjusteController@AjusteCostoNuevoPost')->name('ajusteCostoNuevoPost');
    Route::get('/ajuste/{id}','AjusteController@AjusteVer')->name('ajusteVer');



    /**
     * Rutas de inventario
     */
    Route::get('/inventario','InventarioController@InventarioLista')->name('inventarioLista');
    Route::get('/inventario/{id}','InventarioController@InventarioKardex')->name('kardexProducto');
    Route::post('/inventario/{id}','InventarioController@InventarioKardexPost')->name('kardexProductoPost');

    /**
     * Rutas de formulas
     */
    Route::group(['middleware' => ['administrador']], function () {
        Route::get('/formula','FormulaController@FormulaLista')->name('formulaLista');
        Route::get('/formula/historico','FormulaController@FormulaDesactivadasLista')->name('formulaDesactivadasLista');
        Route::get('/formula/nuevo','FormulaController@FormulaNuevo')->name('formulaNuevo');
        Route::post('/formula/nuevo','FormulaController@FormulaNuevoPost')->name('formulaNuevoPost');
        Route::get('/formula/{id}/editar','FormulaController@FormulaEditar')->name('formulaEditar');
        Route::put('/formula/{id}','FormulaController@FormulaEditarPut')->name('formulaEditarPut');
        Route::post('/formula/{id}','FormulaController@FormulaActivarPost')->name('formulaActivarPost');
        Route::delete('/componente/{id}','FormulaController@ComponenteEliminar')->name('componenteEliminar');
    });
    Route::get('/formula/{id}','FormulaController@FormulaVer')->name('formulaVer')->middleware('bodeguero');

    /**
     * Rutas para la produccion
     */
    Route::get('/produccion','ProduccionController@ProduccionLista')->name('produccionLista');
    Route::get('/produccion/revertida','ProduccionController@ProduccionRevLista')->name('produccionRevertidaLista');
    Route::get('/produccion/nuevo','ProduccionController@ProduccionNuevo')->name('produccionNuevo');
    Route::post('/produccion/nuevo','ProduccionController@ProduccionNuevoPost')->name('produccionNuevoPost');
    Route::get('/produccion/{id}','ProduccionController@ProduccionVer')->name('produccionVer');
    Route::delete('/produccion/{id}','ProduccionController@ProduccionRevertir')->name('produccionRevertir');
    Route::get('/produccion/{id}/editar','ProduccionController@ProduccionEditar')->name('produccionEditar');
    Route::put('/produccion/{id}','ProduccionController@ProduccionEditarPut')->name('produccionEditarPut');

    Route::post('/produccion/','ProduccionController@ProduccionNuevaPost')->name('produccionNuevaPost');
    Route::get('/produccion/previa/{id}','ProduccionController@ProduccionPrevia')->name('produccionPrevia');
    Route::delete('/produccion/previa/{id}','ProduccionController@ProduccionPreviaEliminar')->name('produccionPreviaEliminar');
    Route::post('/produccion/{id}','ProduccionController@ProduccionConfirmarPost')->name('produccionConfirmarPost');

    /**
     * Rutas para los abonos
     */
    Route::group(['middleware' => ['administrador']], function () {
        Route::get('/abono','AbonoController@AbonoLista')->name('abonoLista');
        Route::get('/abono/nuevo','AbonoController@AbonoNuevoSinVenta')->name('abonoNuevoSinVenta');
        Route::get('/abono/nuevo/{id}','AbonoController@AbonoNuevo')->name('abonoNuevo');
        Route::post('/abono/nuevo','AbonoController@AbonoNuevoSinVentaPost')->name('abonoNuevoSinVentaPost');
        Route::post('/abono/nuevo/{id}','AbonoController@AbonoNuevoPost')->name('abonoNuevoPost');
        Route::get('/abono/{id}','AbonoController@AbonoVer')->name('abonoVer');
    });


    /**
     * Rutas para generación de PDFs
     */
    Route::get('/pdf/ordenPedido/{id}','OrdenPedidoController@OrdenPedidoPDF')->name('ordenPedidoPDF');
    Route::get('/pdf/factura/{id}','VentaController@VentaFacturaPDF')->name('facturaPDF');
    Route::get('/pdf/factura-especial/{id}','VentaController@VentaFacturaEspecialPDF')->name('facturaEspecialPDF');
    Route::get('/pdf/ccf/{id}','VentaController@VentaCCFPDF')->name('CCFPDF');


    /**
     * Rutas de configuracion general del sistema LGL
     */
    Route::get('/configuracion/producto/cvs','ConfiguracionController@ImportarDatos')->name('importarDatos');
    Route::post('/configuracion/producto/cvs','ConfiguracionController@ImportarDatosPost')->name('importarDatosPost');
    Route::get('/configuracion/ordenes/cvs','ConfiguracionController@ImportarOrdenes')->name('importarOrdenes');
    Route::post('/configuracion/ordenes/cvs','ConfiguracionController@ImportarOrdenesPost')->name('importarOrdenesPost');
    Route::get('/conversionUnidades','ConfiguracionController@ConversionUnidadesLista')->name('conversionUnidadesLista');
    Route::get('/conversionUnidades/nuevo','ConfiguracionController@ConversionUnidadesNuevo')->name('conversionUnidadesNuevo');
    Route::post('/conversionUnidades/nuevo','ConfiguracionController@ConversionUnidadesNuevoPost')->name('conversionUnidadesNuevoPost');
    Route::get('/conversionUnidades/{id}','ConfiguracionController@ConversionUnidadesVer')->name('conversionUnidadesVer');

    /**
     * Rutas de pruebas
     */
    Route::get('dev/venta-sin-orden','DevController@VentaSinOrden');
    Route::get('dev/venta-anulada-sin-orden','DevController@VentaAnuladaSinOrden');
    Route::get('dev/c','DevController@Corregir');
    Route::get('dev/c2','DevController@Corregir2');


    /**
     * Rutas API del SIFLGL
     */
    Route::get('api/precios/{id}','APIController@ProductosPresentacionesJSON')->name('preciosProducto');
    Route::get('api/cliente/{id}','APIController@ClientesVentasPendientesJSON')->name('clienteVentasPendientes');
    Route::get('api/tipoAjustes/{id}','APIController@TipoAjustesJSON')->name('clienteVentasPendientes');
    Route::get('api/formula/version/{id}','APIController@VersionFormulaJSON')->name('versionFormula');


    /**
     * Rutas de inicio
     */
    Route::get('/administrador','InicioController@AdministradorInicio')->name('administradorInicio');
    Route::get('/vendedor','InicioController@AdministradorInicio')->name('vendedorInicio');
    Route::get('/bodega','InicioController@AdministradorInicio')->name('bodegaInicio');

    /**
     * Rutas de informes
     */
    Route::get('informe/lista','InformesController@InformeLista')->name('informeLista');
    Route::get('informe/facturacion/{rango}','InformesController@facturacionDia')->name('facturacion');
    Route::get('informe/facturacionxls','InformesController@FacturacionInformeExcelPost')->name('facturacionExcelPost');
    Route::post('informe/facturacion','InformesController@facturacionDiaInformeFechaPost')->name('facturacionInformeFechaPost');
    Route::get('informe/abonos/{rango}','InformesController@Abonos')->name('abonosInforme');
    Route::get('informe/abonos','InformesController@AbonosFecha')->name('abonosInformeFecha');
    Route::post('informe/abonos','InformesController@AbonosFechaPost')->name('abonosFechaPost');
    Route::get('informe/abonosxls','InformesController@AbonosFechaExcelPost')->name('abonosFechaExcelPost');
    Route::get('informe/producto/existencias','InformesController@ProductosExistenciasInforme')->name('productoExistenciaInforme');
    Route::get('informe/cxc/antiguedad','InformesController@CXCAntiguedad')->name('cxcAntiguedad');
    Route::get('informe/producto/precios','InformesController@ProductosPreciosInforme')->name('productoPreciosInforme');
    Route::get('informe/ingreso/ventas','InformesController@IngresoVentas')->name('ingresoVentas');
    Route::post('informe/ingreso/ventas','InformesController@IngresoVentasPost')->name('ingresoVentasPost');
});
