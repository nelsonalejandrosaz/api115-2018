@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Kardex
@endsection

@section('CSSExtras')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('/plugins/dataTables.bootstrap.css') }}">
    {{-- Daterange --}}
    <link rel="stylesheet" href="{{asset('/plugins/daterangepicker.css')}}">
    <style type="text/css">
        .filasKardex:hover {
            filter: brightness(0.9);
        }

        .entradaCSS {
            background-color: #a5d6a7;
        }

        .salidaCSS {
            background-color: #ef9a9a;
        }

        .existenciaCSS {
            background-color: #80cbc4;
        }

        thead {
            background-color: #616161;
            color: white;
        }
    </style>
@endsection

@section('contentheader_title')
    Kardex del producto: {{$producto->nombre}}
@endsection

@section('contentheader_description')
    -- Entradas y salidas del producto
@endsection

@section('main-content')

    @include('partials.alertas')

    <div class="row no-print">
        <div class="col-xs-12">

            {{--Cuadro de herramientas--}}
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Fechas consultadas</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div><!-- /.box-header -->

                <!-- form start -->
                <form class="form-horizontal" action="{{ route('kardexProductoPost',['id' => $producto->id]) }}" method="POST">
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="col-md-6 col-sm-12">

                            {{-- Fecha inicio --}}
                            <div class="form-group">
                                <label class="col-md-4 control-label"><b>Fecha inicio</b></label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input type="date" class="form-control" name="fecha_inicio" id="fecha-inicio" value="{{ $mes['inicio'] }}">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-6 col-sm-12">

                            {{-- Fecha fin --}}
                            <div class="form-group">
                                <label class="col-md-4 control-label"><b>Fecha fin</b></label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input type="date" class="form-control" name="fecha_fin" id="fecha-fin" value="{{ $mes['fin'] }}">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div><!-- /.box-body -->

                    <div class="box-footer">
                        <a href="{{ route('inventarioLista') }}" class="btn btn-lg btn-default"><span
                                    class="fa fa-mail-reply"></span> Regresar</a>
                        <button type="submit" class="btn btn-lg btn-success pull-right"><span
                                    class="fa fa-search"></span> Consultar
                        </button>
                    </div>
                </form>
            </div>
            {{--Fin cuadro de herramientas--}}

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
                <p class="lead">{{ $producto->nombre }}</p>
                Unidad de medida: {{ $producto->unidad_medida->nombre }} <br>
                Metodo: Promedio Ponderado
            </div>

            {{-- Tabla de productos --}}
            <div class="col-xs-12" style="padding-top: 20px">
                <div class="box-body">
                    <table id="tablaKardex" class="table table-hover">
                        <thead>
                        <tr>
                            <th style="width: 10%" rowspan="2">Fecha</th>
                            <th style="width: 15%" rowspan="2">Detalle</th>
                            <th style="width: 25%">Entradas</th>
                            <th style="width: 25%">Salidas</th>
                            <th style="width: 25%">Existencias</th>
                        </tr>
                        <tr>
                            <th>Cantidad</th>
                            <th>Cantidad</th>
                            <th>Cantidad</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($movimientos as $movimiento)
                            <tr class="filasKardex">
                                <td>{{ \Carbon\Carbon::parse($movimiento->fecha_procesado)->format('d/m/Y')}}</td>
                                @if($movimiento->tipo_movimiento->codigo == "ENTC")
                                    <td>
                                        <a href="{{ route('compraVer',['id' => $movimiento->entrada->compra_id]) }}">{{$movimiento->detalle}}</a>
                                    </td>
                                @elseif($movimiento->tipo_movimiento->codigo == "ENTP")
                                    <td>
                                        <a href="{{ route('produccionVer',['id' => $movimiento->entrada->produccion_id]) }}">{{$movimiento->detalle}}</a>
                                    </td>
                                @elseif($movimiento->tipo_movimiento->codigo == "SALO")
                                    <td>
                                        <a href="{{ route('ordenPedidoVer',['id' => $movimiento->salida->orden_pedido_id]) }}">{{$movimiento->detalle}}</a>
                                    </td>
                                @elseif($movimiento->tipo_movimiento->codigo == "SALP")
                                    <td>
                                        <a href="{{ route('produccionVer',['id' => $movimiento->salida->produccion_id]) }}">{{$movimiento->detalle}}</a>
                                    </td>
                                @else
                                    <td>
                                        <a href="{{ route('ajusteVer',['id' => $movimiento->ajuste_id]) }}">{{$movimiento->detalle}}</a>
                                    </td>
                                @endif


                                @if($movimiento->tipo_movimiento->codigo == "ENTC" || $movimiento->tipo_movimiento->codigo == "ENTP")
                                    <td class="entradaCSS">{{ number_format($movimiento->cantidad,3) }} Kgs</td>
                                    <td class="salidaCSS"> --</td>
                                @elseif($movimiento->tipo_movimiento->codigo == "SALO" || $movimiento->tipo_movimiento->codigo == "SALP")
                                    <td class="entradaCSS"> --</td>
                                    <td class="salidaCSS">{{ number_format($movimiento->cantidad,3) }} Kgs</td>
                                @elseif($movimiento->tipo_movimiento->codigo == "AJSE")
                                    <td class="entradaCSS">{{ number_format($movimiento->cantidad,3) }} Kgs</td>
                                    <td class="salidaCSS"> --</td>
                                @elseif($movimiento->tipo_movimiento->codigo == "AJSS")
                                    <td class="entradaCSS"> --</td>
                                    <td class="salidaCSS">{{ number_format($movimiento->cantidad,3) }} Kgs</td>
                                @else
                                    <td class="entradaCSS"> --</td>
                                    <td class="salidaCSS"> --</td>
                                @endif
                                <td class="existenciaCSS">{{ number_format($movimiento->cantidad_existencia,3) }} Kgs</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>
                </div><!-- /.box-body -->

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
