@extends('adminlte::layouts.app')

@section('htmlheader_title')
    {{-- {{ trans('message.tituloProveedorNuevo') }} --}}
    Informe de cuentas por cobrar por antiguedad
@endsection

@section('CSSx')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
    {{-- DataPicker --}}
    <link rel="stylesheet" href="{{asset('/css/datapicker/bootstrap-datepicker3.css')}}">
@endsection

@section('contentheader_title')
    {{-- {{ trans('message.tituloProveedorNuevo') }} --}}
    Informe de cuentas por cobrar por antiguedad
@endsection

@section('contentheader_description')

@endsection

@section('main-content')

    @include('partials.alertas')

    <div class="box box-default box-solid no-print">
        <div class="box-header with-border">
            <h3 class="box-title">Herramientas</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
        </div><!-- /.box-header -->

        <!-- form start -->
        <form class="form-horizontal" action="{{ route('facturacionInformeFechaPost') }}" method="POST">
            {{ csrf_field() }}
            <div class="box-body">

            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ URL::previous() }}" class="btn btn-lg btn-default"><span class="fa fa-mail-reply"></span> Regresar</a>
                <a href='javascript:window.print(); void 0;' class="btn btn-lg btn-success pull-right" style="margin-left: 10px"><span class="fa fa-print"></span> Imprimir</a>
                <a href='{{route('cxcAntiguedadExcel')}}' class="btn btn-lg btn-success pull-right"><span class="fa fa-file-excel-o"></span> Exportar Excel</a>
            </div>
        </form>
    </div><!-- /.box -->

    <section class="invoice">
        <div class="row">
            {{--Encabezado--}}
            <div class="col-xs-12">
                <h2 class="page-header">
                    <i class="fa fa-globe"></i> LGL S.A. de C.V. -- Informe de cuentas por cobrar
                    <small class="pull-right">Generado: {{\Carbon\Carbon::now()->format('d/m/Y -- h:m:s A')}}</small>
                </h2>
                <p class="lead">Fecha de corte al día: {{ $extra['dia']->format('d/m/Y') }}</p>
                Generado por: {{Auth::user()->nombre}} {{Auth::user()->apellido}} <br>
            </div>

            {{-- Tabla de productos --}}
            <div class="col-xs-12" style="padding-top: 20px">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-striped table-condensed" id="tblProductos">
                        <thead>
                        <tr>
                            <th style="width: 10%">Vendedor</th>
                            <th style="width: 30%">Cliente</th>
                            <th style="width: 10%">N° documento</th>
                            <th style="width: 10%">Tipo doc</th>
                            <th style="width: 10%">Fecha</th>
                            <th style="width: 10%">Valor doc</th>
                            <th style="width: 10%">Saldo pendiente</th>
                            <th style="width: 10%">Antigüedad</th>
                        </tr>
                        </thead>
                        <tbody>
                            @php($total_saldos = 0.00)
                            @foreach($ventas as $venta)
                                <tr>
                                    <td>{{ $venta->vendedor->nombre }}</td>
                                    <td>{{ $venta->cliente->nombre }}</td>
                                    <td>{{ $venta->numero }}</td>
                                    @if($venta->tipo_documento->codigo == 'FAC')
                                        <td><span class="label label-info">{{ $venta->tipo_documento->codigo }}</span></td>
                                    @else
                                        <td><span class="label label-default">{{ $venta->tipo_documento->codigo }}</span></td>
                                    @endif
                                    <td>{{ $venta->fecha->format('d/m/Y') }}</td>
                                    <td>$ {{ number_format($venta->venta_total_con_impuestos,2) }}</td>
                                    <td>$ {{ number_format($venta->saldo,2) }}</td>
                                    <td>{{ $venta->antiguedad }}</td>
                                </tr>
                                @php($total_saldos += $venta->saldo)
                            @endforeach
                        </tbody>
                        <td colspan="6"><b>TOTAL SALDO PENDIENTE</b></td>
                        <td><b>$ {{ number_format($total_saldos,2) }}</b></td>
                        <td></td>
                        {{--<td><b>$ {{ number_format($extra['monto_iva_dia'],2) }}</b></td>--}}
                        {{--<td><b>$ {{ number_format($extra['monto_total_dia'],2) }}</b></td>--}}
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

