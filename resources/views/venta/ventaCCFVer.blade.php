@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Crédito Fiscal
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
    {{-- DataPicker --}}
    <link rel="stylesheet" href="{{asset('/css/datepicker.css')}}">
@endsection

@section('contentheader_title')
    Crédito Fiscal n° {{$venta->numero}}
@endsection

@section('contentheader_description')

@endsection

@section('main-content')

    @include('partials.alertas')

    <!-- Form de nuevo proveedor -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Detalle de venta</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" action="{{ route('ventaNuevaPost', ['id' => $ordenPedido->id]) }}" method="POST">
            {{ csrf_field() }}
            <div class="box-body">
                {{-- Cabecera --}}
                <div class="col-md-6 col-sm-12">

                    {{-- Fecha ingreso --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label"><b>Fecha venta</b></label>
                        <div class="col-md-9 ">
                            <input disabled type="date" class="form-control" name="fechaIngreso"
                                   value="{{$ordenPedido->fechaIngreso->format('Y-d-m')}}">
                        </div>
                    </div>

                    {{-- Cliente --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label"><b>Cliente</b></label>
                        <div class="col-md-9 ">
                            <select class="form-control select2" style="width: 100%" name="cliente_id" disabled>
                                <option value="" selected disabled>Seleciona un cliente</option>
                                @foreach($clientes as $cliente)
                                    @if($cliente->id == $ordenPedido->cliente_id)
                                        <option selected value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                    @else
                                        <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- NRC --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label"><b>NRC</b></label>
                        <div class="col-md-9 ">
                            <input disabled type="text" class="form-control" name="nrc" value="{{$venta->nrc}}" placeholder="Numero Registro Comercial">
                        </div>
                    </div>

                    {{-- Condicion pago --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label">Condición pago</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control" name="condicionPago"
                                   value="{{$ordenPedido->condicionPago}}" disabled>
                        </div>
                    </div>

                    {{-- Municipio --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label">Municipio</label>
                        <div class="col-md-9 ">
                            <select class="form-control select2" style="width: 100%" name="municipio_id" disabled>
                                @foreach($municipios as $municipio)
                                    @if($municipio->id == $ordenPedido->id)
                                        <option selected value="{{ $municipio->id }}">{{ $municipio->nombre }}</option>
                                    @else
                                        <option value="{{ $municipio->id }}">{{ $municipio->nombre }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Direccion --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label">Dirección</label>
                        <div class="col-md-9 ">
                            <textarea class="form-control" name="direccion"
                                      disabled>{{$ordenPedido->direccion}}</textarea>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12">

                    {{-- Numero documento venta --}}
                    <div class="form-group">
                        <label class="col-md-4  control-label">N° Documento:</label>
                        <div class="col-md-8 ">
                            <input disabled type="text" class="form-control" name="numero" placeholder="Numero factura o Crédito Fiscal" value="{{$venta->numero}}">
                        </div>
                    </div>

                    {{-- Numero Orden Pedido --}}
                    <div class="form-group">
                        <label class="col-md-4  control-label">Orden pedido n°:</label>
                        <div class="col-md-8 ">
                            <input type="text" class="form-control" name="numero" value="{{$ordenPedido->numero}}"
                                   disabled>
                        </div>
                    </div>

                    {{-- Fecha entrega --}}
                    <div class="form-group">
                        <label class="col-md-4  control-label">Fecha entrega</label>
                        <div class="col-md-8 ">
                            @if($ordenPedido->fechaEntrega != null)
                                <input type="date" class="form-control" name="fechaEntrega"
                                       value="{{$ordenPedido->fechaEntrega->format('Y-m-d')}}" disabled>
                            @else
                                <input type="text" class="form-control" name="fechaEntrega"
                                       value="Sin fecha definida" disabled>
                            @endif
                        </div>
                    </div>

                    {{-- Despachado por --}}
                    <div class="form-group">
                        <label class="col-md-4  control-label"><b>Vendedor</b></label>
                        <div class="col-md-8 ">
                            <input type="text" class="form-control"
                                   value="{{$ordenPedido->vendedor->nombre}} {{$ordenPedido->vendedor->apellido}}"
                                   disabled name="despachadoPor">
                        </div>
                    </div>

                    {{-- Ruta archivo --}}
                    {{--<div class="form-group">--}}
                        {{--<label class="col-md-4 control-label">Copia documento</label>--}}
                        {{--<div class="col-md-8">--}}
                            {{--<input type="file" class="form-control" name="archivo">--}}
                        {{--</div>--}}
                    {{--</div>--}}

                </div>

                {{-- Fila --}}
                <div class="col-md-12">
                    {{-- Tabla de productos --}}
                    <table class="table table-bordered" id="tblProductos">
                        <tr>
                            <th style="width:40%">Producto</th>
                            <th style="width:5%">Unidad medida</th>
                            <th style="width:5%">Cantidad</th>
                            <th style="width:12.5%">Precio unitario</th>
                            <th style="width:15%">Ventas exentas</th>
                            <th style="width:15%">Ventas gravadas</th>
                        </tr>
                        @foreach($salidas as $salida)
                            <tr>
                                {{--Productos--}}
                                <td>
                                    <select class="form-control select2 selProd" style="width:100%" name="productos_id[]"
                                            id="selectProductos" disabled>
                                        @foreach($productos as $producto)
                                            @if($producto->id == $salida->movimiento->producto_id)
                                                <option selected value="{{ $producto->id }}" data-cu="{{ $producto->precio }}" data-um="{{$producto->unidadMedida->abreviatura}}">{{ $producto->nombre }}) </option>
                                            @else
                                                <option value="{{ $producto->id }}" data-cu="{{ $producto->precio }}" data-um="{{$producto->unidadMedida->abreviatura}}">{{ $producto->nombre }}) </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </td>
                                {{--Unidad de medida--}}
                                <td>
                                    <input type="text" class="form-control unidadCls" name="" id="unidadMedida" value="{{$salida->unidadMedida->abreviatura}}" disabled>
                                </td>
                                {{--Cantidad--}}
                                <td>
                                    <input type="text" class="form-control cantidadCls"
                                           pattern="^[+-]?[0-9]{1,3}(?:,?[0-9]{3})*(?:\.[0-9]{2})?$" name="cantidades[]"
                                           id="cantidad" value="{{$salida->cantidadOP}}" disabled>
                                </td>
                                {{--Precio unitario--}}
                                <td>
                                    <div class="input-group">
                                        <span class="input-group-addon">$</span>
                                        <input type="text" class="form-control puCls"
                                               pattern="^[+-]?[0-9]{1,3}(?:,?[0-9]{3})*(?:\.[0-9]{2})?$"
                                               name="preciosUnitarios[]" id="precioUnitario" value="{{number_format($salida->precioUnitarioOP,5)}}" disabled>
                                    </div>
                                </td>
                                {{--Ventas exentas--}}
                                <td>
                                    <div class="input-group">
                                        <span class="input-group-addon">$</span>
                                        <input type="text" class="form-control veCls" name="ventasExentas[]"
                                               id="ventasExentas" value="{{number_format($salida->ventaExenta,2)}}" disabled>
                                    </div>
                                </td>
                                {{--Ventas afectas--}}
                                <td>
                                    <div class="input-group">
                                        <span class="input-group-addon">$</span>
                                        <input type="text" class="form-control vaCls" name="ventasGravadas[]"
                                               id="ventasGravadas" value="{{number_format($salida->ventaGravada,2)}}" disabled>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </table>

                    <table class="table table-bordered">
                        <tr>
                            <th style="width:65%"></th>
                            <th style="width:15%">Subtotal</th>
                            <th style="width:15%">
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="number" class="form-control" value="{{number_format($ordenPedido->ventasGravadas,2)}}" name="compraTotal"
                                           id="ventaTotal" disabled>
                                </div>
                            </th>
                        </tr>

                        <tr>
                            <th></th>
                            <th>13% IVA</th>
                            <th>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="number" class="form-control" value="{{number_format($ordenPedido->porcentajeIVA,2)}}" name="compraTotal"
                                           id="ventaTotal" disabled>
                                </div>
                            </th>
                        </tr>

                        <tr>
                            <th></th>
                            <th>Venta Total</th>
                            <th>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="number" class="form-control" value="{{number_format($ordenPedido->ventaTotal,2)}}" name="compraTotal"
                                           id="ventaTotal" disabled>
                                </div>
                            </th>
                        </tr>
                    </table>

                </div>
            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('ventaFacturaLista') }}" class="btn btn-lg btn-default">Ver lista</a>
                <a href="{{ route('CCFPDF',['id' => $venta->id]) }}" class="btn btn-lg btn-success pull-right">Imprimir CCF</a>
            </div>
        </form>
    </div><!-- /.box -->

@endsection

@section('JSExtras')
    {{-- Funcion para cargar mas filas de productos --}}
    <script>
        $(document).on('ready', funcionPrincipal());

        function funcionPrincipal() {
            $("body").on("click", ".btn-danger", funcionEliminarProducto);
            agregarFuncion();
        }

        var numero = 2;

        function funcionNuevoProducto() {
            copia = $('#selectProductos').clone(false);
            $('#tblProductos')
                .append
                (
                    $('<tr>').attr('id', 'rowProducto' + numero)
                        .append
                        (
                            $('<td>')
                                .append
                                (
                                    copia
                                )
                        )
                        .append
                        (
                            $('<td>')
                                .append
                                (
                                    '<input type="text" class="form-control unidadCls" name="" id="unidadMedida" disabled>'
                                )
                        )
                        .append
                        (
                            $('<td>')
                                .append
                                (
                                    '<input type="text" class="form-control cantidadCls" pattern="^[+-]?[0-9]{1,3}(?:,?[0-9]{3})*(?:\\.[0-9]{2})?$" name="cantidades[]" id="cantidad" required>'
                                )
                        )
                        .append
                        (
                            $('<td>')
                                .append
                                (
                                    '<div class="input-group">\n' +
                                    '<span class="input-group-addon">$</span>\n' +
                                    '<input type="text" class="form-control puCls" pattern="^[+-]?[0-9]{1,3}(?:,?[0-9]{3})*(?:\\.[0-9]{2})?$" name="preciosUnitarios[]" id="precioUnitario" required>\n' +
                                    '</div>'
                                )
                        )
                        .append
                        (
                            $('<td>')
                                .append
                                (
                                    '<div class="input-group">\n' +
                                    '<span class="input-group-addon">$</span>\n' +
                                    '<input type="text" class="form-control veCls" name="ventasExentas[]" id="ventasExentas">\n' +
                                    '</div>'
                                )
                        )
                        .append
                        (
                            $('<td>')
                                .append
                                (
                                    '<div class="input-group">\n' +
                                    '<span class="input-group-addon">$</span>\n' +
                                    '<input type="text" class="form-control vaCls" name="ventasGravadas[]" id="ventasGravadas">\n' +
                                    '</div>'
                                )
                        )
                        .append
                        (
                            $('<td>').attr('align', 'center')
                                .append
                                (
                                    '<input type="checkbox" class="tipoVentaCls" id="tipoVenta" name="exentas[]">'
                                )
                        )
                        .append
                        (
                            $('<td>').attr('align', 'center')
                                .append
                                (
                                    '<button type="button" class="btn btn-danger" click="funcionEliminarProducto()" type="button"><span class="fa fa-remove"></span></button>'
                                )
                        )
                );
            //Initialize Select2 Elements
            $(".select2").select2();
            $(".select2").select2();
            numero++;
            agregarFuncion();
        }

        function funcionEliminarProducto() {
            // $(this).remove().end();
            // $(this).closest('tr').remove();
            // console.log($(this).parent().parent());
            $(this).parent().parent().remove();
            cambioTotal();
        }

        function agregarFuncion() {
            $('.cantidadCls').each(
                function (index, value) {
                    $(this).change(cambioProductoJS);
                    $(this).keyup(cambioProductoJS);
                });
            $('.selProd').each(
                function (index, value) {
                    $(this).change(cambioProductoJS);
                });
            $('.tipoVentaCls').each(
                function (index, value) {
                    $(this).change(cambioTipoVenta);
                });
        }

        function cambioTotal() {
            var ventaTotal = 0;
            $(".veCls").each(
                function (index, value) {
                    if ($.isNumeric($(this).val())) {
                        ventaTotal += eval($(this).val());
                    }
                }
            );
            $(".vaCls").each(
                function (index, value) {
                    if ($.isNumeric($(this).val())) {
                        ventaTotal += eval($(this).val());
                    }
                }
            );
            $("#ventaTotal").val(ventaTotal.toFixed(2));
        }

        function cambioProductoJS() {
            var idSelect = $(this).parent().parent().find('#selectProductos').val();
            var cu = $(this).parent().parent().find('option[value="' + idSelect + '"]').data('cu');
            var um = $(this).parent().parent().find('option[value="' + idSelect + '"]').data('um');
            var cantidad = $(this).parent().parent().find('#cantidad').val();
            var costoTotal = cu * cantidad;
            costoTotal = costoTotal.toFixed(2);
            var puntoStr = 0.00;
            $(this).parent().parent().find('#precioUnitario').val(cu);
            $(this).parent().parent().find('#unidadMedida').val(um);
            if ($(this).parent().parent().find('#tipoVenta').is(":checked")) {
                $(this).parent().parent().find('#ventasGravadas').val(puntoStr);
                $(this).parent().parent().find('#ventasExentas').val(costoTotal);
            } else {
                $(this).parent().parent().find('#ventasGravadas').val(costoTotal);
                $(this).parent().parent().find('#ventasExentas').val(puntoStr);
            }
            cambioTotal();
        }

        function cambioTipoVenta() {
            var idSelect = $(this).parent().parent().find('#selectProductos').val();
            var cu = $(this).parent().parent().find('option[value="' + idSelect + '"]').data('cu');
            var um = $(this).parent().parent().find('option[value="' + idSelect + '"]').data('um');
            var cantidad = $(this).parent().parent().find('#cantidad').val();
            var costoTotal = cu * cantidad;
            costoTotal = costoTotal.toFixed(2);
            var puntoStr = 0.00;
            $(this).parent().parent().find('#precioUnitario').val(cu);
            $(this).parent().parent().find('#unidadMedida').val(um);
            if ($(this).parent().parent().find('#tipoVenta').is(":checked")) {
                $(this).parent().parent().find('#ventasGravadas').val(puntoStr);
                $(this).parent().parent().find('#ventasExentas').val(costoTotal);
            } else {
                $(this).parent().parent().find('#ventasGravadas').val(costoTotal);
                $(this).parent().parent().find('#ventasExentas').val(puntoStr);
            }
            cambioTotal();
        }

    </script>
    @include('comun.select2Jses')
@endsection

