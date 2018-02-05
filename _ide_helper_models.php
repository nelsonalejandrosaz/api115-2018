<?php
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * App\DetalleProduccion
 *
 * @property-read \App\Produccion $produccion
 */
	class DetalleProduccion extends \Eloquent {}
}

namespace App{
/**
 * App\Proveedor
 *
 * @property int $id
 * @property string $nombre
 * @property string|null $telefono_1
 * @property string|null $telefono_2
 * @property string|null $direccion
 * @property string|null $nit
 * @property string|null $nrc
 * @property string|null $nombre_contacto
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Compra[] $compras
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proveedor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proveedor whereDireccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proveedor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proveedor whereNit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proveedor whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proveedor whereNombreContacto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proveedor whereNrc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proveedor whereTelefono1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proveedor whereTelefono2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proveedor whereUpdatedAt($value)
 */
	class Proveedor extends \Eloquent {}
}

namespace App{
/**
 * App\OrdenPedido
 *
 * @property int $id
 * @property int $cliente_id
 * @property int $numero
 * @property string|null $detalle
 * @property \Carbon\Carbon $fecha
 * @property \Carbon\Carbon|null $fecha_procesado
 * @property \Carbon\Carbon|null $fecha_entrega
 * @property int|null $condicion_pago_id
 * @property int $vendedor_id
 * @property int|null $bodega_id
 * @property float|null $ventas_exentas
 * @property float|null $ventas_gravadas
 * @property float|null $venta_total
 * @property string $ruta_archivo
 * @property int $estado_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\User|null $bodeguero
 * @property-read \App\Cliente $cliente
 * @property-read \App\CondicionPago|null $condicion_pago
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Salida[] $salidas
 * @property-read \App\User $vendedor
 * @property-read \App\Venta $venta
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereBodegaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereClienteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereCondicionPagoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereDetalle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereEstadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereFechaEntrega($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereFechaProcesado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereNumero($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereRutaArchivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereVendedorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereVentaTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereVentasExentas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereVentasGravadas($value)
 */
	class OrdenPedido extends \Eloquent {}
}

namespace App{
/**
 * App\Categoria
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property string|null $descripcion
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Categoria whereCodigo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Categoria whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Categoria whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Categoria whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Categoria whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Categoria whereUpdatedAt($value)
 */
	class Categoria extends \Eloquent {}
}

namespace App{
/**
 * App\Servicio
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property float $costo
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DetalleServicio[] $detalle_servicios
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servicio whereCodigo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servicio whereCosto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servicio whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servicio whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servicio whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servicio whereUpdatedAt($value)
 */
	class Servicio extends \Eloquent {}
}

namespace App{
/**
 * App\Cliente
 *
 * @property int $id
 * @property int $municipio_id
 * @property string $nombre
 * @property string|null $nombre_alternativo
 * @property string|null $telefono_1
 * @property string|null $telefono_2
 * @property string|null $direccion
 * @property int|null $vendedor_id
 * @property string|null $nit
 * @property string|null $nrc
 * @property string|null $giro
 * @property string|null $nombre_contacto
 * @property float $saldo
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Abono[] $abonos
 * @property-read \App\Municipio $municipio
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\OrdenPedido[] $ordenes_pedidos
 * @property-read \App\User|null $vendedor
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereDireccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereGiro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereMunicipioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereNit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereNombreAlternativo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereNombreContacto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereNrc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereSaldo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereTelefono1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereTelefono2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereVendedorId($value)
 */
	class Cliente extends \Eloquent {}
}

namespace App{
/**
 * App\Departamento
 *
 * @property int $id
 * @property string $nombre
 * @property string $isocode
 * @property int $zonesv_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Municipio[] $municipios
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Departamento whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Departamento whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Departamento whereIsocode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Departamento whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Departamento whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Departamento whereZonesvId($value)
 */
	class Departamento extends \Eloquent {}
}

