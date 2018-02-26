@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Orden de compra
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
    {{--Alertify--}}
    <link rel="stylesheet" href="{{asset('/plugins/alertify/themes/alertify.core.css')}}"/>
    <link rel="stylesheet" href="{{asset('/plugins/alertify/themes/alertify.default.css')}}"/>
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
              enctype="multipart/form-data" id="orden-form">
            {{ csrf_field() }}
            <div class="box-body">
                {{-- Cabecera --}}
                <div class="col-md-6 col-sm-12">

                    {{-- Fecha ingreso --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label"><b>Fecha venta</b></label>
                        <div class="col-md-9 ">
                            <div class="input-group">
                                <input type="date" class="form-control" name="fecha"
                                       value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
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
                            <select class="form-control select2" style="width: 100%" name="cliente_id" id="clienteID"
                                    onchange="cambioCliente()">
                                <option value="" selected disabled>Seleciona un cliente</option>
                                @foreach($clientes as $cliente)
                                    @if($cliente->id == old('cliente_id'))
                                        <option selected value="{{ $cliente->id }}"
                                                data-direccion="{{$cliente->direccion}}"
                                                data-municipio="{{$cliente->municipio->nombre}}">{{ $cliente->nombre }}</option>
                                    @else
                                        <option value="{{ $cliente->id }}"
                                                data-direccion="{{$cliente->direccion}}"
                                                data-municipio="{{$cliente->municipio->nombre}}">{{ $cliente->nombre }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Municipio --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label">Municipio</label>
                        <div class="col-md-9 ">
                            <input readonly type="text" class="form-control" placeholder="Seleccione el cliente"
                                   name="municipio" id="municipioID" value="{{ old('municipio') }}">
                        </div>
                    </div>

                    {{-- Direccion --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label">Dirección</label>
                        <div class="col-md-9 ">
                            <textarea readonly class="form-control" placeholder="Seleccione el cliente" name="direccion"
                                      id="direccionID">{{ old('direccion') }}</textarea>
                        </div>
                    </div>

                    {{-- Tipo Documento --}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><b>Tipo documento</b></label>
                        <div class="col-sm-9">
                            <select class="form-control select2" style="width:100%" name="tipo_documento_id" id="IVAid"
                                    onchange="cambioIVA()">
                                <option selected disabled>Seleccione una opción</option>
                                @foreach($tipoDocumentos as $tipoDocumento)
                                    @if($tipoDocumento->id == old('tipo_documento_id'))
                                        <option selected
                                                value="{{ $tipoDocumento->id }}">{{ $tipoDocumento->nombre }}</option>
                                    @else
                                        <option value="{{ $tipoDocumento->id }}">{{ $tipoDocumento->nombre }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>

                <div class="col-md-6 col-sm-12">

                    {{-- Numero Orden Pedido --}}
                    <div class="form-group">
                        <label for="numeroID" class="col-md-4  control-label"><b>Orden pedido n°</b></label>
                        <div class="col-md-8 ">
                            <input type="number" class="form-control" name="numero" id="numeroID"
                                   value="{{ old('numero') }}"
                                   placeholder="{{ \App\OrdenPedido::latest()->first()->id + 1 }}">
                        </div>
                    </div>

                    {{-- Fecha entrega --}}
                    <div class="form-group">
                        <label class="col-md-4  control-label">Fecha entrega</label>
                        <div class="col-md-8 ">
                            <div class="input-group">
                                <input type="date" class="form-control" name="fecha_entrega"
                                       value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
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

                    {{--Condicion de pago--}}
                    <div class="form-group">
                        <label for="condicionPagoID" class="col-md-4  control-label"><b>Condición pago</b></label>
                        <div class="col-md-8 ">
                            <select class="form-control select2" style="width: 100%" name="condicion_pago_id"
                                    id="condicionPagoID">
                                <option selected disabled>Selecciona una condición de pago</option>
                                @foreach($condiciones_pago as $condicion_pago)
                                    @if($condicion_pago->id == old('condicion_pago_id'))
                                        <option selected
                                                value="{{ $condicion_pago->id }}">{{ $condicion_pago->nombre }}</option>
                                    @else
                                        <option value="{{ $condicion_pago->id }}">{{ $condicion_pago->nombre }}</option>
                                    @endif
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
                            <th style="width:30%">Código -- Producto</th>
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
                                                        data-unidad="{{$producto->unidad_medida->id}}">{{ $producto->codigo }}
                                                    -- {{ $producto->nombre }}
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
                                                        data-unidad="{{$producto->unidad_medida->id}}">{{ $producto->codigo }}
                                                    -- {{ $producto->nombre }}
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
                                <input type="number" class="form-control cantidadCls" name="cantidad[]"
                                       id="cantidadID" value="0" required>
                            </td>

                            {{--Precio unitario--}}
                            <td>
                                <div class="input-group">
                                    <input readonly type="number" class="form-control puCls" step="any"
                                           name="precios_unitario[]" id="precioUnitario">
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
                <button type="submit" class="btn btn-lg btn-success pull-right" id="enviar-buttom"><span
                            class="fa fa-floppy-o"></span>
                    Guardar
                </button>
            </div>
        </form>
    </div><!-- /.box -->

@endsection

@section('JSExtras')
    {{--Alertify--}}
    <script type="text/javascript" src="{{'/plugins/alertify/lib/alertify.js'}}"></script>
    {{--Validacion--}}
    <script src="{{asset('/plugins/jquery-validation/dist/jquery.validate.js')}}"></script>
    <script src="{{asset('/plugins/jquery-validation/dist/additional-methods.min.js')}}"></script>
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
            Validacion();
        }

        function Validacion() {
            $('#orden-form').validate({
                ignore: [],
                onfocusout: false,
                onkeyup: false,
                rules: {
                    "fecha": {
                        required: true,
                    },
                    "cliente_id": {
                        required: true,
                    },
                    "tipo_documento_id": {
                        required: true,
                    },
                    "fecha_entrega": {
                        required: true,
                    },
                    "condicion_pago_id": {
                        required: true,
                    },
                    "producto_id[]": {
                        required: true,
                    },
                    "presentacion_id[]": {
                        required: true,
                    },
                    "cantidad[]": {
                        required: true,
                        min: 0.001,
                    },
                },
                messages: {
                    "fecha": {
                        required: function () {
                            toastr.error('Por favor digite la fecha', 'Ups!');
                        },
                    },
                    "cliente_id": {
                        required: function () {
                            toastr.error('Por favor seleccione el cliente', 'Ups!');
                        },
                    },
                    "tipo_documento_id": {
                        required: function () {
                            toastr.error('Por favor seleccione el tipo de documento', 'Ups!');
                        },
                    },
                    "fecha_entrega": {
                        required: function () {
                            toastr.error('Por favor complete la fecha de entrega', 'Ups!');
                        },
                    },
                    "condicion_pago_id": {
                        required: function () {
                            toastr.error('Por favor seleccione la condicion de pago', 'Ups!');
                        },
                    },
                    "producto_id[]": {
                        required: function () {
                            toastr.error('Por favor seleccione el producto', 'Ups!');
                        },
                    },
                    "presentacion_id[]": {
                        required: function () {
                            toastr.error('Por favor seleccione la presentación', 'Ups!');
                        },
                    },
                    "cantidad[]": {
                        required: function () {
                            toastr.error('Por favor complete la cantidad', 'Ups!');
                        },
                        min: function () {
                            toastr.error('La cantidad debe ser mayor a cero', 'Ups!');
                        },
                    },
                },
                submitHandler: function (form) {
                    $('#enviar-buttom').attr('disabled', 'true');
                    toastr.success('Por favor espere a que se procese', 'Excelente');
                    form.submit();
                }
            });
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
                                    'name="cantidad[]"\n' +
                                    'id="cantidadID" value="0">'
                                )
                        )
                        .append
                        (
                            $('<td>')
                                .append
                                (
                                    '<div class="input-group">\n' +
                                    '<input readonly type="number" step="any" class="form-control puCls" name="precios_unitario[]" id="precioUnitario">\n' +
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
            if (iva === 2) {
                // Sin IVA
                precio_unitario_input.val(precio_unitario);
                precio_total = cantidad * precio_unitario;
            } else {
                // Con IVA
                precio_unitario = precio_unitario * valor_IVA;
                precio_unitario_input.val(precio_unitario.toFixed(2));
                precio_total = cantidad * precio_unitario;
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

