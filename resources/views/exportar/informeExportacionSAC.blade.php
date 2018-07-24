@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Exportacion SAC
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
@endsection

@section('contentheader_title')
    Exportacion SAC
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
                                <input type="date" class="form-control" name="dia" value="{{$fecha->format('Y-m-d')}}">
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
                <button type="button" class="btn btn-lg btn-success pull-right" style="margin-left: 5px" id="opciones-buttom"><span class="fa fa-search" ></span> Consultar </button>
                <button type="button" class="btn btn-lg btn-success pull-right" id="sac-buttom" style="margin-right: 5px"><span class="fa fa-file-archive-o"></span> Exportar SAC</button>
            </div>
        </form>
    </div><!-- /.box -->

    <section class="invoice">
        <div class="row">
            {{--Encabezado--}}
            <div class="col-xs-12">
                <h2 class="page-header">
                    <i class="fa fa-globe"></i> LGL S.A. de C.V. -- Preliminar de exportacion a SAC
                    <small class="pull-right">Generado: {{\Carbon\Carbon::now()->format('d/m/Y -- h:m:s A')}}</small>
                </h2>
                <p class="lead">Fecha: </p>
                Generado por: {{Auth::user()->nombre}} {{Auth::user()->apellido}} <br>
            </div>

            {{-- Tabla de productos --}}
            <div class="col-xs-12" style="padding-top: 20px">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-striped table-condensed" id="tblProductos">
                        <thead>
                        <tr>
                            <th style="width: 20%">ID Cuenta</th>
                            <th style="width: 40%">Concepto</th>
                            <th style="width: 20%">Cargo</th>
                            <th style="width: 20%">Abono</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tabla as $fila)
                            <tr>
                                <td>{{$fila['id_cuenta']}}</td>
                                <td>{{$fila['concepto']}}</td>
                                <td>$ {{number_format($fila['cargo'],2)}}</td>
                                <td>$ {{number_format($fila['abono'],2)}}</td>
                            </tr>
                        @endforeach
                            <tr>
                                <td colspan="2"><b>TOTAL</b></td>
                                <td><b>$ {{number_format($tabla->sum('cargo'),2)}}</b></td>
                                <td><b>$ {{number_format($tabla->sum('abono'),2)}}</b></td>
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