namespace App{
/**
 * App\Precio
 *
 * @property int $id
 * @property int $producto_id
 * @property int $unidad_medida_id
 * @property string $presentacion
 * @property string|null $nombre_factura
 * @property float $precio
 * @property float $factor
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Producto $producto
 * @property-read \App\UnidadMedida $unidad_medida
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Precio whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Precio whereFactor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Precio whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Precio whereNombreFactura($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Precio wherePrecio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Precio wherePresentacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Precio whereProductoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Precio whereUnidadMedidaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Precio whereUpdatedAt($value)
 */
	class Precio extends \Eloquent {}
}

namespace App{
/**
 * App\Ajuste
 *
 * @property int $id
 * @property int $tipo_ajuste_id
 * @property string $detalle
 * @property \Carbon\Carbon $fecha
 * @property int $realizado_id
 * @property float $cantidad_anterior
 * @property float $valor_unitario_anterior
 * @property float|null $cantidad_ajuste
 * @property float|null $valor_unitario_ajuste
 * @property float $diferencia_ajuste
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Movimiento $movimiento
 * @property-read \App\User $realizado
 * @property-read \App\TipoAjuste $tipo_ajuste
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereCantidadAjuste($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereCantidadAnterior($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereDetalle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereDiferenciaAjuste($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereRealizadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereTipoAjusteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereValorUnitarioAjuste($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereValorUnitarioAnterior($value)
 */
	class Ajuste extends \Eloquent {}
}

namespace App{
/**
 * App\Municipio
 *
 * @property int $id
 * @property string $nombre
 * @property int $departamento_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Cliente[] $clientes
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Municipio whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Municipio whereDepartamentoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Municipio whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Municipio whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Municipio whereUpdatedAt($value)
 */
	class Municipio extends \Eloquent {}
}

namespace App{
/**
 * App\Componente
 *
 * @property int $id
 * @property int $formula_id
 * @property int $producto_id
 * @property float $porcentaje
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Formula $formula
 * @property-read \App\Producto $producto
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Componente whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Componente whereFormulaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Componente whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Componente wherePorcentaje($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Componente whereProductoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Componente whereUpdatedAt($value)
 */
	class Componente extends \Eloquent {}
}

namespace App{
/**
 * App\Formula
 *
 * @property int $id
 * @property int $producto_id
 * @property \Carbon\Carbon $fecha
 * @property string $ingresado_id
 * @property string|null $descripcion
 * @property float $version
 * @property int $activa
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Componente[] $componentes
 * @property-read \App\User $ingresado
 * @property-read \App\Producto $producto
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formula whereActiva($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formula whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formula whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formula whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formula whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formula whereIngresadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formula whereProductoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formula whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formula whereVersion($value)
 */
	class Formula extends \Eloquent {}
}

namespace App{
/**
 * App\TipoMovimiento
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Movimiento[] $movimientos
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoMovimiento whereCodigo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoMovimiento whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoMovimiento whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoMovimiento whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoMovimiento whereUpdatedAt($value)
 */
	class TipoMovimiento extends \Eloquent {}
}

namespace App{
/**
 * App\Abono
 *
 * @property int $id
 * @property int $venta_id
 * @property int $cliente_id
 * @property \Carbon\Carbon $fecha
 * @property string|null $detalle
 * @property float $cantidad
 * @property int $forma_pago_id
 * @property int $tipo_abono_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Cliente $cliente
 * @property-read \App\Venta $venta
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Abono whereCantidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Abono whereClienteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Abono whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Abono whereDetalle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Abono whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Abono whereFormaPagoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Abono whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Abono whereTipoAbonoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Abono whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Abono whereVentaId($value)
 */
	class Abono extends \Eloquent {}
}

