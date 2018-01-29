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
        <form class="form-horizontal" action="{{ route('ordenPedidoNuevaPost') }}" method="POST"
              enctype="multipart/form-data">
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
                            <input disabled type="text" class="form-control" placeholder="Seleccione el cliente"
                                   name="municipio" id="municipioID">
                        </div>
                    </div>

                    {{-- Direccion --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label">Dirección</label>
                        <div class="col-md-9 ">
                            <textarea disabled class="form-control" placeholder="Seleccione el cliente" name="direccion"
                                      id="direccionID"></textarea>
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
                        <label for="numeroID" class="col-md-4  control-label"><b>Orden venta n°</b></label>
                        <div class="col-md-8 ">
                            <input type="text" class="form-control" name="numero" id="numeroID">
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


                    <div class="form-group">
                        <label for="condicionPagoID" class="col-md-4  control-label"><b>Condición pago</b></label>
                        <div class="col-md-8 ">
                            <select class="form-control select2" style="width: 100%" name="condicion_pago_id"
                                    id="condicionPagoID">
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
                            <th style="width:30%">Producto</th>
                            <th style="width:20%">Presentación</th>
                            <th style="width:10%">Cantidad</th>
                            <th style="width:10%">Precio unitario</th>
                            <th style="width:10%">Ventas exentas</th>
                            <th style="width:10%">Ventas gravadas</th>
                            <th style="width:5%">Tipo venta</th>
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
                                <select class="form-control select2 selProd" style="width:100%" name="producto_id[]"
                                        id="selectProductos">
                                    <option selected disabled value="">-- Seleccione un producto --</option>
                                    <optgroup label="Productos con existencia">
                                        @foreach($productos as $producto)
                                            @if($producto->cantidad_existencia > 0 )
                                                <option value="{{ $producto->id }}"
                                                        data-preciounitario="{{ $producto->precio }}"
                                                        data-preciounitarioiva="{{$producto->precio_impuestos}}"
                                                        data-unidad="{{$producto->unidad_medida->id}}">{{ $producto->nombre }}
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

                            {{-- Presentacion --}}
                            <td>
                                <select class="form-control select2 presentacionCls" style="width:100%"
                                        name="presentacion_id[]" id="umSelect">
                                    <option selected disabled value="">-----</option>
                                </select>
                            </td>

                            {{--Cantidad--}}
                            <td>
                                <input type="number" class="form-control cantidadCls"
                                       step="0.001" min="0.001" name="cantidad[]"
                                       id="cantidadID" value="0" required>
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
                                <select class="form-control select2 tipoVentaCls" style="width:100%" name="tipo_venta[]"
                                        id="tipoVentaSelect">
                                    <option value="0">G</option>
                                    <option value="1">E</option>
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
                                    umCopia
                                )
                        )
                        .append
                        (
                            $('<td>')
                                .append
                                (
                                    '<input type="number" class="form-control cantidadCls"\n' +
                                    'step="0.001" min="0.001" name="cantidad[]"\n' +
                                    'id="cantidadID" value="0" required>'
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
                                    '<select class="form-control select2 tipoVentaCls" style="width:100%" name="tipo_venta[]" id="tipoVentaSelect">\n' +
                                    '<option value="0">G</option>\n' +
                                    '<option value="1">E</option>\n' +
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

        /**
         * Estado: Verificada
         */
        function funcionEliminarProducto() {
            // $(this).remove().end();
            // $(this).closest('tr').remove();
            // console.log($(this).parent().parent());
            $(this).parent().parent().remove();
            cambioTotal();
        }

        /**
         * Estado: Verificada
         */
        function agregarFuncion() {
            $('.cantidadCls').each(
                function (index, value) {
                    console.log('fx agregarFuncion');
                    $(this).change(cambioCantidad);
                    $(this).keyup(cambioCantidad);
                });
            $('.selProd').each(
                function (index, value) {
                    $(this).change(cambioProductoJS);
                });
            $('.tipoVentaCls').each(
                function (index, value) {
                    $(this).change(cambioTipoVenta);
                });
            $('.presentacionCls').each(
                function (index, value) {
                    $(this).change(cambioPresentacion);
                });
        }

        /**
         * Estado: Verificada
         */
        function cambioIVA() {
            $(".cantidadCls").each(
                function (index, value) {
//                    console.log($(this));
                    cambioTotalFila($(this));
                }
            );
        }

        /**
         * Estado: Verificada
         */
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

        /**
         * Estado: Verificada y funcionando
         */
        function cambioProductoJS() {
            // Se almacena el id del producto seleccionado en una variable
            let producto_select = $(this);
            let presentacion_select = producto_select.parent().parent().find('#umSelect');
            let cantidad_input = producto_select.parent().parent().find('#cantidadID');
            // Poner en select los diferentes precios del producto
            $.ajax({
                url: '/api/precios/' + producto_select.val(),
                type: 'GET',
                dataType: 'JSON',
                success: function (datos) {
                    presentacion_select.select2('destroy');
                    presentacion_select.empty();
                    for (var i = 0; i < datos.length; i++) {
                        if (i == 0) {
                            presentacion_select.append('<option selected value="' + datos[i].id + '" data-precio="' + datos[i].precio + '">' + datos[i].presentacion + ' -- ' + datos[i].unidad_medida_nombre + '</option>');
                        } else {
                            presentacion_select.append('<option value="' + datos[i].id + '" data-precio="' + datos[i].precio + '">' + datos[i].presentacion + ' -- ' + datos[i].unidad_medida_nombre + '</option>');
                        }
                    }
                    presentacion_select.select2();
                },
                async: false
            });
            cambioTotalFila(cantidad_input);
        }

        /**
         * Estado: Verificada y funcionando
         */
        function cambioTipoVenta() {
            let cantidad_input = $(this).parent().parent().find('#cantidadID');
            cambioTotalFila(cantidad_input);
        }

        /**
         * Estado: Verificada y funcionando
         */
        function cambioCantidad() {
            let cantidad_input = $(this);
            cambioTotalFila(cantidad_input);
        }

        /**
         * Estado:
         */
        function cambioPresentacion() {
            let cantidad_input = $(this).parent().parent().find('#cantidadID');
            cambioTotalFila(cantidad_input);
        }

        /**
         * Estado: Verificada y funcionando
         */
        function cambioTotalFila(cantidad_rev) {
            let cantidad_input = cantidad_rev;
            let cantidad = parseFloat(cantidad_input.val());
            let venta_gravada_input = cantidad_input.parent().parent().find('#ventasGravadas');
            let venta_exenta_input = cantidad_input.parent().parent().find('#ventasExentas');
            let tipo_venta = parseInt(cantidad_input.parent().parent().find('#tipoVentaSelect').val());
            let iva = parseInt($('#IVAid').val());
            let cero = 0;
            let precio_total = 0;
            let valor_IVA = 1.13;
            // Se busca el precio del producto
            let precio_unitario_input = cantidad_input.parent().parent().find('#precioUnitario');
            let precio_unitario = parseFloat(cantidad_input.parent().parent().find('#umSelect').find(':selected').data('precio'));
            // Verificar si es con o sin IVA
            if (iva === 0) {
                // Sin IVA
                precio_unitario_input.val(precio_unitario);
                precio_total = cantidad * precio_unitario;
            } else {
                // Con IVA
                precio_unitario = precio_unitario * valor_IVA;
                precio_unitario_input.val(precio_unitario.toFixed(2));
                precio_total = (cantidad * precio_unitario) * valor_IVA;
            }
            // Se verifica si es venta gravada o exenta
            if (tipo_venta === 0) {
                // Es venta gravada
                venta_gravada_input.val(precio_total.toFixed(2));
                venta_exenta_input.val(cero.toFixed(2));
            } else {
                // Es venta exenta
                venta_gravada_input.val(cero.toFixed(2));
                venta_exenta_input.val(precio_total.toFixed(2));
            }
            cambioTotal();
        }

        /**
         * Estado: Funcionando
         */
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

