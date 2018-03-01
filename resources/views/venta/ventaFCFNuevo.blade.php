@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Nueva venta
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
    {{-- DataPicker --}}
    <link rel="stylesheet" href="{{asset('/css/datepicker.css')}}">
@endsection

@section('contentheader_title')
    Nueva venta
@endsection

@section('contentheader_description')
    -- Factura de consumidor final de orden n°: {{$orden_pedido->numero}}
@endsection

@section('main-content')

    @include('partials.alertas')

    <!-- Form de nuevo proveedor -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Detalle de venta</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" action="{{ route('ventaNuevaPost', ['id' => $orden_pedido->id]) }}" method="POST" id="venta-form">
            {{ csrf_field() }}
            <div class="box-body">
                {{-- Cabecera --}}
                <div class="col-md-6 col-sm-12">

                    {{-- Fecha ingreso --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label">Fecha venta</label>
                        <div class="col-md-9 ">
                            <input readonly type="date" class="form-control" name="fecha"
                                   value="{{ $dia->format('Y-m-d') }}">
                        </div>
                    </div>

                    {{-- Cliente --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label">Cliente</label>
                        <div class="col-md-9 ">
                            <input readonly type="text" class="form-control" name="cliente_id"
                                   value="{{$orden_pedido->cliente->nombre}}">
                        </div>
                    </div>

                    {{-- Municipio --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label">Municipio</label>
                        <div class="col-md-9 ">
                            <input readonly type="text" class="form-control" name="municipio" id="municipioID"
                                   value="{{$orden_pedido->cliente->municipio->nombre}}">
                        </div>
                    </div>

                    {{-- Direccion --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label">Dirección</label>
                        <div class="col-md-9 ">
                            <textarea readonly class="form-control" placeholder="Seleccione el cliente" name="direccion"
                                      id="direccionID">{{$orden_pedido->cliente->direccion}}</textarea>
                        </div>
                    </div>

                    {{-- Condicion pago --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label">Condición pago</label>
                        <div class="col-md-9 ">
                            <input readonly type="text" class="form-control" name="condicion_pago_id"
                                   value="{{$orden_pedido->condicion_pago->nombre}}">
                        </div>
                    </div>

                </div>

                <div class="col-md-6 col-sm-12">

                    {{-- Numero documento venta --}}
                    <div class="form-group">
                        <label class="col-md-4  control-label"><b>N° Documento</b></label>
                        <div class="col-md-8 ">
                            <input type="text" class="form-control" name="numero"
                                   placeholder="Numero factura o Crédito Fiscal" value="">
                        </div>
                    </div>

                    {{-- Tipo Documento --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Tipo de documento</label>
                        <div class="col-sm-8">
                            <input readonly type="text" class="form-control" name="tipo_documento" value="{{ $orden_pedido->tipo_documento->nombre }}">
                        </div>
                    </div>

                    {{-- Numero Orden Pedido --}}
                    <div class="form-group">
                        <label class="col-md-4  control-label">Orden pedido n°:</label>
                        <div class="col-md-8 ">
                            <input readonly type="text" class="form-control" name="orden" value="{{$orden_pedido->numero}}">
                        </div>
                    </div>

                    {{-- Fecha entrega --}}
                    <div class="form-group">
                        <label class="col-md-4  control-label">Fecha entrega</label>
                        <div class="col-md-8 ">
                            @if($orden_pedido->fecha_entrega != null)
                                <input readonly type="date" class="form-control" name="fechaEntrega"
                                       value="{{$orden_pedido->fecha_entrega->format('Y-m-d')}}">
                            @else
                                <input readonly type="text" class="form-control" name="fechaEntrega"
                                       value="Sin fecha definida">
                            @endif
                        </div>
                    </div>

                    {{-- Despachado por --}}
                    <div class="form-group">
                        <label class="col-md-4  control-label">Vendedor</label>
                        <div class="col-md-8 ">
                            <input readonly type="text" class="form-control"
                                   value="{{$orden_pedido->vendedor->nombre}} {{$orden_pedido->vendedor->apellido}}"
                                   name="despachadoPor">
                        </div>
                    </div>

                </div>

                {{-- Fila --}}
                <div class="col-md-12">
                    {{-- Tabla de productos --}}
                    <table class="table table-bordered" id="tblProductos">
                        <tr>
                            <th style="width:40%">Producto</th>
                            <th style="width:7.5%">Unidad medida</th>
                            <th style="width:7.5%">Cantidad</th>
                            <th style="width:15%">Precio unitario</th>
                            <th style="width:15%">Ventas exentas</th>
                            <th style="width:15%">Ventas gravadas</th>
                        </tr>
                        @php( $iva = \App\Configuracion::find(1)->iva)
                        @foreach($orden_pedido->salidas as $salida)
                            <tr>
                                {{--Productos--}}
                                <td>
                                    @if( $salida->descripcion_presentacion != null)
                                        <input readonly type="text" class="form-control" name="productos_id[]"
                                               value="{{$salida->movimiento->producto->nombre}} ({{$salida->descripcion_presentacion}})">
                                    @else
                                        <input readonly type="text" class="form-control" name="productos_id[]"
                                               value="{{$salida->movimiento->producto->nombre}}">
                                    @endif
                                </td>
                                {{--Unidad de medida--}}
                                <td>
                                    <input readonly type="text" class="form-control unidadCls" name="" id="unidadMedida"
                                           value="{{$salida->unidad_medida->abreviatura}}">
                                </td>
                                {{--Cantidad--}}
                                <td>
                                    <input readonly type="text" class="form-control cantidadCls"
                                           pattern="^[+-]?[0-9]{1,3}(?:,?[0-9]{3})*(?:\.[0-9]{2})?$" name="cantidades[]"
                                           id="cantidad" value="{{$salida->cantidad}}">
                                </td>
                                {{--Precio unitario--}}
                                <td>
                                    <div class="input-group">
                                        <span class="input-group-addon">$</span>
                                        <input readonly type="text" class="form-control puCls"
                                               name="preciosUnitarios[]" id="precioUnitario"
                                               value="{{number_format(($salida->precio_unitario * $iva),4)}}">
                                    </div>
                                </td>
                                {{--Ventas exentas--}}
                                <td>
                                    <div class="input-group">
                                        <span class="input-group-addon">$</span>
                                        <input readonly type="text" class="form-control veCls" name="ventasExentas[]"
                                               id="ventasExentas" value="{{number_format(($salida->venta_exenta * $iva),2)}}">
                                    </div>
                                </td>
                                {{--Ventas afectas--}}
                                <td>
                                    <div class="input-group">
                                        <span class="input-group-addon">$</span>
                                        <input readonly type="text" class="form-control vaCls" name="ventasGravadas[]"
                                               id="ventasGravadas" value="{{number_format(($salida->venta_gravada * $iva),2)}}">
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </table>

                    <table class="table table-bordered">
                        <tr>
                            <th style="width:70%"></th>
                            <th style="width:15%">Venta Total</th>
                            <th style="width:15%">
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input readonly type="text" class="form-control"
                                           value="{{number_format(($orden_pedido->venta_total * $iva),2)}}" name="compraTotal"
                                           id="ventaTotal">
                                </div>
                            </th>
                        </tr>
                    </table>

                </div>
            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('ventaOrdenesLista') }}" class="btn btn-lg btn-default"><span
                            class="fa fa-close"></span> Cancelar</a>
                <button type="submit" class="btn btn-lg btn-success pull-right" id="enviar-buttom-id"><span class="fa fa-shopping-bag"></span>
                    Generar Factura
                </button>
            </div>
        </form>
    </div><!-- /.box -->

@endsection

@section('JSExtras')
    {{--Validacion--}}
    <script src="{{asset('/plugins/jquery-validation/dist/jquery.validate.js')}}"></script>
    <script src="{{asset('/plugins/jquery-validation/dist/additional-methods.min.js')}}"></script>
    @include('comun.select2Jses')
    <script>
        $(document).on('ready', Principal());

        function Principal() {
            Validacion();
        }

        function Validacion() {
            $('#venta-form').validate({
                ignore: [],
                onfocusout: false,
                onkeyup: false,
                rules: {
                    "numero" : {
                        required: true,
                    }
                },
                messages: {
                    "numero" : {
                        required: function () {
                            toastr.error('Por favor ingrese el numero de documento', 'Ups!');
                        },
                    }
                },
                submitHandler: function (form) {
                    $('#enviar-buttom-id').attr('disabled', 'true');
                    toastr.success('Por favor espere a que se procese','Excelente');
                    form.submit();
                }
            });
        }
    </script>
@endsection

