@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Orden de compra
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
    {{-- DataPicker --}}
    <link rel="stylesheet" href="{{asset('/css/datepicker.css')}}">
@endsection

@section('contentheader_title')
    Orden de compra
@endsection

@section('contentheader_description')

@endsection

@section('main-content')

    @include('partials.alertas')

    <!-- Form de nuevo proveedor -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Detalle</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" action="{{ route('ordenPedidoNuevaPost') }}" method="POST">
            {{ csrf_field() }}
            <div class="box-body">
                {{-- Cabecera --}}
                <div class="col-md-6 col-sm-12">

                    {{-- Fecha ingreso --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label"><b>Fecha venta</b></label>
                        <div class="col-md-9 ">
                            <input type="date" class="form-control" name="fechaIngreso">
                        </div>
                    </div>

                    {{-- Cliente --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label"><b>Cliente</b></label>
                        <div class="col-md-9 ">
                            <select class="form-control select2" style="width: 100%" name="cliente_id">
                                <option value="" selected disabled>Seleciona un cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- Municipio --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label">Municipio</label>
                        <div class="col-md-9 ">
                            <select class="form-control select2" style="width: 100%" name="municipio_id">
                                <option value="" selected disabled>Seleciona un municipio</option>
                                @foreach($municipios as $municipio)
                                    <option value="{{ $municipio->id }}">{{ $municipio->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Direccion --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label">Dirección</label>
                        <div class="col-md-9 ">
                            <textarea class="form-control" name="direccion"></textarea>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12">

                    {{-- Numero Orden Pedido --}}
                    <div class="form-group">
                        <label class="col-md-4  control-label">Orden venta n°:</label>
                        <div class="col-md-8 ">
                            <input type="text" class="form-control" name="numero">
                        </div>
                    </div>

                    {{-- Fecha entrega --}}
                    <div class="form-group">
                        <label class="col-md-4  control-label">Fecha entrega</label>
                        <div class="col-md-8 ">
                            <input type="date" class="form-control" name="fechaEntrega">
                        </div>
                    </div>

                    {{-- Despachado por --}}
                    <div class="form-group">
                        <label class="col-md-4  control-label"><b>Vendedor</b></label>
                        <div class="col-md-8 ">
                            <input type="text" class="form-control" value="{{ Auth::user()->nombre }} {{ Auth::user()->apellido }}" disabled name="despachadoPor">
                        </div>
                    </div>

                    {{-- Condicion pago --}}
                    <div class="form-group">
                        <label class="col-md-4  control-label">Condición pago</label>
                        <div class="col-md-8 ">
                            <input type="text" class="form-control" name="condicionPago">
                        </div>
                    </div>

                    {{-- Ruta archivo --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label">Copia orden</label>
                        <div class="col-md-8">
                            <input type="file" class="form-control" name="archivo">
                        </div>
                    </div>

                </div>

                {{-- Fila --}}
                <div class="col-md-12">
                    {{-- Tabla de productos --}}
                    <table class="table table-bordered" id="tblProductos">
                        <tr>
                            <th style="width:40%">Producto -- (Cantidad existencia)</th>
                            <th style="width:5%">Unidad medida</th>
                            <th style="width:5%">Cantidad</th>
                            <th style="width:12.5%">Precio unitario</th>
                            <th style="width:15%">Ventas exentas</th>
                            <th style="width:15%">Ventas gravadas</th>
                            <th style="width:2.5%">E</th>
                            <th style="width:5%">
                                <button class="btn btn-success" id="btnNuevoProducto" onclick="funcionNuevoProducto()" type="button">
                                    <span class="fa fa-plus"></span>
                                </button>
                            </th>
                        </tr>
                        <tr id="rowProducto1">
                            {{--Productos--}}
                            <td>
                                <select class="form-control select2 selProd" name="productos_id[]" id="selectProductos">
                                    <option selected disabled value="">-- Seleccione un producto --</option>
                                    @foreach($productos as $producto)
                                        <option value="{{ $producto->id }}" data-cu="{{ $producto->precio }}" data-um="{{$producto->unidadMedida->abreviatura}}">{{ $producto->nombre }} -- ({{$producto->cantidadExistencia}})</option>
                                    @endforeach
                                </select>
                            </td>
                            {{--Unidad de medida--}}
                            <td>
                                <input type="text" class="form-control unidadCls" name="" id="unidadMedida" disabled>
                            </td>
                            {{--Cantidad--}}
                            <td>
                                <input type="text" class="form-control cantidadCls" pattern="^[+-]?[0-9]{1,3}(?:,?[0-9]{3})*(?:\.[0-9]{2})?$" name="cantidades[]" id="cantidad" required>
                            </td>
                            {{--Precio unitario--}}
                            <td>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" class="form-control puCls" pattern="^[+-]?[0-9]{1,3}(?:,?[0-9]{3})*(?:\.[0-9]{2})?$" name="preciosUnitarios[]" id="precioUnitario" required>
                                </div>
                            </td>
                            {{--Ventas exentas--}}
                            <td>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" class="form-control veCls" name="ventasExentas[]" id="ventasExentas">
                                </div>
                            </td>
                            {{--Ventas afectas--}}
                            <td>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" class="form-control vaCls" name="ventasGravadas[]" id="ventasGravadas">
                                </div>
                            </td>
                            {{--Exenta--}}
                            <td align="center">
                                <input type="checkbox" class="tipoVentaCls" id="tipoVenta" name="exentas[]">
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
                                    <input type="number" class="form-control" value="0.00" name="compraTotal" id="ventaTotal" disabled>
                                </div>
                            </th>
                            <th style="width:5%"></th>
                        </tr>
                    </table>

                </div>
            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('ordenPedidoLista') }}" class="btn btn-lg btn-default">Cancelar</a>
                <button type="submit" class="btn btn-lg btn-success pull-right">Guardar</button>
            </div>
        </form>
    </div><!-- /.box -->

@endsection

@section('JSExtras')
    {{-- Funcion para cargar mas filas de productos --}}
    <script>
        $(document).on('ready', funcionPrincipal());

        function funcionPrincipal() {
            $("body").on( "click", ".btn-danger",funcionEliminarProducto);
            agregarFuncion();
        }
        var numero = 2;

        function funcionNuevoProducto() {
            copia = $('#selectProductos').clone(false);
            $('#tblProductos')
                .append
                (
                    $('<tr>').attr('id','rowProducto'+numero)
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
                            $('<td>').attr('align','center')
                                .append
                                (
                                    '<input type="checkbox" class="tipoVentaCls" id="tipoVenta" name="exentas[]">'
                                )
                        )
                        .append
                        (
                            $('<td>').attr('align','center')
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
                function(index, value){
                    $(this).change(cambioProductoJS);
                    $(this).keyup(cambioProductoJS);
                });
            $('.selProd').each(
                function(index, value){
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
                function(index, value) {
                    if ( $.isNumeric( $(this).val() ) ){
                        ventaTotal += eval($(this).val());
                    }
                }
            );
            $(".vaCls").each(
                function(index, value) {
                    if ( $.isNumeric( $(this).val() ) ){
                        ventaTotal += eval($(this).val());
                    }
                }
            );
            $("#ventaTotal").val(ventaTotal.toFixed(2));
        }

        function cambioProductoJS() {
            var idSelect = $(this).parent().parent().find('#selectProductos').val();
            var cu = $(this).parent().parent().find('option[value="'+idSelect+'"]').data('cu');
            var um = $(this).parent().parent().find('option[value="'+idSelect+'"]').data('um');
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
            var cu = $(this).parent().parent().find('option[value="'+idSelect+'"]').data('cu');
            var um = $(this).parent().parent().find('option[value="'+idSelect+'"]').data('um');
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

