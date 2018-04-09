@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Informe de movimientos por ajuste
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
@endsection

@section('contentheader_title')
    Informe de movimientos por ajuste
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
                                <input type="date" class="form-control" name="fecha_inicio" id="fecha-inicio"
                                       value="{{ $datos['fecha_inicio']->format('Y-m-d') }}">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4>Producto</h4>
                    {{-- Cliente --}}
                    <div class="form-group">
                        <label class="col-md-4  control-label"><b>Producto</b></label>
                        <div class="col-md-8 ">
                            <select class="form-control select2" style="width: 100%" name="producto_id" id="clienteID">
                                <option value="" selected disabled>Seleciona un producto</option>
                                @foreach($productos as $producto)
                                    @if($producto->id == Request::input('producto_id'))
                                        <option selected value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                                    @else
                                        <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
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
                                <input type="date" class="form-control" name="fecha_fin" id="fecha-fin"
                                       value="{{ $datos['fecha_fin']->format('Y-m-d') }}">
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
                <button type="button" class="btn btn-lg btn-success pull-right" style="margin-left: 10px"
                        id="opciones-buttom"><span class="fa fa-search"></span> Consultar
                </button>
                <a href='javascript:window.print(); void 0;' class="btn btn-lg btn-success pull-right"
                   style="margin-left: 10px"><span class="fa fa-print"></span> Imprimir</a>
                {{--<a href='{{route('cxcAntiguedadExcel')}}' class="btn btn-lg btn-success pull-right"><span--}}
                            {{--class="fa fa-file-excel-o"></span> Exportar Excel</a>--}}
            </div>
        </form>
    </div><!-- /.box -->

    <section class="invoice">
        <div class="row">
            {{--Encabezado--}}
            <div class="col-xs-12">
                <h2 class="page-header">
                    <i class="fa fa-globe"></i> LGL S.A. de C.V. -- Informe de movimientos por ajuste
                    <small class="pull-right">Generado: {{\Carbon\Carbon::now()->format('d/m/Y -- h:m:s A')}}</small>
                </h2>
                <p class="lead">Del dia: {{ $datos['fecha_inicio']->format('d/m/Y') }} al
                    dia: {{ $datos['fecha_fin']->format('d/m/Y') }}</p>
                Producto: {{ $datos['nombre_producto'] }}<br>
                Generado por: {{Auth::user()->nombre}} {{Auth::user()->apellido}} <br>
            </div>

            {{-- Tabla de productos --}}
            <div class="col-xs-12" style="padding-top: 20px">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-striped table-condensed" id="tblProductos">
                        <thead>
                        <tr>
                            <th style="width: 10%">Fecha</th>
                            <th style="width: 45%">Tipo ajuste</th>
                            <th style="width: 15%">Cantidad</th>
                            <th style="width: 15%">Costo unitario</th>
                            <th style="width: 15%">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="5"><b>ENTRADAS</b></td>
                        </tr>
                        @foreach($tabla->where('tipo_movimiento','=','ENTRADA') as $fila)
                            <tr>
                                <td>{{ $fila['fecha'] }}</td>
                                <td>{{ $fila['tipo_ajuste'] }}</td>
                                <td>{{ $fila['cantidad'] }} Kgs</td>
                                <td>$ {{ number_format($fila['costo_unitario'],2) }}</td>
                                <td>$ {{ number_format($fila['costo_total'] ,2) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="2"><b>TOTAL ENTRADAS</b></td>
                            <td><b>$ {{ number_format($tabla->where('tipo_movimiento','=','ENTRADA')->sum('cantidad'),2) }}</b></td>
                            <td></td>
                            <td><b>$ {{ number_format($tabla->where('tipo_movimiento','=','ENTRADA')->sum('costo_total'),2) }}</b></td>
                        </tr>
                        <tr>
                            <td colspan="5"><b>SALIDAS</b></td>
                        </tr>
                        @foreach($tabla->where('tipo_movimiento','=','SALIDA') as $fila)
                            <tr>
                                <td>{{ $fila['fecha'] }}</td>
                                <td>{{ $fila['tipo_ajuste'] }}</td>
                                <td>{{ $fila['cantidad'] }} Kgs</td>
                                <td>$ {{ number_format($fila['costo_unitario'],2) }}</td>
                                <td>$ {{ number_format($fila['costo_total'] ,2) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="2"><b>TOTAL SALIDAS</b></td>
                            <td><b>$ {{ number_format($tabla->where('tipo_movimiento','=','SALIDA')->sum('cantidad'),2) }}</b></td>
                            <td></td>
                            <td><b>$ {{ number_format($tabla->where('tipo_movimiento','=','SALIDA')->sum('costo_total'),2) }}</b></td>
                        </tr>
                        </tbody>
                        {{--<td><b>TOTALES</b></td>--}}
                        {{--<td colspan="2"><b>{{ $tabla->sum('cantidad') }} Kgs</b></td>--}}
                        {{--<td><b>$ {{ number_format($tabla->sum('costo_total'),2) }}</b></td>--}}
                    </table>
                </div>
            </div>
        </div>

    </section>

@endsection

@section('JSExtras')
    @include('comun.select2Jses')
    <script>
        $(document).ready(Principal);

        function Principal() {
            // $('#exportar-excel-id').click(exportarExcel);
            $('#opciones-buttom').click(CambiarFecha);
        }

        function CambiarFecha() {
            toastr.info('Voy en cambiar fechas');
            let fecha_form = $('#opciones-form');
            let fechas_str = fecha_form.serialize();
            let path = window.location.pathname;
            let uri = path + "?" + fechas_str;
            toastr.info("Consultando fecha selecionada", "Excelente!!");
            window.location.href = uri;
        }

    </script>
@endsection

