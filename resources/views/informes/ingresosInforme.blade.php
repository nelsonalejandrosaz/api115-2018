@extends('adminlte::layouts.app')

@section('htmlheader_title')
    {{-- {{ trans('message.tituloProveedorNuevo') }} --}}
    Informe de ingresos diarios
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
    {{-- Daterange --}}
    <link rel="stylesheet" href="{{asset('/plugins/daterangepicker.css')}}">
@endsection

@section('contentheader_title')
    {{-- {{ trans('message.tituloProveedorNuevo') }} --}}
    Informe de ingresos diarios
@endsection

@section('contentheader_description')

@endsection

@section('main-content')

    @include('partials.alertas')

    <div class="row no-print">
        <div class="col-xs-12">

            {{--Barra herramientas--}}
            <div class="box box-default box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Herramientas</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div><!-- /.box-header -->

                <!-- form start -->
                <form class="form-horizontal" action="{{ route('ingresoVentasPost') }}" method="POST" id="fechas-form-id">
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="col-md-6 col-sm-12">

                            <h4>Fecha consultada</h4>
                            <br>

                            {{-- Fecha inicio --}}
                            <div class="form-group">
                                <label class="col-md-4 control-label"><b>Fecha</b></label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input type="date" class="form-control" name="fecha" id="fecha-inicio" value="{{ $extra['dia']->format('Y-m-d') }}">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div><!-- /.box-body -->

                    <div class="box-footer">
                        <a href="{{ route('informeLista') }}" class="btn btn-lg btn-default"><span
                                    class="fa fa-mail-reply"></span> Regresar</a>
                        <button type="submit" class="btn btn-lg btn-success pull-right" style="margin-left: 10px"><span class="fa fa-search" ></span> Consultar </button>
                        <a href='javascript:window.print(); void 0;' class="btn btn-lg btn-success pull-right" style="margin-left: 10px"><span class="fa fa-print"></span> Imprimir</a>
                        <button type="button" class="btn btn-lg btn-success pull-right" style="margin-left: 10px" id="exportar-excel-id"><span class="fa fa-file-excel-o" ></span> Exportar Excel </button>
                        {{--<a href='javascript:window.print(); void 0;'  style="margin-left: 10px"><span class="fa fa-file-excel-o"></span> Exportar Excel</a>--}}
                    </div>
                </form>
            </div><!-- /.box -->

        </div>
    </div>


    <section class="invoice">
        <div class="row">
            {{--Encabezado--}}
            <div class="col-xs-12">
                <h2 class="page-header">
                    <i class="fa fa-globe"></i> LGL S.A. de C.V.
                    <small class="pull-right">Generado: {{\Carbon\Carbon::now()->format('d/m/Y -- h:m:s A')}}</small>
                </h2>
                <p class="lead">Informe de ingresos diarios por ventas del dia: {{ $extra['dia']->format('d/m/Y') }}</p>
                Generado por: {{Auth::user()->nombre}} {{Auth::user()->apellido}} <br>
            </div>


                {{-- Tabla de productos --}}
                <div class="col-xs-12" style="padding-top: 20px">
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-condensed" id="tblProductos">
                            <thead>
                            <tr>
                                <th style="width: 5%">#</th>
                                <th style="width: 10%">Tipo doc</th>
                                <th style="width: 5%">N° documento</th>
                                <th style="width: 5%">Fecha doc</th>
                                <th style="width: 30%">Cliente</th>
                                <th style="width: 15%">Vendedor</th>
                                <th style="width: 10%">Tipo pago</th>
                                <th style="width: 10%">Cantidad abonada</th>
                                <th style="width: 10%">Saldo pendiente</th>
                            </tr>
                            </thead>
                            <tbody>
                                @php( $i = 1 )
                                @if($ventas_contado->isNotEmpty())
                                    <tr>
                                        <td colspan="9" style="background-color: #e0e0e0;">Ventas del dia con abono</td>
                                    </tr>
                                    @foreach($ventas_contado as $abono)
                                        <tr>
                                            <td>
                                                {{ $i }}
                                            </td>
                                            <td>
                                                <span class="label label-default">{{ $abono->venta->tipo_documento->codigo }}</span>
                                            </td>
                                            <td>
                                                {{ $abono->venta->numero }}
                                            </td>
                                            <td>
                                                {{ $abono->venta->fecha->format('d/m/Y') }}
                                            </td>
                                            <td>
                                                {{ $abono->venta->orden_pedido->cliente->nombre }}
                                            </td>
                                            <td>
                                                {{ $abono->venta->vendedor->nombre }}
                                            </td>
                                            <td>
                                                @if($abono->forma_pago->codigo == 'EFECT')
                                                    <span class="label label-success">{{ $abono->forma_pago->nombre }}</span>
                                                @elseif($abono->forma_pago->codigo == 'CHEQU')
                                                    <span class="label label-default">{{ $abono->forma_pago->nombre }}</span>
                                                @elseif($abono->forma_pago->codigo == 'DEPOS')
                                                    <span class="label label-primary">{{ $abono->forma_pago->nombre }}</span>
                                                @elseif($abono->forma_pago->codigo == 'RETEN')
                                                    <span class="label label-info">{{ $abono->forma_pago->nombre }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                $ {{ number_format($abono->cantidad,2) }}
                                            </td>
                                            <td>
                                                $ {{ number_format($abono->venta->saldo,2) }}
                                            </td>
                                        </tr>
                                        @php( $i++ )
                                    @endforeach
                                @endif
                                @if($ventas_credito->isNotEmpty())
                                    <tr>
                                        <td colspan="9" style="background-color: #e0e0e0;">Ventas al crédito del dia</td>
                                    </tr>
                                    @foreach($ventas_credito as $venta)
                                        <tr>
                                            <td>
                                                {{ $i }}
                                            </td>
                                            <td>
                                                <span class="label label-default">{{ $venta->tipo_documento->codigo }}</span>
                                            </td>
                                            <td>
                                                {{ $venta->numero }}
                                            </td>
                                            <td>
                                                {{ $venta->fecha->format('d/m/Y') }}
                                            </td>
                                            <td>
                                                {{ $venta->cliente->nombre }}
                                            </td>
                                            <td>
                                                {{ $venta->vendedor->nombre }}
                                            </td>
                                            <td>
                                                --
                                            </td>
                                            <td>
                                                --
                                            </td>
                                            <td>
                                                $ {{ number_format($venta->saldo,2) }}
                                            </td>
                                        </tr>
                                        @php( $i++ )
                                    @endforeach
                                @endif
                                @if($cobros->isNotEmpty())
                                    <tr>
                                        <td colspan="9" style="background-color: #e0e0e0;">Cobros del dia</td>
                                    </tr>
                                    @foreach($cobros as $abono)
                                        <tr>
                                            <td>
                                                {{ $i }}
                                            </td>
                                            <td>
                                                <span class="label label-default">{{ $abono->venta->tipo_documento->codigo }}</span>
                                            </td>
                                            <td>
                                                {{ $abono->venta->numero }}
                                            </td>
                                            <td>
                                                {{ $abono->venta->fecha->format('d/m/Y') }}
                                            </td>
                                            <td>
                                                {{ $abono->venta->orden_pedido->cliente->nombre }}
                                            </td>
                                            <td>
                                                {{ $abono->venta->vendedor->nombre }}
                                            </td>
                                            <td>
                                                @if($abono->forma_pago->codigo == 'EFECT')
                                                    <span class="label label-success">{{ $abono->forma_pago->nombre }}</span>
                                                @elseif($abono->forma_pago->codigo == 'CHEQU')
                                                    <span class="label label-default">{{ $abono->forma_pago->nombre }}</span>
                                                @elseif($abono->forma_pago->codigo == 'DEPOS')
                                                    <span class="label label-primary">{{ $abono->forma_pago->nombre }}</span>
                                                @elseif($abono->forma_pago->codigo == 'RETEN')
                                                    <span class="label label-info">{{ $abono->forma_pago->nombre }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                $ {{ number_format($abono->cantidad,2) }}
                                            </td>
                                            <td>
                                                $ {{ number_format($abono->venta->saldo,2) }}
                                            </td>
                                        </tr>
                                        @php( $i++ )
                                    @endforeach
                                @endif
                                @if($ventas_anuladas->isEmpty() != true)
                                    <tr>
                                        <td colspan="9" style="background-color: #e0e0e0;">Documentos anulados</td>
                                    </tr>
                                    @foreach($ventas_anuladas as $venta)
                                        <tr>
                                            <td>
                                                {{ $i }}
                                            </td>
                                            <td>
                                                <span class="label label-default">{{ $venta->tipo_documento->codigo }}</span>
                                            </td>
                                            <td>
                                                {{ $venta->numero }}
                                            </td>
                                            <td>
                                                {{ $venta->fecha->format('d/m/Y') }}
                                            </td>
                                            <td>
                                                {{ $venta->orden_pedido->cliente->nombre }}
                                            </td>
                                            <td>
                                                {{ $venta->vendedor->nombre }}
                                            </td>
                                            <td>
                                                --
                                            </td>
                                            <td>
                                                --
                                            </td>
                                            <td>
                                                --
                                            </td>
                                        </tr>
                                        @php( $i++ )
                                    @endforeach
                                @endif
                            </tbody>
                            <tr>
                                <td colspan="7" style="text-align: right"><b>TOTAL EFECTIVO</b></td>
                                <td><b>$ {{ number_format($extra['abono_efectivo'],2) }}</b></td>
                                <td><b></b></td>
                            </tr>
                            <tr>
                                <td colspan="7" style="text-align: right"><b>TOTAL CHEQUE</b></td>
                                <td><b>$ {{ number_format($extra['abono_cheque'],2) }}</b></td>
                                <td><b></b></td>
                            </tr>
                            <tr>
                                <td colspan="7" style="text-align: right"><b>TOTAL RETENCIONES</b></td>
                                <td><b>$ {{ number_format($extra['abono_retencion'],2) }}</b></td>
                                <td><b></b></td>
                            </tr>
                            <tr>
                                <td colspan="7" style="text-align: right"><b>TOTALES</b></td>
                                <td><b>$ {{ number_format($extra['abono_total'],2) }}</b></td>
                                <td><b>$ {{ number_format($extra['documento_total'],2) }}</b></td>
                                {{--<td></td>--}}
                            </tr>
                        </table>
                    </div>
                </div>

        </div>

    </section>

@endsection

@section('JSExtras')
    {{--Daterange--}}
    <script src="{{asset('plugins/moment.min.js')}}"></script>
    <script src="{{asset('plugins/daterangepicker.js')}}"></script>
    <script>

        $(function () {

            $('#daterange-btn').daterangepicker(
                {
                    "opens": "left"
                },
                function (start, end) {
                    $('#fecha-inicio').val(start.format('YYYY-MM-DD'));
                    $('#fecha-fin').val(end.format('YYYY-MM-DD'));
                }
            );
        });

        $(document).ready(function () {
            $('#exportar-excel-id').click(exportarExcel);
        });

        function exportarExcel() {
            let fechas = $('#fechas-form-id').serialize();
            // window.open("/informe/abonosxls?" + fechas);
        }
    </script>
@endsection

