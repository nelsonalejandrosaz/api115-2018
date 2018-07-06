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
                    <h4>Fechas</h4>
                    {{-- Fecha inicio --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label"><b>Fecha inicio</b></label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="date" class="form-control" name="fecha_inicio" id="fecha-inicio" value="{{ $datos['fecha_inicio']->format('Y-m-d') }}">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h4>Proveedor</h4>
                    {{-- Fecha inicio --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label"><b>Proveedor</b></label>
                        <div class="col-md-8">
                            <select class="form-control select2" style="width: 100%" name="proveedor_id">
                                <option disabled selected>Seleccione un proveedor</option>
                                @if("todos" == Request::input('proveedor_id'))
                                    <option selected value="todos">Todos</option>
                                @else
                                    <option value="todos">Todos</option>
                                @endif
                                @foreach($proveedores as $proveedor)
                                    @if($proveedor->id == Request::input('proveedor_id'))
                                        <option selected value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                                    @else
                                        <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                                    @endif
                                @endforeach
                            </select>
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
                                <input type="date" class="form-control" name="fecha_fin" id="fecha-fin" value="{{ $datos['fecha_fin']->format('Y-m-d') }}">
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
            </div>
        </form>
    </div><!-- /.box -->

    <section class="invoice">
        <div class="row">
            {{--Encabezado--}}
            <div class="col-xs-12">
                <h2 class="page-header">
                    <i class="fa fa-globe"></i> LGL S.A. de C.V. -- Informe de compras
                    <small class="pull-right">Generado: {{\Carbon\Carbon::now()->format('d/m/Y -- h:m:s A')}}</small>
                </h2>
                <p class="lead">Del dia: {{ $datos['fecha_inicio']->format('d/m/Y') }} al dia: {{ $datos['fecha_fin']->format('d/m/Y') }}</p>
                Generado por: {{Auth::user()->nombre}} {{Auth::user()->apellido}} <br>
            </div>

            {{-- Tabla de productos --}}
            <div class="col-xs-12" style="padding-top: 20px">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-striped table-condensed" id="tblProductos">
                        <thead>
                        <tr>
                            <th style="width: 20%">Fecha</th>
                            <th style="width: 20%">n° documento</th>
                            <th style="width: 20%">Total sin IVA</th>
                            <th style="width: 20%">IVA</th>
                            <th style="width: 20%">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($tabla_agrupada as $fila_agrupada)
                                <tr>
                                    <td colspan="6" style="background-color: #bdbdbd"><b>{{ $fila_agrupada['proveedor'] }}</b></td>
                                </tr>
                                @foreach($fila_agrupada['compras'] as $fila)
                                    <tr>
                                        <td>{{ $fila['fecha'] }}</td>
                                        <td>{{ $fila['numero'] }}</td>
                                        <td>$ {{ number_format($fila['total_sin_iva'],2) }}</td>
                                        <td>$ {{ number_format($fila['iva'],2) }}</td>
                                        <td>$ {{ number_format($fila['total'] ,2) }}</td>
                                    </tr>
                                @endforeach
                                <td colspan="2"><b>TOTAL PROVEEDOR</b></td>
                                <td><b>$ {{ number_format($fila_agrupada['compras']->sum('total_sin_iva'),2) }}</b></td>
                                <td><b>$ {{ number_format($fila_agrupada['compras']->sum('iva'),2) }}</b></td>
                                <td><b>$ {{ number_format($fila_agrupada['compras']->sum('total'),2) }}</b></td>
                            @endforeach
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

    </script>
@endsection

