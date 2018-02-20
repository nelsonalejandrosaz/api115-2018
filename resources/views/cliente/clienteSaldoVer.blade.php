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

    <section class="invoice">
        <div class="row">
            {{--Encabezado--}}
            <div class="col-xs-12">
                <h2 class="page-header">
                    <i class="fa fa-globe"></i> LGL S.A. de C.V.
                    <small class="pull-right">Generado: {{\Carbon\Carbon::now()->format('d/m/Y -- h:m:s A')}}</small>
                </h2>
                <p class="lead">Informe de ventas por cliente realizadas al dia: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
                Cliente: {{ $cliente->nombre }}<br>
                Contacto: {{ ($cliente->contacto == null) ? 'Sin nombre de contacto' : $cliente->contacto }} <br>
                Teléfono: {{ ($cliente->telefono_1 == null) ? 'Sin teléfono de contacto' : $cliente->telefono_1 }}
            </div>

            {{-- Tabla de productos --}}
            <div class="col-xs-12" style="padding-top: 20px">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover" id="tblProductos">
                        <thead>
                            <tr>
                                <th style="width: 10%">Fecha</th>
                                <th style="width: 10%">N° doc</th>
                                <th style="width: 20%">Tipo documento</th>
                                <th style="width: 15%">Condición de pago</th>
                                <th style="width: 15%">Total documento</th>
                                <th style="width: 15%">Saldo pendiente</th>
                                <th style="width: 15%">Estado</th>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        @php( $i = 1 )
                        @foreach($cliente->ventas as $venta)
                            <tr>
                                <td>{{ $venta->fecha->format('d/m/Y') }}</td>
                                <td>
                                    @if($venta->tipo_documento_id == 1)
                                        <a href="{{ route('ventaVerFactura',['id' => $venta->id]) }}">{{$venta->numero}}</a>
                                    @else
                                        <a href="{{ route('ventaVerFactura',['id' => $venta->id]) }}">{{$venta->numero}}</a>
                                    @endif
                                </td>
                                <td>
                                    {{$venta->tipo_documento->nombre}}
                                </td>
                                <td>
                                    {{$venta->orden_pedido->condicion_pago->nombre}}
                                </td>
                                <td>
                                    $ {{number_format($venta->venta_total_con_impuestos,2)}}
                                </td>
                                <td>
                                    $ {{number_format($venta->saldo,2)}}
                                </td>
                                <td>
                                    @if($venta->estado_venta->codigo == 'PP')
                                        <span class="label label-warning">{{ $venta->estado_venta->nombre }}</span>
                                    @elseif($venta->estado_venta->codigo == 'PG')
                                        <span class="label label-success">{{ $venta->estado_venta->nombre }}</span>
                                    @elseif($venta->estado_venta->codigo == 'AN')
                                        <span class="label label-danger">{{ $venta->estado_venta->nombre }}</span>
                                    @endif
                                </td>
                            </tr>
                            @php( $i++ )
                        @endforeach
                        </tbody>
                        <tfoot>

                        </tfoot>
                    </table>
                </div>

            </div>
        </div>

    </section>

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

