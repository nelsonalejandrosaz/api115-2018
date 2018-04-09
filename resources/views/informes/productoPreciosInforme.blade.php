@extends('adminlte::layouts.app')

@section('htmlheader_title')
    {{-- {{ trans('message.tituloProveedorNuevo') }} --}}
    Informe de precios de los productos
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
    {{-- Daterange --}}
    <link rel="stylesheet" href="{{asset('/plugins/daterangepicker.css')}}">
@endsection

@section('contentheader_title')
    {{-- {{ trans('message.tituloProveedorNuevo') }} --}}
    Informe de precios de los productos
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
                <a href='{{route('productoPreciosInformeExcel')}}' class="btn btn-lg btn-success pull-right"><span class="fa fa-file-excel-o"></span> Exportar Excel</a>
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
                <p class="lead">Informe de precios por productos al dia: {{ $extra['dia']->format('d/m/Y') }}</p>
                Generado por: {{Auth::user()->nombre}} {{Auth::user()->apellido}} <br>
            </div>

            {{-- Tabla de productos --}}
            <div class="col-xs-12" style="padding-top: 20px">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-bordered table-condensed" id="tblProductos">
                        <thead>
                        <tr>
                            <th colspan="7">Código -- Producto</th>
                        </tr>
                        <tr>
                            <th style="width: 5%">#</th>
                            <th style="width: 35%">Presentación</th>
                            <th style="width: 15%">Unidad Medida</th>
                            <th style="width: 15%">Precio</th>
                            <th style="width: 10%">Precio con IVA</th>
                            <th style="width: 10%">Equivalente Kg</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="background-color: #eeeeee" colspan="6">
                                    Productos terminados
                                </td>
                            </tr>
                            @foreach($productos['productos_pt'] as $producto)
                                <tr>
                                    <td colspan="6" style="background-color: #e0e0e0">{{ $producto->codigo }} -- {{ $producto->nombre }}</td>
                                </tr>
                                    @php( $i = 1 )
                                    @foreach($producto->precios as $precio)
                                        <tr>
                                            <td>
                                                {{ $i }}
                                            </td>
                                            <td>
                                                {{ $precio->presentacion }}
                                            </td>
                                            <td>
                                                {{ $precio->unidad_medida->abreviatura }}
                                            </td>
                                            <td>
                                                $ {{ number_format($precio->precio,2) }}
                                            </td>
                                            <td>
                                                $ {{ number_format(($precio->precio * 1.13),2) }}
                                            </td>
                                            <td>
                                                {{ number_format($precio->factor,4) }} Kg
                                            </td>
                                        </tr>
                                        @php( $i++ )
                                    @endforeach
                                @php( $i = 1 )
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

