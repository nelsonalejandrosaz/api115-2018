@extends('adminlte::layouts.app')

@section('htmlheader_title')
    {{-- {{ trans('message.tituloProveedorNuevo') }} --}}
    Informe de facturación
@endsection

@section('CSSx')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
    {{-- DataPicker --}}
    <link rel="stylesheet" href="{{asset('/css/datapicker/bootstrap-datepicker3.css')}}">
@endsection

@section('contentheader_title')
    {{-- {{ trans('message.tituloProveedorNuevo') }} --}}
    Informe de facturación
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
        <form class="form-horizontal" action="{{ route('facturacionInformeFechaPost') }}" method="POST" id="fechas-form-id">
            {{ csrf_field() }}
            <div class="box-body">

                <div class="col-md-6 col-sm-12">

                    <h4>Fechas consultadas</h4>

                    {{-- Fecha inicio --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label"><b>Fecha inicio</b></label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="date" class="form-control" name="fecha_inicio" id="fecha-inicio" value="{{ $extra['dia_inicio']->format('Y-m-d') }}">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-md-6 col-sm-12">

                    <h4><br></h4>

                    {{-- Fecha fin --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label"><b>Fecha fin</b></label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="date" class="form-control" name="fecha_fin" id="fecha-fin" value="{{ $extra['dia_fin']->format('Y-m-d') }}">
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

    <section class="invoice">
        <div class="row">
            {{--Encabezado--}}
            <div class="col-xs-12">
                <h2 class="page-header">
                    <i class="fa fa-globe"></i> LGL S.A. de C.V.
                    <small class="pull-right">Generado: {{\Carbon\Carbon::now()->format('d/m/Y -- h:m:s A')}}</small>
                </h2>
                <p class="lead">Informe de facturacion del dia: {{ $extra['dia_inicio']->format('d/m/Y') }} al {{ $extra['dia_fin']->format('d/m/Y') }}</p>
                Generado por: {{Auth::user()->nombre}} {{Auth::user()->apellido}} <br>
            </div>

            {{-- Tabla de productos --}}
            <div class="col-xs-12" style="padding-top: 20px">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-striped table-condensed" id="tblProductos">
                        <thead>
                        <tr>
                            <th style="width: 5%">#</th>
                            <th style="width: 10%">N° documento</th>
                            <th style="width: 25%">Cliente</th>
                            <th style="width: 15%">Vendedor</th>
                            <th style="width: 15%">Monto</th>
                            <th style="width: 15%">IVA</th>
                            <th style="width: 15%">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                            @php( $i = 1 )
                            <tr>
                                <td colspan="6">Facturas de consumidor final</td>
                            </tr>
                            @foreach($fcf_dia as $venta)
                            <tr>
                                <td>
                                    {{ $i }}
                                </td>
                                <td>
                                    {{ $venta->numero }}
                                </td>
                                <td>
                                    {{ $venta->cliente->nombre }}
                                </td>
                                <td>
                                    {{ $venta->vendedor->nombre }}
                                </td>
                                <td>
                                    $ {{ number_format($venta->monto,2) }}
                                </td>
                                <td>
                                    $ {{ number_format($venta->monto_iva,2) }}
                                </td>
                                <td>
                                    $ {{ number_format($venta->venta_total_con_impuestos,2) }}
                                </td>
                            </tr>
                            @php( $i++ )
                            @endforeach
                            <tr>
                                <td colspan="4">SUB TOTAL FAC</td>
                                <td><b>$ {{number_format($fcf_dia->sum('monto'),2)}}</b></td>
                                <td><b>$ {{number_format($fcf_dia->sum('monto_iva'),2)}}</b></td>
                                <td><b>$ {{number_format($fcf_dia->sum('venta_total_con_impuestos'),2)}}</b></td>
                            </tr>
                            <tr>
                                <td colspan="6">Comprobantes de crédito fiscal</td>
                            </tr>
                            @foreach($ccf_dia as $venta)
                                <tr>
                                    <td>
                                        {{ $i }}
                                    </td>
                                    <td>
                                        {{ $venta->numero }}
                                    </td>
                                    <td>
                                        {{ $venta->cliente->nombre }}
                                    </td>
                                    <td>
                                        {{ $venta->vendedor->nombre }}
                                    </td>
                                    <td>
                                        $ {{ number_format($venta->monto,2) }}
                                    </td>
                                    <td>
                                        $ {{ number_format($venta->monto_iva,2) }}
                                    </td>
                                    <td>
                                        $ {{ number_format($venta->venta_total_con_impuestos,2) }}
                                    </td>
                                </tr>
                                @php( $i++ )
                            @endforeach
                            <tr>
                                <td colspan="4">SUB TOTAL CCF</td>
                                <td><b>$ {{number_format($ccf_dia->sum('monto'),2)}}</b></td>
                                <td><b>$ {{number_format($ccf_dia->sum('monto_iva'),2)}}</b></td>
                                <td><b>$ {{number_format($ccf_dia->sum('venta_total_con_impuestos'),2)}}</b></td>
                            </tr>
                        </tbody>
                        <td colspan="4"><b>TOTALES</b></td>
                        <td><b>$ {{ number_format($extra['monto_dia'],2) }}</b></td>
                        <td><b>$ {{ number_format($extra['monto_iva_dia'],2) }}</b></td>
                        <td><b>$ {{ number_format($extra['monto_total_dia'],2) }}</b></td>
                    </table>
                </div>
            </div>
        </div>

    </section>

@endsection

@section('JSExtras')
    <script>
        $(document).ready(function () {
            $('#exportar-excel-id').click(exportarExcel);
        });

        function exportarExcel() {
            let fechas = $('#fechas-form-id').serialize();
            window.open("/informe/facturacionxls?" + fechas);
        }
    </script>
@endsection

