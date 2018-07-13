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
        Route::delete('/orden-pedido-despachada/{id}','OrdenPedidoController@OrdenPedidoEliminarDespachada')->name('ordenPedidoEliminarDespachada');
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
        Route::get('/venta/sin-orden/anular','VentaController@VentaAnuladaSinOrdenNueva')->name('ventaSinOrdenAnuladaNueva');
        Route::post('/venta/sin-orden/anular','VentaController@VentaAnuladaSinOrdenNuevaPost')->name('ventaSinOrdenAnuladaNuevaPost');
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
        Route::get('/formula-historico','FormulaController@FormulaDesactivadasLista')->name('formulaDesactivadasLista');
        Route::get('/formula/nuevo','FormulaController@FormulaNuevo')->name('formulaNuevo');
        Route::post('/formula/nuevo','FormulaController@FormulaNuevoPost')->name('formulaNuevoPost');
        Route::get('/formula/{id}/editar','FormulaController@FormulaEditar')->name('formulaEditar');
        Route::put('/formula/{id}','FormulaController@FormulaEditarPut')->name('formulaEditarPut');
        Route::post('/formula/{id}','FormulaController@FormulaActivarPost')->name('formulaActivarPost');
        Route::delete('/formula/{id}','FormulaController@FormulaEliminar')->name('formulaEliminar');
        Route::delete('/componente/{id}','FormulaController@ComponenteEliminar')->name('componenteEliminar');
    });
    Route::get('/formula','FormulaController@FormulaLista')->name('formulaLista')->middleware('bodeguero');
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
        Route::delete('/abono/{id}','AbonoController@revertirAbono')->name('abonoRevertir');
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
    Route::post('/configuracion/producto/cvs','ConfiguracionController@ActualizarInventario')->name('importarDatosPost');
    Route::get('/configuracion/ordenes/cvs','ConfiguracionController@ImportarOrdenes')->name('importarOrdenes');
    Route::post('/configuracion/ordenes/cvs','ConfiguracionController@ImportarOrdenesPost')->name('importarOrdenesPost');
    Route::get('/configuracion/reajuste','ConfiguracionController@ReajustarDB')->name('reajusteDB');
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
    Route::get('informe/producto/movimientos','InformesController@ProductoMovimiento')->name('productoMovimiento');
    Route::get('informe/cxc/antiguedad','InformesController@CXCAntiguedad')->name('cxcAntiguedad');
    Route::get('informe/cxc/antiguedad/excel','InformesController@CXCAntiguedadExcel')->name('cxcAntiguedadExcel');

    Route::get('informe/ventas','InformesController@Ventas')->name('informeVentas');
    Route::get('informe/ventas-excel','InformesController@VentasExcel')->name('informeVentasExcel');
    Route::get('informe/ventas/cliente','InformesController@VentasPorCliente')->name('informeVentasPorCliente');
    Route::get('informe/ventas/cliente-excel','InformesController@VentasPorClienteExcel')->name('informeVentasPorClienteExcel');
    Route::get('informe/ventas/vendedor','InformesController@VentasPorVendedor')->name('informeVentasPorVendedor');
    Route::get('informe/producciones','InformesController@Producciones')->name('informeProducciones');
    Route::get('informe/movimientos/ajustes','InformesController@MovimientosAjuste')->name('movimientosAjuste');
    Route::get('informe/producto/existencias','InformesController@ProductosExistenciasInforme')->name('productoExistenciaInforme');
    Route::get('informe/producto/existencias/excel','InformesController@ProductosExistenciasInformeExcel')->name('productoExistenciaInformeExcel');
    Route::get('informe/compras','InformesController@Compras')->name('informeCompras');
    Route::get('informe/compras/excel','InformesController@ComprasExcel')->name('informeComprasExcel');
    Route::get('informe/compras/proveedor','InformesController@ComprasProveedor')->name('informeComprasProveedor');
    Route::get('informe/compras/proveedor/excel','InformesController@ComprasProveedorExcel')->name('informeComprasProveedorExcel');

    Route::get('informe/compras/libro','InformesController@LibroCompras')->name('informeLibroCompras');
    Route::get('informe/compras/libro/excel','InformesController@LibroComprasExcel')->name('informeLibroComprasExcel');
    Route::get('informe/ventas/libro/fac','InformesController@LibroVentasFAC')->name('informeLibroVentasFAC');
    Route::get('informe/ventas/libro/fac/excel','InformesController@LibroVentasFACExcel')->name('informeLibroVentasFACExcel');
    Route::get('informe/ventas/libro/ccf','InformesController@LibroVentasCCF')->name('informeLibroVentasCCF');
    Route::get('informe/ventas/libro/ccf/excel','InformesController@LibroVentasCCFExcel')->name('informeLibroVentasCCFExcel');

    Route::get('informe/ingresos-diario','InformesController@IngresoDiario')->name('ingresoVentas');
    Route::get('informe/producto/precios','InformesController@ProductosPreciosInforme')->name('productoPreciosInforme');
    Route::get('informe/producto/precios/excel','InformesController@ProductosPreciosInformeExcel')->name('productoPreciosInformeExcel');

    Route::get('informe/costo-ventas','CierreMensualController@informeCostoVentas')->name('informeCostoVentas');
    Route::get('informe/costo-ventas/excel','CierreMensualController@informeCostoVentasExcel')->name('informeCostoVentasExcel');
    Route::get('informe/costo-ventas/SAC','CierreMensualController@informeCostoVentasSAC')->name('informeCostoVentasSAC');

    Route::get('informe/costo-inventario','InformesController@CostoInvenatario')->name('informeCostoInventario');
    Route::get('informe/costo-inventario/excel','InformesController@CostoInvenatarioExcel')->name('informeCostoInventarioExcel');



    /**
     * Rutas de usuarios
     */
    Route::group(['middleware' => ['administrador']], function () {
        Route::get('/usuario','UsuarioController@UsuarioLista')->name('usuarioLista');
        Route::get('/usuario/nuevo','UsuarioController@UsuarioNuevo')->name('usuarioNuevo');
        Route::post('/usuario/nuevo','UsuarioController@UsuarioNuevoPost')->name('usuarioNuevoPost');
        Route::get('/usuario/{id}','UsuarioController@UsuarioVer')->name('usuarioVer');
        Route::get('/usuario/{id}/editar','UsuarioController@UsuarioEditar')->name('usuarioEditar');
        Route::put('/usuario/{id}','UsuarioController@UsuarioEditarPut')->name('usuarioEditarPut');
        Route::delete('/usuario/{id}','UsuarioController@ProveedorEliminar')->name('usuarioEliminar');
    });

    /**
     * Rutas Orden de pedido
     */
    Route::group(['middleware' => ['vendedor']], function () {
        Route::get('/orden-muestra','OrdenMuestraController@OrdenMuestraLista')->name('ordenMuestraLista');
        Route::get('/orden-muestra/nueva','OrdenMuestraController@OrdenMuestraNueva')->name('ordenMuestraNueva');
        Route::post('/orden-muestra/nueva','OrdenMuestraController@OrdenMuestrasNuevaPost')->name('ordenMuestraNuevaPost');
//        Route::get('/orden-pedido/{id}','OrdenPedidoController@OrdenPedidoVer')->name('ordenPedidoVer');
//        Route::delete('/orden-pedido/{id}','OrdenPedidoController@OrdenPedidoEliminar')->name('ordenPedidoEliminar');
//        Route::delete('/orden-pedido-despachada/{id}','OrdenPedidoController@OrdenPedidoEliminarDespachada')->name('ordenPedidoEliminarDespachada');
    
    /**
     * Rutas para refactorizar la base de datos
     */
        Route::get('rf/entradas1','DevController@Entradas1');
        Route::get('rf/ventas','DevController@VentasREF');

    });

    /**
     * Rutas para exportar a SAC
     */
    Route::get('exportar/sac/','DevController@Entradas1');
    Route::get('exportar/sac/configuracion','ExportarController@configuracionSAC')->name('exportar.configuracion');
    Route::post('exportar/sac/configuracion','ExportarController@store')->name('exportar.configuracion.store');
    Route::post('exportar/sac/configuracion2','ExportarController@store2')->name('exportar.configuracion.store2');
    Route::get('exportar/sac/configuracion/{id}','ExportarController@edit')->name('exportar.configuracion.edit');
    Route::post('exportar/sac/configuracion/{id}','ExportarController@update')->name('exportar.configuracion.update');

    /**
     * Cierre mensual
     */
    Route::get('cierre-mensual','CierreMensualController@index')->name('cierre.index');


});
