@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Orden de pedido
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
    {{-- DataPicker --}}
    <link rel="stylesheet" href="{{asset('/css/datepicker.css')}}">
@endsection

@section('contentheader_title')
    Orden de pedido n° {{$ordenPedido->numero}}
@endsection

@section('contentheader_description')

@endsection

@section('main-content')

    @include('partials.alertas')

    <!-- Form de nuevo proveedor -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Detalle</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal">
            {{ csrf_field() }}
            <div class="box-body">
                {{-- Cabecera --}}
                <div class="col-md-6 col-sm-12">

                    {{-- Fecha ingreso --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label"><b>Fecha venta</b></label>
                        <div class="col-md-9 ">
                            <div class="input-group">
                                <input readonly type="date" class="form-control" name="fechaIngreso"
                                       value="{{$ordenPedido->fecha->format('Y-m-d')}}">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Cliente --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label"><b>Cliente</b></label>
                        <div class="col-md-9 ">
                            <input readonly type="text" class="form-control" name="cliente_id"
                                   value="{{$ordenPedido->cliente->nombre}}">
                        </div>
                    </div>

                    {{-- Municipio --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label">Municipio</label>
                        <div class="col-md-9 ">
                            <input readonly type="text" class="form-control" name="municipio" id="municipioID" value="{{$ordenPedido->cliente->municipio->nombre}}">
                        </div>
                    </div>

                    {{-- Direccion --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label">Dirección</label>
                        <div class="col-md-9 ">
                            <textarea readonly class="form-control" name="direccion"
                                      >{{$ordenPedido->cliente->direccion}}</textarea>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12">

                    {{-- Numero Orden Pedido --}}
                    <div class="form-group">
                        <label class="col-md-4  control-label">Orden venta n°:</label>
                        <div class="col-md-8 ">
                            <input readonly type="text" class="form-control" name="numero" value="{{$ordenPedido->numero}}"
                                   >
                        </div>
                    </div>

                    {{--Tipo de documento--}}
                    <div class="form-group">
                        <label for="condicionPagoID" class="col-md-4  control-label"><b>Condición pago</b></label>
                        <div class="col-md-8 ">
                            <input readonly type="text" class="form-control" name="tipo_documento" value="{{$ordenPedido->tipo_documento->nombre}}"
                            >
                        </div>
                    </div>

                    {{-- Fecha entrega --}}
                    <div class="form-group">
                        <label class="col-md-4  control-label">Fecha entrega</label>
                        <div class="col-md-8 ">
                            @if($ordenPedido->fecha_entrega != null)
                                <div class="input-group">
                                    <input readonly type="date" class="form-control" name="fechaEntrega"
                                           value="{{$ordenPedido->fecha_entrega->format('Y-m-d')}}">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            @else
                                <input readonly type="text" class="form-control" name="fechaEntrega"
                                       value="Sin fecha definida">
                            @endif
                        </div>
                    </div>

                    {{-- Despachado por --}}
                    <div class="form-group">
                        <label class="col-md-4  control-label"><b>Vendedor</b></label>
                        <div class="col-md-8 ">
                            <input readonly type="text" class="form-control"
                                   value="{{$ordenPedido->vendedor->nombre}} {{$ordenPedido->vendedor->apellido}}"
                                   name="despachadoPor">
                        </div>
                    </div>

                    {{-- Condicion pago --}}
                    <div class="form-group">
                        <label class="col-md-4  control-label">Condición pago</label>
                        <div class="col-md-8 ">
                            <input type="text" class="form-control" name="condicionPago"
                                   value="{{$ordenPedido->condicion_pago->nombre}}" readonly>
                        </div>
                    </div>

                    {{-- Ruta archivo --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label">Copia orden</label>
                        <div class="col-md-8">
                            <a class="btn btn-lg btn-default" target="_blank"
                               href="{{Storage::url($ordenPedido->ruta_archivo)}}">Ver archivo</a>
                        </div>
                    </div>

                </div>

                {{-- Fila --}}
                <div class="col-md-12">
                    {{-- Tabla de productos --}}
                    <table class="table table-bordered" id="tblProductos">
                        <tr>
                            <th style="width:40%">Producto</th>
                            <th style="width:5%">Unidad medida</th>
                            <th style="width:10%">Cantidad</th>
                            <th style="width:15%">Precio unitario</th>
                            <th style="width:15%">Ventas exentas</th>
                            <th style="width:15%">Ventas gravadas</th>
                        </tr>
                        @foreach($ordenPedido->salidas as $salida)
                            <tr>
                                {{--Productos--}}
                                <td>
                                    @if($salida->descripcion_presentacion == null)
                                        <input readonly type="text" class="form-control" name="producto_id" id="" value="{{ $salida->movimiento->producto()->withTrashed()->first()->nombre }}">
                                    @else
                                        <input readonly type="text" class="form-control" name="producto_id" id="" value="{{ $salida->movimiento->producto()->withTrashed()->first()->nombre }} ({{ $salida->descripcion_presentacion}})">
                                    @endif
                                </td>
                                {{--Unidad de medida--}}
                                <td>
                                    <input type="text" class="form-control unidadCls" name="" id="unidadMedida"
                                           value="{{$salida->unidad_medida->abreviatura}}" disabled>
                                </td>
                                {{--Cantidad--}}
                                <td>
                                    <input type="text" class="form-control cantidadCls"
                                           pattern="^[+-]?[0-9]{1,3}(?:,?[0-9]{3})*(?:\.[0-9]{2})?$" name="cantidades[]"
                                           id="cantidad" value="{{$salida->cantidad}}" disabled>
                                </td>
                                {{--Precio unitario--}}
                                <td>
                                    <div class="input-group">
                                        <span class="input-group-addon">$</span>
                                        <input type="text" class="form-control puCls"
                                               pattern="^[+-]?[0-9]{1,3}(?:,?[0-9]{3})*(?:\.[0-9]{2})?$"
                                               name="preciosUnitarios[]" id="precioUnitario"
                                               value="{{number_format($salida->precio_unitario,5)}}" disabled>
                                    </div>
                                </td>
                                {{--Ventas exentas--}}
                                <td>
                                    <div class="input-group">
                                        <span class="input-group-addon">$</span>
                                        <input type="text" class="form-control veCls" name="ventasExentas[]"
                                               id="ventasExentas" value="{{number_format($salida->venta_exenta,2)}}"
                                               disabled>
                                    </div>
                                </td>
                                {{--Ventas afectas--}}
                                <td>
                                    <div class="input-group">
                                        <span class="input-group-addon">$</span>
                                        <input type="text" class="form-control vaCls" name="ventasGravadas[]"
                                               id="ventasGravadas" value="{{number_format($salida->venta_gravada,2)}}"
                                               disabled>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </table>

                    <table class="table table-bordered">
                        @if($ordenPedido->tipo_documento->codigo == 'CCF')
                            <tr>
                                <th style="width:70%"></th>
                                <th style="width:15%">Subtotal</th>
                                <th style="width:15%">
                                    <div class="input-group">
                                        <span class="input-group-addon">$</span>
                                        <input type="number" class="form-control"
                                               value="{{number_format(($ordenPedido->venta_total),2)}}" name="iva"
                                               id="ventaTotal" disabled>
                                    </div>
                                </th>
                            </tr>
                            <tr>
                                @php( $iva = \App\Configuracion::find(1)->iva)
                                <th style="width:70%"></th>
                                <th style="width:15%">13% IVA</th>
                                <th style="width:15%">
                                    <div class="input-group">
                                        <span class="input-group-addon">$</span>
                                        <input type="number" class="form-control"
                                               value="{{number_format(($ordenPedido->venta_total * ($iva - 1)),2)}}" name="iva"
                                               id="ventaTotal" disabled>
                                    </div>
                                </th>
                            </tr>
                        @endif
                        <tr>
                            <th style="width:70%"></th>
                            <th style="width:15%">Venta Total</th>
                            <th style="width:15%">
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="number" class="form-control"
                                           value="{{number_format(($ordenPedido->venta_total * $iva),2)}}" name="compraTotal"
                                           id="ventaTotal" disabled>
                                </div>
                            </th>
                        </tr>
                    </table>

                </div>
            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('ordenPedidoLista') }}" class="btn btn-lg btn-default"><span class="fa fa-mail-reply"></span> Regresar</a>
                <a href="{{ route('ordenPedidoPDF',['id' => $ordenPedido->id]) }}" target="_blank"
                   class="btn btn-lg btn-info pull-right"><span class="fa fa-print"></span> Imprimir Orden</a>
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