namespace App{
/**
 * App\Producto
 *
 * @property int $id
 * @property int $unidad_medida_id
 * @property int $tipo_producto_id
 * @property int $categoria_id
 * @property string $nombre
 * @property string|null $nombre_alternativo
 * @property string|null $codigo
 * @property float|null $existencia_min
 * @property float|null $existencia_max
 * @property float $cantidad_existencia
 * @property float $cantidad_reserva
 * @property float $costo
 * @property string|null $unidad_factor
 * @property float $factor_volumen
 * @property int $producto_activo
 * @property int $formula_activa
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Categoria $categoria
 * @property-read \App\Formula $formula
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Movimiento[] $movimientos
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Precio[] $precios
 * @property-read \App\TipoProducto $tipo_producto
 * @property-read \App\UnidadMedida $unidad_medida
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Producto whereCantidadExistencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Producto whereCantidadReserva($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Producto whereCategoriaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Producto whereCodigo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Producto whereCosto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Producto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Producto whereExistenciaMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Producto whereExistenciaMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Producto whereFactorVolumen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Producto whereFormulaActiva($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Producto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Producto whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Producto whereNombreAlternativo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Producto whereProductoActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Producto whereTipoProductoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Producto whereUnidadFactor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Producto whereUnidadMedidaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Producto whereUpdatedAt($value)
 */
	class Producto extends \Eloquent {}
}

namespace App{
/**
 * App\Compra
 *
 * @property int $id
 * @property int $proveedor_id
 * @property int $numero
 * @property string|null $detalle
 * @property string $fecha
 * @property float|null $compra_total
 * @property float|null $compra_total_con_impuestos
 * @property int|null $ingresado_id
 * @property int|null $bodega_id
 * @property string $ruta_archivo
 * @property int $condicion_pago_id
 * @property int $estado_compra_id
 * @property float $saldo
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\User|null $bodeguero
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entrada[] $entradas
 * @property-read \App\EstadoCompra $estado_compra
 * @property-read \App\User|null $ingresado
 * @property-read \App\Proveedor $proveedor
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereBodegaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereCompraTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereCompraTotalConImpuestos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereCondicionPagoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereDetalle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereEstadoCompraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereIngresadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereNumero($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereProveedorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereRutaArchivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereSaldo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereUpdatedAt($value)
 */
	class Compra extends \Eloquent {}
}

namespace App{
/**
 * App\TipoAjuste
 *
 * @property int $id
 * @property string $codigo
 * @property string $tipo
 * @property string $nombre
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Ajuste[] $ajustes
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoAjuste whereCodigo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoAjuste whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoAjuste whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoAjuste whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoAjuste whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoAjuste whereUpdatedAt($value)
 */
	class TipoAjuste extends \Eloquent {}
}

namespace App{
/**
 * App\Produccion
 *
 * @property int $id
 * @property int $bodega_id
 * @property int $formula_id
 * @property float $cantidad
 * @property string $fecha
 * @property int|null $lote
 * @property string|null $fecha_vencimiento
 * @property string|null $detalle
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\User $bodeguero
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DetalleProduccion[] $detalle_producciones
 * @property-read \App\Formula $formula
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Salida[] $salidas
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Produccion whereBodegaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Produccion whereCantidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Produccion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Produccion whereDetalle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Produccion whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Produccion whereFechaVencimiento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Produccion whereFormulaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Produccion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Produccion whereLote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Produccion whereUpdatedAt($value)
 */
	class Produccion extends \Eloquent {}
}

namespace App{
/**
 * App\EstadoOrdenPedido
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\OrdenPedido[] $ordenes_pedido
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EstadoOrdenPedido whereCodigo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EstadoOrdenPedido whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EstadoOrdenPedido whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EstadoOrdenPedido whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EstadoOrdenPedido whereUpdatedAt($value)
 */
	class EstadoOrdenPedido extends \Eloquent {}
}

namespace App{
/**
 * App\EstadoCompra
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Compra[] $compras
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EstadoCompra whereCodigo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EstadoCompra whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EstadoCompra whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EstadoCompra whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EstadoCompra whereUpdatedAt($value)
 */
	class EstadoCompra extends \Eloquent {}
}

