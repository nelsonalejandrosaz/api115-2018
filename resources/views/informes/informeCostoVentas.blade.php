@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Informe de compras
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
@endsection

@section('contentheader_title')
    Informe de compras
@endsection

@section('contentheader_description')

@endsection

@section('main-content')

    @include('partials.alertas')

    <div class="box box-default box-solid no-print">
        <div class="box-header with-border">
            <h3 class="box-title">Opciones de informe</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
        </div><!-- /.box-header -->

        <!-- form start -->
        <form class="form-horizontal" id="opciones-form">
            <div class="box-body">

                <div class="col-md-6 col-sm-12">
                    <h4>Mes</h4>
                    {{-- Fecha inicio --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label"><b>Mes</b></label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="month" class="form-control" name="mes" id="fecha-inicio" value="{{ $datos['mes']->format('Y-m') }}">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('informeLista') }}" class="btn btn-lg btn-default"><span class="fa fa-mail-reply"></span> Regresar</a>
                <button type="button" class="btn btn-lg btn-success pull-right" style="margin-left: 10px" id="opciones-buttom"><span class="fa fa-search" ></span> Consultar </button>
                <a href='javascript:window.print(); void 0;' class="btn btn-lg btn-success pull-right" style="margin-left: 10px"><span class="fa fa-print"></span> Imprimir</a>
                <button type="button" class="btn btn-lg btn-success pull-right" id="excel-buttom"><span class="fa fa-file-excel-o"></span> Exportar Excel</button>
                <button type="button" class="btn btn-lg btn-success pull-right" id="sac-buttom" style="margin-right: 10px"><span class="fa fa-file-archive-o"></span> Exportar SAC</button>
            </div>
        </form>
    </div><!-- /.box -->

    <section class="invoice">
        <div class="row">
            {{--Encabezado--}}
            <div class="col-xs-12">
                <h2 class="page-header">
                    <i class="fa fa-globe"></i> LGL S.A. de C.V. -- Informe de costo de ventas
                    <small class="pull-right">Generado: {{\Carbon\Carbon::now()->format('d/m/Y -- h:m:s A')}}</small>
                </h2>
                <p class="lead">Del mes: {{ $datos['mes']->format('m/Y') }}</p>
                Generado por: {{Auth::user()->nombre}} {{Auth::user()->apellido}} <br>
            </div>

            {{-- Tabla de productos --}}
            <div class="col-xs-12" style="padding-top: 20px">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-striped table-condensed" id="tblProductos">
                        <thead>
                        <tr>
                            <th style="width: 20%">Codigo</th>
                            <th style="width: 40%">Producto</th>
                            <th style="width: 20%">Cantidad vendida</th>
                            <th style="width: 20%">Costo de venta</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tabla as $fila)
                            <tr>
                                <td>{{ $fila['codigo'] }}</td>
                                <td>{{ $fila['producto'] }}</td>
                                <td>{{ number_format($fila['cantidad_venta'],2) }} Kgs</td>
                                <td>$ {{ number_format($fila['costo_vendido'],2) }}</td>
                            </tr>
                        @endforeach
                            <tr>
                                <td colspan="3"><b>TOTAL</b></td>
                                <td><b>$ {{number_format($tabla->sum('costo_vendido'),2)}}</b></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </section>

@endsection

@section('JSExtras')
    @include('comun.select2Jses')
    {{-- Funcion para cargar mas filas de productos --}}
    <script>
        $(document).ready(Principal);

        function Principal() {
            $('#opciones-buttom').click(CambiarFecha);
            $('#excel-buttom').click(ExportarExcel);
            $('#sac-buttom').click(ExportarSAC);
        }

        function CambiarFecha() {
            let fecha_form = $('#opciones-form');
            let fechas_str = fecha_form.serialize();
            let path = window.location.pathname;
            let uri = path + "?" + fechas_str;
            toastr.info("Consultando fecha selecionada","Excelente!!");
            window.location.href = uri;
        }

        function ExportarExcel() {
            let fecha_form = $('#opciones-form');
            let fechas_str = fecha_form.serialize();
            let path = window.location.pathname;
            let uri = path + "/excel" + "?" + fechas_str;
            toastr.info("Generando el Excel en la fecha selecionada","Excelente!!");
            window.location.href = uri;
        }

        function ExportarSAC() {
            let fecha_form = $('#opciones-form');
            let fechas_str = fecha_form.serialize();
            let path = window.location.pathname;
            let uri = path + "/SAC" + "?" + fechas_str;
            toastr.info("Generando el archivo para SAC en la fecha selecionada","Excelente!!");
            window.location.href = uri;
        }

    </script>
@endsection

