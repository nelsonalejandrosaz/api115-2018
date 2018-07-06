@extends('adminlte::layouts.app')

@section('htmlheader_title')
    {{-- {{ trans('message.tituloProveedorNuevo') }} --}}
    Informe de existencia de inventario
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
    {{-- Daterange --}}
    <link rel="stylesheet" href="{{asset('/plugins/daterangepicker.css')}}">
@endsection

@section('contentheader_title')
    {{-- {{ trans('message.tituloProveedorNuevo') }} --}}
    Informe de existencia de inventario
@endsection

@section('contentheader_description')

@endsection

@section('main-content')

    {{--Cuadro de herramientas--}}
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
                <a href='{{route('productoExistenciaInformeExcel')}}' class="btn btn-lg btn-success pull-right"><span class="fa fa-file-excel-o"></span> Exportar Excel</a>
            </div>
        </form>
    </div><!-- /.box -->
    {{--Fin cuadro de herramientas--}}

    <section class="invoice">
        <div class="row">
            {{--Encabezado--}}
            <div class="col-xs-12">
                <h2 class="page-header">
                    <i class="fa fa-globe"></i> LGL S.A. de C.V.
                    <small class="pull-right">Generado: {{\Carbon\Carbon::now()->format('d/m/Y -- h:m:s A')}}</small>
                </h2>
                <p class="lead">Informe de existencia de producto en bodega al dia: {{ $datos['dia']->format('d/m/Y') }}</p>
                Generado por: {{Auth::user()->nombre}} {{Auth::user()->apellido}} <br>
            </div>

            {{-- Tabla de productos --}}
            <div class="col-xs-12" style="padding-top: 20px">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-striped" id="tblProductos">
                        <thead>
                        <tr>
                            <th style="width: 5%">#</th>
                            <th style="width: 10%"></th>
                            <th style="width: 35%">Producto</th>
                            <th style="width: 10%">Existencia</th>
                            <th style="width: 10%">Unidad Medida</th>
                            <th style="width: 10%">Existencia min</th>
                            <th style="width: 10%">Existencia max</th>
                            <th style="width: 10%">Estado</th>
                        </tr>
                        </thead>
                        <tbody>
                            @php( $i = 1 )
                            <tr>
                                <td colspan="7"><b>Materias primas</b></td>
                            </tr>
                            @foreach($tabla->where('tipo_producto','=','MP') as $fila)
                            <tr>
                                <td>
                                    {{ $i }}
                                </td>
                                <td>
                                    {{ $fila['codigo'] }}
                                </td>
                                <td>
                                    {{ $fila['nombre_producto'] }}
                                </td>
                                <td>
                                    {{ number_format($fila['existencia'],3) }}
                                </td>
                                <td>
                                    {{ $fila['unidad_medida'] }}
                                </td>
                                <td>
                                    {{ $fila['existencia_min'] }}
                                </td>
                                <td>
                                    {{ $fila['existencia_max'] }}
                                </td>
                                @if($fila['estado'] == 1)
                                    <td>
                                        <span class="label label-success">Alto</span>
                                    </td>
                                @elseif($fila['estado'] == 2)
                                    <td>
                                        <span class="label label-warning">Medio</span>
                                    </td>
                                @else
                                    <td>
                                        <span class="label label-danger">Bajo</span>
                                    </td>
                                @endif
                            </tr>
                            @php( $i++ )
                            @endforeach
                            <tr>
                                <td colspan="7"><b>Productos terminados</b></td>
                            </tr>
                            @foreach($tabla->where('tipo_producto','=','PT') as $fila)
                                <tr>
                                    <td>
                                        {{ $i }}
                                    </td>
                                    <td>
                                        {{ $fila['codigo'] }}
                                    </td>
                                    <td>
                                        {{ $fila['nombre_producto'] }}
                                    </td>
                                    <td>
                                        {{ number_format($fila['existencia'],3) }}
                                    </td>
                                    <td>
                                        {{ $fila['unidad_medida'] }}
                                    </td>
                                    <td>
                                        {{ $fila['existencia_min'] }}
                                    </td>
                                    <td>
                                        {{ $fila['existencia_max'] }}
                                    </td>
                                    @if($fila['estado'] == 1)
                                        <td>
                                            <span class="label label-success">Alto</span>
                                        </td>
                                    @elseif($fila['estado'] == 2)
                                        <td>
                                            <span class="label label-warning">Medio</span>
                                        </td>
                                    @else
                                        <td>
                                            <span class="label label-danger">Bajo</span>
                                        </td>
                                    @endif
                                </tr>
                                @php( $i++ )
                            @endforeach
                            <tr>
                                <td colspan="7"><b>Reventa</b></td>
                            </tr>
                            @foreach($tabla->where('tipo_producto','=','RV') as $fila)
                                <tr>
                                    <td>
                                        {{ $i }}
                                    </td>
                                    <td>
                                        {{ $fila['codigo'] }}
                                    </td>
                                    <td>
                                        {{ $fila['nombre_producto'] }}
                                    </td>
                                    <td>
                                        {{ number_format($fila['existencia'],3) }}
                                    </td>
                                    <td>
                                        {{ $fila['unidad_medida'] }}
                                    </td>
                                    <td>
                                        {{ $fila['existencia_min'] }}
                                    </td>
                                    <td>
                                        {{ $fila['existencia_max'] }}
                                    </td>
                                    @if($fila['estado'] == 1)
                                        <td>
                                            <span class="label label-success">Alto</span>
                                        </td>
                                    @elseif($fila['estado'] == 2)
                                        <td>
                                            <span class="label label-warning">Medio</span>
                                        </td>
                                    @else
                                        <td>
                                            <span class="label label-danger">Bajo</span>
                                        </td>
                                    @endif
                                </tr>
                                @php( $i++ )
                            @endforeach
                            <tr>
                                <td colspan="7"><b>Productos materia prima y reventa</b></td>
                            </tr>
                            @foreach($tabla->where('tipo_producto','=','MR') as $fila)
                                <tr>
                                    <td>
                                        {{ $i }}
                                    </td>
                                    <td>
                                        {{ $fila['codigo'] }}
                                    </td>
                                    <td>
                                        {{ $fila['nombre_producto'] }}
                                    </td>
                                    <td>
                                        {{ number_format($fila['existencia'],3) }}
                                    </td>
                                    <td>
                                        {{ $fila['unidad_medida'] }}
                                    </td>
                                    <td>
                                        {{ $fila['existencia_min'] }}
                                    </td>
                                    <td>
                                        {{ $fila['existencia_max'] }}
                                    </td>
                                    @if($fila['estado'] == 1)
                                        <td>
                                            <span class="label label-success">Alto</span>
                                        </td>
                                    @elseif($fila['estado'] == 2)
                                        <td>
                                            <span class="label label-warning">Medio</span>
                                        </td>
                                    @else
                                        <td>
                                            <span class="label label-danger">Bajo</span>
                                        </td>
                                    @endif
                                </tr>
                                @php( $i++ )
                            @endforeach
                            <tr>
                                <td colspan="7"><b>Productos producto terminado y materia prima</b></td>
                            </tr>
                            @foreach($tabla->where('tipo_producto','=','PM') as $fila)
                                <tr>
                                    <td>
                                        {{ $i }}
                                    </td>
                                    <td>
                                        {{ $fila['codigo'] }}
                                    </td>
                                    <td>
                                        {{ $fila['nombre_producto'] }}
                                    </td>
                                    <td>
                                        {{ number_format($fila['existencia'],3) }}
                                    </td>
                                    <td>
                                        {{ $fila['unidad_medida'] }}
                                    </td>
                                    <td>
                                        {{ $fila['existencia_min'] }}
                                    </td>
                                    <td>
                                        {{ $fila['existencia_max'] }}
                                    </td>
                                    @if($fila['estado'] == 1)
                                        <td>
                                            <span class="label label-success">Alto</span>
                                        </td>
                                    @elseif($fila['estado'] == 2)
                                        <td>
                                            <span class="label label-warning">Medio</span>
                                        </td>
                                    @else
                                        <td>
                                            <span class="label label-danger">Bajo</span>
                                        </td>
                                    @endif
                                </tr>
                                @php( $i++ )
                            @endforeach
                        </tbody>
                        {{--<tfoot>--}}
                            {{--<tr>--}}
                                {{--<td colspan="5"><b>TOTAL EFECTIVO</b></td>--}}
                                {{--<td><b>$ {{ number_format($extra['abono_efectivo'],2) }}</b></td>--}}
                                {{--<td><b></b></td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                                {{--<td colspan="5"><b>TOTAL CHEQUE</b></td>--}}
                                {{--<td><b>$ {{ number_format($extra['abono_cheque'],2) }}</b></td>--}}
                                {{--<td><b></b></td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                                {{--<td colspan="5"><b>TOTAL RETENCIONES</b></td>--}}
                                {{--<td><b>$ {{ number_format($extra['abono_retencion'],2) }}</b></td>--}}
                                {{--<td><b></b></td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                                {{--<td colspan="5"><b>TOTALES</b></td>--}}
                                {{--<td><b>$ {{ number_format($extra['abono_total'],2) }}</b></td>--}}
                                {{--<td><b>$ {{ number_format($extra['documento_total'],2) }}</b></td>--}}
                            {{--</tr>--}}
                        {{--</tfoot>--}}
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
    </script>
@endsection