namespace App{
/**
 * App\TipoDocumento
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Venta[] $ventas
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoDocumento whereCodigo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoDocumento whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoDocumento whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoDocumento whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoDocumento whereUpdatedAt($value)
 */
	class TipoDocumento extends \Eloquent {}
}

namespace App{
/**
 * App\Configuracion
 *
 * @property int $id
 * @property float $iva
 * @property float $comisiones
 * @property string $color_tema
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Configuracion whereColorTema($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Configuracion whereComisiones($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Configuracion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Configuracion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Configuracion whereIva($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Configuracion whereUpdatedAt($value)
 */
	class Configuracion extends \Eloquent {}
}

namespace App{
/**
 * App\Rol
 *
 * @property int $id
 * @property string $nombre
 * @property string $descripcion
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Rol whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Rol whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Rol whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Rol whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Rol whereUpdatedAt($value)
 */
	class Rol extends \Eloquent {}
}

namespace App{
/**
 * App\Venta
 *
 * @property int $id
 * @property int $tipo_documento_id
 * @property int $orden_pedido_id
 * @property int $estado_venta_id
 * @property string|null $numero
 * @property \Carbon\Carbon $fecha
 * @property int $vendedor_id
 * @property float|null $venta_total_con_impuestos
 * @property string $ruta_archivo
 * @property float $saldo
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DetalleServicio[] $detalle_servicios
 * @property-read \App\EstadoVenta $estado_venta
 * @property-read \App\OrdenPedido $orden_pedido
 * @property-read \App\TipoDocumento $tipo_documento
 * @property-read \App\User $vendedor
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Venta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Venta whereEstadoVentaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Venta whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Venta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Venta whereNumero($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Venta whereOrdenPedidoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Venta whereRutaArchivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Venta whereSaldo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Venta whereTipoDocumentoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Venta whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Venta whereVendedorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Venta whereVentaTotalConImpuestos($value)
 */
	class Venta extends \Eloquent {}
}

namespace App{
/**
 * App\DetalleServicio
 *
 * @property int $id
 * @property int $servicio_id
 * @property int $venta_id
 * @property float $cantidad
 * @property float $precio_unitario
 * @property float $venta_exenta
 * @property float $venta_gravada
 * @property string|null $fecha_procesado
 * @property int $procesado
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Servicio $servicio
 * @property-read \App\Venta $venta
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DetalleServicio whereCantidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DetalleServicio whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DetalleServicio whereFechaProcesado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DetalleServicio whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DetalleServicio wherePrecioUnitario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DetalleServicio whereProcesado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DetalleServicio whereServicioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DetalleServicio whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DetalleServicio whereVentaExenta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DetalleServicio whereVentaGravada($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DetalleServicio whereVentaId($value)
 */
	class DetalleServicio extends \Eloquent {}
}

namespace App{
/**
 * App\CondicionPago
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\OrdenPedido[] $ordenes_pedido
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CondicionPago whereCodigo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CondicionPago whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CondicionPago whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CondicionPago whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CondicionPago whereUpdatedAt($value)
 */
	class CondicionPago extends \Eloquent {}
}

namespace App{
/**
 * App\TipoProducto
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoProducto whereCodigo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoProducto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoProducto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoProducto whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoProducto whereUpdatedAt($value)
 */
	class TipoProducto extends \Eloquent {}
}

namespace App{
/**
 * App\UnidadMedida
 *
 * @property int $id
 * @property string $nombre
 * @property string $abreviatura
 * @property string $tipo
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ConversionUnidadMedida[] $conversiones
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UnidadMedida whereAbreviatura($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UnidadMedida whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UnidadMedida whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UnidadMedida whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UnidadMedida whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UnidadMedida whereUpdatedAt($value)
 */
	class UnidadMedida extends \Eloquent {}
}

