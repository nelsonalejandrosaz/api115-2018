@extends('adminlte::layouts.app')

@section('htmlheader_title')
    {{-- {{ trans('message.tituloProveedorNuevo') }} --}}
    Detalle de saldo de clientes
@endsection

@section('CSSx')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
    {{-- DataPicker --}}
    <link rel="stylesheet" href="{{asset('/css/datapicker/bootstrap-datepicker3.css')}}">
@endsection

@section('contentheader_title')
    {{-- {{ trans('message.tituloProveedorNuevo') }} --}}
    Detalle de saldo de clientes
@endsection

@section('contentheader_description')

@endsection

@section('main-content')

    @include('partials.alertas')

    <!-- Form de nuevo proveedor -->
    <div class="box box-default">
        {{--<div class="box-header with-border">--}}
            {{--<h3 class="box-title">Detalle de formula</h3>--}}
        {{--</div><!-- /.box-header -->--}}
        <!-- form start -->
        <form id="formDatos" class="form-horizontal">
            {{ csrf_field() }}
            <div class="box-body">

                {{-- Fila  --}}
                <div class="col-md-6">

                    <h4>Datos cliente</h4>
                    <br>

                    {{-- Cliente --}}
                    <div class="form-group">
                        <label class="col-md-3 control-label">Cliente</label>
                        <div class="col-md-9">
                            <input readonly type="text" class="form-control"
                                   value="{{ $cliente->nombre }}"
                                   name="producto_id">
                        </div>
                    </div>

                    {{-- Contacto --}}
                    <div class="form-group">
                        <label class="col-md-3 control-label">Nombre contacto</label>
                        <div class="col-md-9">
                            <input readonly type="text" class="form-control"
                                   value="{{ $cliente->nombre_contacto }}"
                                   name="producto_id">
                        </div>
                    </div>

                    {{-- Numero 1 --}}
                    <div class="form-group">
                        <label class="col-md-3 control-label">Teléfono 1</label>
                        <div class="col-md-9">
                            <input readonly type="text" class="form-control"
                                   value="{{ $cliente->telefono_1 }}"
                                   name="producto_id">
                        </div>
                    </div>

                    {{-- Unidad de medida formula--}}
                    {{--<div class="form-group">--}}
                        {{--<label class="col-md-4 control-label">Unidad de medida</label>--}}
                        {{--<div class="col-md-8">--}}
                            {{--<input readonly type="text" class="form-control"--}}
                                   {{--value="{{ $formula->producto->unidad_medida->nombre }}" id="unidadMedidalbl">--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    {{-- Descripcion --}}
                    {{--<div class="form-group">--}}
                        {{--<label class="col-md-4 control-label">Descripción</label>--}}
                        {{--<div class="col-md-8">--}}
                            {{--<textarea readonly class="form-control"--}}
                                      {{--name="descripcion">{{$formula->descripcion}}</textarea>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                </div>

                <div class="col-md-6">

                    <h4>Datos saldos</h4>
                    <br>

                    {{--Version --}}
                    <div class="form-group">
                        <label class="col-md-3 control-label">Saldo total</label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input readonly type="number" class="form-control cant"
                                       value="{{ number_format($cliente->saldo,2) }}" name="">
                            </div>
                        </div>
                    </div>

                    {{-- Fecha --}}
                    {{--<div class="form-group">--}}
                        {{--<label class="col-md-4 control-label"><b>Fecha ingreso</b></label>--}}
                        {{--<div class="col-md-8">--}}
                            {{--<div class="input-group">--}}
                                {{--<input readonly type="date" class="form-control" name="fecha"--}}
                                       {{--value="{{$formula->fecha->format('Y-m-d')}}">--}}
                                {{--<div class="input-group-addon">--}}
                                    {{--<i class="fa fa-calendar"></i>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    {{-- Estado --}}
                    {{--<div class="form-group">--}}
                        {{--<label class="col-md-4 control-label">Estado</label>--}}
                        {{--<div class="col-md-8">--}}
                            {{--@if($formula->activa)--}}
                                {{--<input readonly type="text" class="form-control"--}}
                                       {{--value="Activa"--}}
                                       {{--name="version">--}}
                            {{--@else--}}
                                {{--<input readonly type="text" class="form-control"--}}
                                       {{--value="No activa"--}}
                                       {{--name="version">--}}
                            {{--@endif--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    {{-- Ingresado por --}}
                    {{--<div class="form-group">--}}
                        {{--<label class="col-md-4 control-label">Ingresado por</label>--}}
                        {{--<div class="col-md-8">--}}
                            {{--<input readonly type="text" class="form-control"--}}
                                   {{--value="{{$formula->ingresado->nombre}} {{$formula->ingresado->apellido}}"--}}
                                   {{--name="ingresadoPor">--}}
                        {{--</div>--}}
                    {{--</div>--}}
                </div>
                {{-- Fin fila --}}

                {{-- Fila --}}
                <div class="col-md-12">
                    <h4>Lista de documentos pendientes</h4>
                    {{-- Tabla de productos --}}
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover" id="tblProductos">
                            <tr>
                                <th style="width: 20%">N° documento</th>
                                <th style="width: 20%">Tipo documento</th>
                                <th style="width: 20%">Condición de pago</th>
                                <th style="width: 20%">Total documento</th>
                                <th style="width: 20%">Saldo pendiente</th>
                                </th>
                            </tr>
                            @php( $i = 1 )
                            @foreach($cliente->ordenes_pedidos as $orden_pedido)
                                <tr>
                                    <td>
                                        @if($orden_pedido->venta->tipo_documento_id == 1)
                                            <a href="{{ route('ventaVerFactura',['id' => $orden_pedido->venta->id]) }}">{{$orden_pedido->venta->numero}}</a>
                                        @else
                                            <a href="{{ route('ventaVerFactura',['id' => $orden_pedido->venta->id]) }}">{{$orden_pedido->venta->numero}}</a>
                                        @endif
                                    </td>
                                    <td>
                                        {{$orden_pedido->venta->tipo_documento->nombre}}
                                    </td>
                                    <td>
                                        {{$orden_pedido->condicion_pago->nombre}}
                                    </td>
                                    <td>
                                        $ {{number_format($orden_pedido->venta->venta_total_con_impuestos,2)}}
                                    </td>
                                    <td>
                                        $ {{number_format($orden_pedido->venta->saldo,2)}}
                                    </td>
                                </tr>
                                @php( $i++ )
                            @endforeach
                        </table>
                    </div>

                </div>
                {{-- Fin fila --}}

            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('clienteSaldoLista') }}" class="btn btn-lg btn-default"><span
                            class="fa fa-mail-reply"></span> Regresar a lista</a>
                {{--@if(!$formula->activa)--}}
                    {{--<a href="{{ route('formulaLista') }}" class="btn btn-lg btn-warning pull-right"><span--}}
                                {{--class="fa fa-linux"></span> Activar formula</a>--}}
                {{--@endif--}}
            </div>
        </form>
    </div><!-- /.box -->

@endsection

@section('JSx')
    {{-- Funcion para cargar mas filas de productos --}}
    <script>
        $(document).on('ready', funcionPrincipal());

        function funcionPrincipal() {
            $("body").on("click", ".btn-danger", funcionEliminarProducto);
            calcularTotal();
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
                                    numero
                                )
                        )
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
                                    '<div class="input-group"><input type="number" class="form-control cant" value="1" min="1" max="100" name="porcentajes[]" onchange="calcularTotal()"><span class="input-group-addon">%</span></div>'
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
            calcularTotal();
        }

        function funcionEliminarProducto() {
            // $(this).remove().end();
            // $(this).closest('tr').remove();
            // console.log($(this).parent().parent());
            $(this).parent().parent().remove();
            calcularTotal();
        }

        function verificarSuma() {
            var total = $('#totalPorcentajeInput').val();
            if (total != 100) {
                $('#divErrorSuma').show();
                setTimeout(function () {
                    $('#divErrorSuma').hide();
                }, 3000);
            } else {
                $('#formDatos').submit();
            }
        }

        function calcularTotal() {
            var totalPorcentaje = 0;
            var porcentajes = $('.cant');
            for (var i = 0; i < porcentajes.length; i++) {
                porcentaje = parseFloat(porcentajes[i].value);
                totalPorcentaje = totalPorcentaje + porcentaje;
            }
            $('#totalPorcentajeInput').val(totalPorcentaje);
            // console.log(totalPorcentaje);
        }

    </script>
    {{-- Fin de funcion para cargar mas filas de productos --}}

    <!-- Select2 -->
    <script src="{{asset('/plugins/select2.full.min.js')}}"></script>
    {{-- Data Picker --}}
    <script src="{{asset('/js/datapicker/bootstrap-datepicker.js')}}"></script>
    <script>
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
            // Inicializar el datapicker
            $('.datepicker').datepicker(
                {
                    format: "yyyy/mm/dd",
                    todayBtn: "linked",
                    language: "es",
                    autoclose: true
                });

        });
    </script>
@endsection

