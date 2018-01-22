@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Orden de compra
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
@endsection

@section('contentheader_title')
    Orden de pedido
@endsection

@section('contentheader_description')
    -- Realizar una nueva orden de pedido
@endsection

@section('main-content')

    @include('partials.alertas')

    <!-- Form de nuevo proveedor -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Detalle</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" action="{{ route('ordenPedidoNuevaPost') }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="box-body">
                {{-- Cabecera --}}
                <div class="col-md-6 col-sm-12">

                    {{-- Fecha ingreso --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label"><b>Fecha venta</b></label>
                        <div class="col-md-9 ">
                            <input type="date" class="form-control" name="fecha">
                        </div>
                    </div>

                    {{-- Cliente --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label"><b>Cliente</b></label>
                        <div class="col-md-9 ">
                            <select class="form-control select2" style="width: 100%" name="cliente_id" id="clienteID"
                                    onchange="cambioCliente()">
                                <option value="" selected disabled>Seleciona un cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}"
                                            data-direccion="{{$cliente->direccion}}"
                                            data-municipio="{{$cliente->municipio->nombre}}">{{ $cliente->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Municipio --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label">Municipio</label>
                        <div class="col-md-9 ">
                            <input disabled type="text" class="form-control" placeholder="Seleccione el cliente" name="municipio" id="municipioID">
                        </div>
                    </div>

                    {{-- Direccion --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label">Dirección</label>
                        <div class="col-md-9 ">
                            <textarea disabled class="form-control" placeholder="Seleccione el cliente" name="direccion" id="direccionID"></textarea>
                        </div>
                    </div>

                    {{-- Con impuestos --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label">Precio</label>
                        <div class="col-md-9 ">
                            <select class="form-control select2" style="width: 100%" id="IVAid" onchange="cambioIVA()">
                                <option value="0" selected>Precio sin IVA</option>
                                <option value="1">Precio con IVA</option>
                            </select>
                        </div>
                    </div>

                </div>

                <div class="col-md-6 col-sm-12">

                    {{-- Numero Orden Pedido --}}
                    <div class="form-group">
                        <label class="col-md-4  control-label"><b>Orden venta n°</b></label>
                        <div class="col-md-8 ">
                            <input type="text" class="form-control" name="numero">
                        </div>
                    </div>

                    {{-- Fecha entrega --}}
                    <div class="form-group">
                        <label class="col-md-4  control-label">Fecha entrega</label>
                        <div class="col-md-8 ">
                            <input type="date" class="form-control" name="fecha_entrega">
                        </div>
                    </div>

                    {{-- Despachado por --}}
                    <div class="form-group">
                        <label class="col-md-4  control-label"><b>Vendedor</b></label>
                        <div class="col-md-8 ">
                            <input type="text" class="form-control"
                                   value="{{ Auth::user()->nombre }} {{ Auth::user()->apellido }}" disabled
                                   name="despachadoPor">
                        </div>
                    </div>

                    {{-- Condicion pago --}}
                    {{--<div class="form-group">--}}
                    {{--<label class="col-md-4  control-label">Condición pago</label>--}}
                    {{--<div class="col-md-8 ">--}}
                    {{--<input type="text" class="form-control" name="condicion_pago_id">--}}
                    {{--</div>--}}
                    {{--</div>--}}

                    <div class="form-group">
                        <label class="col-md-4  control-label"><b>Condición pago</b></label>
                        <div class="col-md-8 ">
                            <select class="form-control select2" style="width: 100%" name="condicion_pago_id">
                                <option value="" selected disabled>Selecciona una condición de pago</option>
                                @foreach($condiciones_pago as $condicion_pago)
                                    <option value="{{ $condicion_pago->id }}">{{ $condicion_pago->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Ruta archivo --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label">Copia orden</label>
                        <div class="col-md-8">
                            <input type="file" class="form-control file" name="archivo">
                        </div>
                    </div>

                </div>

                {{-- Fila --}}
                <div class="col-md-12">
                    {{-- Tabla de productos --}}
                    <table class="table table-bordered" id="tblProductos">
                        <tr>
                            <th style="width:40%">Producto -- (Cantidad existencia)</th>
                            <th style="width:7.5%">Unidad medida</th>
                            <th style="width:10%">Cantidad</th>
                            <th style="width:10%">Precio unitario</th>
                            <th style="width:10%">Ventas exentas</th>
                            <th style="width:10%">Ventas gravadas</th>
                            <th style="width:7.5%">Tipo venta</th>
                            <th style="width:5%">
                                <button class="btn btn-success" id="btnNuevoProducto" onclick="funcionNuevoProducto()"
                                        type="button">
                                    <span class="fa fa-plus"></span>
                                </button>
                            </th>
                        </tr>
                        <tr id="rowProducto1">
                            {{--Productos--}}
                            <td>
                                <select class="form-control select2 selProd" style="width:100%" name="productos_id[]"
                                        id="selectProductos">
                                    <option selected disabled value="">-- Seleccione un producto --</option>
                                    <optgroup label="Productos con existencia">
                                        @foreach($productos as $producto)
                                            @if($producto->cantidad_existencia > 0 )
                                                <option value="{{ $producto->id }}"
                                                        data-preciounitario="{{ $producto->precio }}"
                                                        data-preciounitarioiva="{{$producto->precio_impuestos}}"
                                                        data-unidad="{{$producto->unidad_medida->id}}">{{ $producto->nombre }}
                                                    --
                                                    ({{$producto->cantidad_existencia}} {{$producto->unidad_medida->abreviatura}}
                                                    )
                                                </option>
                                            @endif
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="Productos sin existencia">
                                        @foreach($productos as $producto)
                                            @if($producto->cantidad_existencia == 0 )
                                                <option value="{{ $producto->id }}"
                                                        data-preciounitario="{{ $producto->precio }}"
                                                        data-preciounitarioiva="{{$producto->precio_impuestos}}"
                                                        data-unidad="{{$producto->unidad_medida->id}}">{{ $producto->nombre }}
                                                    --
                                                    ({{$producto->cantidad_existencia}} {{$producto->unidad_medida->abreviatura}}
                                                    )
                                                </option>
                                            @endif
                                        @endforeach
                                    </optgroup>
                                </select>
                            </td>

                            {{--Unidad de medida--}}
                            <td>
                                <select class="form-control select2 unidadCls" style="width:100%"
                                        name="unidad_medida_id[]" id="umSelect">
                                    @foreach($unidad_medidas as $unidad_medida)
                                        <option value="{{ $unidad_medida->id }}">{{ $unidad_medida->abreviatura }}</option>
                                    @endforeach
                                </select>
                            </td>

                            {{--Cantidad--}}
                            <td>
                                <input type="number" class="form-control cantidadCls"
                                       step="0.001" min="0.001" name="cantidades[]"
                                       id="cantidad" value="0" required>
                            </td>

                            {{--Precio unitario--}}
                            <td>
                                <div class="input-group">
                                    <input disabled type="text" class="form-control puCls"
                                           pattern="^[+-]?[0-9]{1,3}(?:,?[0-9]{3})*(?:\.[0-9]{2})?$"
                                           name="preciosUnitarios[]" id="precioUnitario" required>
                                </div>
                            </td>

                            {{--Ventas exentas--}}
                            <td>
                                <div class="input-group">
                                    <input disabled type="text" class="form-control veCls" name="ventasExentas[]"
                                           id="ventasExentas">
                                </div>
                            </td>

                            {{--Ventas afectas--}}
                            <td>
                                <div class="input-group">
                                    <input disabled type="text" class="form-control vaCls" name="ventasGravadas[]"
                                           id="ventasGravadas">
                                </div>
                            </td>

                            {{--Exenta--}}
                            <td align="center">
                                <select class="form-control select2 tipoVentaCls" style="width:100%" name="tipoVenta[]"
                                        id="tipoVentaSelect">
                                    <option value="0">Gravada</option>
                                    <option value="1">Exenta</option>
                                </select>
                            </td>
                            <td align="center">

                            </td>
                        </tr>
                    </table>

                    <table class="table table-bordered">
                        <tr>
                            <th style="width:65%"></th>
                            <th style="width:15%">Venta Total</th>
                            <th style="width:15%">
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="number" class="form-control" value="0.00" name="compraTotal"
                                           id="ventaTotal" disabled>
                                </div>
                            </th>
                            <th style="width:5%"></th>
                        </tr>
                    </table>

                </div>
            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('ordenPedidoLista') }}" class="btn btn-lg btn-default"><span
                            class="fa fa-close"></span> Cancelar</a>
                <button type="submit" class="btn btn-lg btn-success pull-right"><span class="fa fa-floppy-o"></span>
                    Guardar
                </button>
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
            $(":input").click(function () {
                $(this).select();
            });
            selecionarValor();
            agregarFuncion();
        }

        function selecionarValor() {
            $(":input").click(function () {
                $(this).select();
            });
//            $(".costoUnitarioCls,.costoTotalCls").focusout(function () {
//                var numeroDato = ($(this).val().length === 0) ? 0 : parseFloat($(this).val());
//                $(this).val(numeroDato.toFixed(2));
//            });
            $(".cantidadCls").focusout(function () {
                var numeroCantidad = ($(this).val().length === 0) ? 0 : parseFloat($(this).val());
                $(this).val(numeroCantidad);
            });
        }

        function funcionNuevoProducto() {
            copia = $('#selectProductos').clone(false);
            umCopia = $('#umSelect').clone(false);
            $('#tblProductos')
                .append
                (
                    $('<tr>').attr('id', 'rowProducto')
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
//                                    '<input type="text" class="form-control unidadCls" name="" id="unidadMedida" disabled>'
                                    umCopia
                                )
                        )
                        .append
                        (
                            $('<td>')
                                .append
                                (
                                    '<input type="number" class="form-control cantidadCls"\n' +
                                    'step="0.001" min="0.001" name="cantidades[]"\n' +
                                    'id="cantidad" value="0" required>'
                                )
                        )
                        .append
                        (
                            $('<td>')
                                .append
                                (
                                    '<div class="input-group">\n' +
                                    '<input disabled type="text" class="form-control puCls" pattern="^[+-]?[0-9]{1,3}(?:,?[0-9]{3})*(?:\\.[0-9]{2})?$" name="preciosUnitarios[]" id="precioUnitario" required>\n' +
                                    '</div>'
                                )
                        )
                        .append
                        (
                            $('<td>')
                                .append
                                (
                                    '<div class="input-group">\n' +
                                    '<input disabled type="text" class="form-control veCls" name="ventasExentas[]" id="ventasExentas">\n' +
                                    '</div>'
                                )
                        )
                        .append
                        (
                            $('<td>')
                                .append
                                (
                                    '<div class="input-group">\n' +
                                    '<input disabled type="text" class="form-control vaCls" name="ventasGravadas[]" id="ventasGravadas">\n' +
                                    '</div>'
                                )
                        )
                        .append
                        (
                            $('<td>').attr('align', 'center')
                                .append
                                (
                                    '<select class="form-control select2 tipoVentaCls" style="width:100%" name="tipoVenta[]" id="tipoVentaSelect">\n' +
                                    '<option value="0">Gravada</option>\n' +
                                    '<option value="1">Exenta</option>\n' +
                                    '</select>'
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
            selecionarValor();
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
                    $(this).change(cambioUnidadMedida);
                    $(this).keyup(cambioUnidadMedida);
                });
            $('.selProd').each(
                function (index, value) {
                    $(this).change(cambioProductoJS);
                });
            $('.tipoVentaCls').each(
                function (index, value) {
                    $(this).change(cambioTipoVenta);
                });
            $('.unidadCls').each(
                function (index, value) {
                    $(this).change(cambioUnidadMedida);
                });
        }

        function cambioIVA() {
            $(".cantidadCls").each(
                cambioUnidadMedida
            );
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
//            var productoPrecioUnitario = $(this).parent().parent().find('option[value="' + idSelect + '"]').data('preciounitario');
            if ($("#IVAid").val() == 0) {
                var productoPrecioUnitario = $(this).parent().parent().find('#selectProductos').find('option[value="' + idSelect + '"]').data('preciounitario');
                console.log('Sin IVA: ' + productoPrecioUnitario);
            } else {
                var productoPrecioUnitario = $(this).parent().parent().find('#selectProductos').find('option[value="' + idSelect + '"]').data('preciounitarioiva');
                console.log('Con IVA: ' + productoPrecioUnitario);
            }
            console.log(productoPrecioUnitario);
            var umId = $(this).parent().parent().find('option[value="' + idSelect + '"]').data('unidad');
            var cantidad = $(this).parent().parent().find('#cantidad').val();
            var costoTotal = productoPrecioUnitario * cantidad;
            costoTotal = costoTotal.toFixed(2);
            var puntoStr = 0.00;
            $(this).parent().parent().find('#precioUnitario').val(productoPrecioUnitario.toFixed(2));
            $(this).parent().parent().find('#umSelect').select2('destroy');
            $(this).parent().parent().find('#umSelect').val(umId);
            $(this).parent().parent().find('#umSelect').select2({
                // Activamos la opcion "Tags" del plugin
                tags: false,
                tokenSeparators: [','],
                ajax: {
                    dataType: 'json',
                    url: '{{ route('unidadesMedidaJSON') }}',
                    delay: 250,
                    data: function (params) {
                        return {
                            umo: umId
                        }
                    },
                    processResults: function (data, page) {
                        return {
                            results: data
                        };
                    },
                },
            });

            if ($(this).parent().parent().find('#tipoVenta').is(":checked")) {
                $(this).parent().parent().find('#ventasGravadas').val(puntoStr.toFixed(2));
                $(this).parent().parent().find('#ventasExentas').val(costoTotal);
            } else {
                $(this).parent().parent().find('#ventasGravadas').val(costoTotal);
                $(this).parent().parent().find('#ventasExentas').val(puntoStr.toFixed(2));
            }
            cambioTotal();
        }

        function cambioTipoVenta() {
            var cero = 0;
            if ($(this).parent().parent().find('#tipoVentaSelect').val() == 1) {
                var ventasExentas = $(this).parent().parent().find('#ventasGravadas').val();
                $(this).parent().parent().find('#ventasGravadas').val(cero.toFixed(2));
                $(this).parent().parent().find('#ventasExentas').val(ventasExentas);
            } else {
                var ventasGravadas = $(this).parent().parent().find('#ventasExentas').val();
                $(this).parent().parent().find('#ventasGravadas').val(ventasGravadas);
                $(this).parent().parent().find('#ventasExentas').val(cero.toFixed(2));
            }
            cambioTotal();
        }

        function cambioUnidadMedida() {
            console.log('ejecuto cambio unidad medida');
            var productoId = $(this).parent().parent().find('#selectProductos').val();
            if ($("#IVAid").val() == 0) {
                var productoPrecioUnitario = $(this).parent().parent().find('#selectProductos').find('option[value="' + productoId + '"]').data('preciounitario');
                console.log('Sin IVA: ' + productoPrecioUnitario);
            } else {
                var productoPrecioUnitario = $(this).parent().parent().find('#selectProductos').find('option[value="' + productoId + '"]').data('preciounitarioiva');
                console.log('Con IVA: ' + productoPrecioUnitario);
            }
            var umOrigen = $(this).parent().parent().find('#selectProductos').find('option[value="' + productoId + '"]').data('unidad');
            var umDestino = $(this).val();
            var umDestino = $(this).parent().parent().find('#umSelect').val();
            var puntoStr = 0.00;
            var factor;
            var cantidad = 0;
            if (umOrigen == umDestino) {
                cantidad = $(this).parent().parent().find('#cantidad').val();
//                productoPrecioUnitario = $(this).parent().parent().find('#selectProductos').find('option[value="' + productoId + '"]').data('preciounitarioiva');
                $(this).parent().parent().find('#precioUnitario').val(productoPrecioUnitario.toFixed(2));
                var costoTotal = productoPrecioUnitario * cantidad;
            } else {
                $.ajax({
                    url: '/dev/factorJSON?umo=' + umOrigen + '&umd=' + umDestino,
                    type: 'GET',
                    dataType: 'JSON',
                    success: function (data) {
                        factor = data;
                        $('#factorID').val(factor);
                    },
                    async: false
                });
                console.log("Factor = " + factor);
                cantidad = $(this).parent().parent().find('#cantidad').val();
                productoPrecioUnitario = productoPrecioUnitario / factor;
                console.log("Precio nuevo = " + productoPrecioUnitario);
                $(this).parent().parent().find('#precioUnitario').val(productoPrecioUnitario.toFixed(5));
                var costoTotal = productoPrecioUnitario * cantidad;
            }
            if ($(this).parent().parent().find('#tipoVenta').is(":checked")) {
                $(this).parent().parent().find('#ventasGravadas').val(puntoStr.toFixed(2));
                $(this).parent().parent().find('#ventasExentas').val(costoTotal.toFixed(2));
            } else {
                $(this).parent().parent().find('#ventasGravadas').val(costoTotal.toFixed(2));
                $(this).parent().parent().find('#ventasExentas').val(puntoStr.toFixed(2));
            }
            cambioTotal();
        }

        function cambioCliente() {
            var idc = $("#clienteID").val();
            var direccion = $("#clienteID").find('option[value="' + idc + '"]').data('direccion');
            var municipio = $("#clienteID").find('option[value="' + idc + '"]').data('municipio');
            $("#direccionID").val(direccion);
            $("#municipioID").val(municipio);
        }

    </script>

    @include('comun.select2Jses')
@endsection