namespace App{
/**
 * App\EstadoVenta
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Venta[] $ventas
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EstadoVenta whereCodigo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EstadoVenta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EstadoVenta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EstadoVenta whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EstadoVenta whereUpdatedAt($value)
 */
	class EstadoVenta extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @property int $id
 * @property string $nombre
 * @property string $email
 * @property string $password
 * @property string $username
 * @property string|null $apellido
 * @property string|null $telefono
 * @property string $ruta_imagen
 * @property int $rol_id
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \App\Rol $rol
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereApellido($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRolId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRutaImagen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereTelefono($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUsername($value)
 */
	class User extends \Eloquent {}
}

namespace App{
/**
 * App\ConversionUnidadMedida
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property int $unidad_medida_origen_id
 * @property int $unidad_medida_destino_id
 * @property float $factor
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\UnidadMedida $unidad_destino
 * @property-read \App\UnidadMedida $unidad_origen
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ConversionUnidadMedida whereCodigo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ConversionUnidadMedida whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ConversionUnidadMedida whereFactor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ConversionUnidadMedida whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ConversionUnidadMedida whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ConversionUnidadMedida whereUnidadMedidaDestinoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ConversionUnidadMedida whereUnidadMedidaOrigenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ConversionUnidadMedida whereUpdatedAt($value)
 */
	class ConversionUnidadMedida extends \Eloquent {}
}

namespace App{
/**
 * App\Salida
 *
 * @property int $id
 * @property int|null $orden_pedido_id
 * @property int|null $produccion_id
 * @property int $unidad_medida_id
 * @property int|null $precio_id
 * @property float $cantidad
 * @property float $precio_unitario
 * @property float $venta_exenta
 * @property float $venta_gravada
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Movimiento $movimiento
 * @property-read \App\OrdenPedido|null $orden_pedido
 * @property-read \App\Precio|null $precio
 * @property-read \App\Produccion|null $produccion
 * @property-read \App\UnidadMedida $unidad_medida
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Salida whereCantidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Salida whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Salida whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Salida whereOrdenPedidoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Salida wherePrecioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Salida wherePrecioUnitario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Salida whereProduccionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Salida whereUnidadMedidaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Salida whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Salida whereVentaExenta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Salida whereVentaGravada($value)
 */
	class Salida extends \Eloquent {}
}

namespace App{
/**
 * App\Entrada
 *
 * @property int $id
 * @property int|null $compra_id
 * @property int|null $produccion_id
 * @property int $unidad_medida_id
 * @property float $cantidad
 * @property float $costo_unitario
 * @property float $costo_total
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Compra|null $compra
 * @property-read \App\Movimiento $movimiento
 * @property-read \App\Produccion|null $produccion
 * @property-read \App\UnidadMedida $unidad_medida
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entrada whereCantidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entrada whereCompraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entrada whereCostoTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entrada whereCostoUnitario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entrada whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entrada whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entrada whereProduccionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entrada whereUnidadMedidaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entrada whereUpdatedAt($value)
 */
	class Entrada extends \Eloquent {}
}

namespace App{
/**
 * App\Movimiento
 *
 * @property int $id
 * @property int $producto_id
 * @property int $tipo_movimiento_id
 * @property int|null $entrada_id
 * @property int|null $salida_id
 * @property int|null $ajuste_id
 * @property \Carbon\Carbon $fecha
 * @property string|null $detalle
 * @property float $cantidad
 * @property float $costo_unitario
 * @property float $costo_total
 * @property float|null $cantidad_existencia
 * @property float|null $costo_unitario_existencia
 * @property float|null $costo_total_existencia
 * @property \Carbon\Carbon|null $fecha_procesado
 * @property int $procesado
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Ajuste|null $ajuste
 * @property-read \App\Entrada|null $entrada
 * @property-read \App\Producto $producto
 * @property-read \App\Salida|null $salida
 * @property-read \App\TipoMovimiento $tipo_movimiento
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereAjusteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereCantidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereCantidadExistencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereCostoTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereCostoTotalExistencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereCostoUnitario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereCostoUnitarioExistencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereDetalle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereEntradaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereFechaProcesado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereProcesado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereProductoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereSalidaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereTipoMovimientoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereUpdatedAt($value)
 */
	class Movimiento extends \Eloquent {}
}

