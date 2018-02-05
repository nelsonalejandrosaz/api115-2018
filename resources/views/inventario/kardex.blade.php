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

    <div class="row">
        <div class="col-xs-12">

            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Productos</h3>
                    <a href="{{ route('inventarioLista') }}" class="btn btn-lg btn-default pull-right"><span
                                class="fa fa-mail-reply"></span> Regresar</a>
                </div><!-- /.box-header -->

                <!-- form start -->
                <form class="form-horizontal" action="{{ route('kardexProductoPost',['id' => $producto->id]) }}" method="POST">
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="col-md-6 col-sm-12">
                            <h4>Fechas mostradas</h4>
                            <br>

                            {{-- Fecha inicio --}}
                            <div class="form-group">
                                <label class="col-md-4 control-label"><b>Fecha inicio</b></label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input readonly type="date" class="form-control" name="fecha_inicio" id="fecha-inicio" value="{{ $mes['inicio'] }}">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Fecha fin --}}
                            <div class="form-group">
                                <label class="col-md-4 control-label"><b>Fecha fin</b></label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input readonly type="date" class="form-control" name="fecha_fin" id="fecha-fin" value="{{ $mes['fin'] }}">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Fecha --}}
                                <div class="form-group">
                                    <label class="col-md-4 control-label"><b>Seleccionar fechas</b></label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <button type="button" class="btn btn-default pull-right" id="daterange-btn">
                                        <span>
                                          <i class="fa fa-calendar"></i> Seleccionar fechas
                                        </span>
                                                <i class="fa fa-caret-down"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                        </div>
                        <div class="col-md-6 col-sm-12">
                            <h4>Control de inventario</h4>
                            <br>

                        </div>
                    </div><!-- /.box-body -->

                    <div class="box-footer">
                        <a href="{{ route('productoLista') }}" class="btn btn-lg btn-default"><span
                                    class="fa fa-close"></span> Cancelar</a>
                        <button type="submit" class="btn btn-lg btn-success pull-right"><span
                                    class="fa fa-floppy-o"></span> Guardar
                        </button>
                    </div>
                </form>
            </div><!-- /.box -->

            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="row">

                    </div>
                </div><!-- /.box-header -->

                <div class="row">
                    <div class="col-sm-6">
                    </div>
                </div>

                <div class="box-body table-responsive">
                    <table id="tablaKardex" class="table table-hover">
                        <thead>
                        <tr>
                            <th style="width: 10%" rowspan="2">Fecha</th>
                            <th style="width: 15%" rowspan="2">Detalle</th>
                            <th style="width: 25%" colspan="3">Entradas</th>
                            <th style="width: 25%" colspan="3">Salidas</th>
                            <th style="width: 25%" colspan="3">Existencias</th>
                        </tr>
                        <tr>
                            <th>Cantidad</th>
                            <th>Costo Unitario</th>
                            <th>Costo Total</th>
                            <th>Cantidad</th>
                            <th>Costo Unitario</th>
                            <th>Costo Total</th>
                            <th>Cantidad</th>
                            <th>Costo Unitario</th>
                            <th>Costo Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($movimientos as $movimiento)
                            <tr class="filasKardex">
                                <td>{{ \Carbon\Carbon::parse($movimiento->fecha)->format('d/m/Y')}}</td>
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


                                {{--@if($movimiento->tipoMovimiento->codigo == "ENTRADA")--}}
                                {{--<td><a href="{{route('compraVer',['id' => $movimiento->entrada->compra->id])}}">{{$movimiento->detalle}}</a></td>--}}
                                {{--@elseif($movimiento->tipoMovimiento->codigo == "SALIDA")--}}
                                {{--<td><a href="{{route('ordenPedidoVer',['id' => $movimiento->salida->ordenPedido->id])}}">{{$movimiento->detalle}}</a></td>--}}
                                {{--@elseif($movimiento->tipoMovimiento->codigo == "AJSTENT" || $movimiento->tipoMovimiento->codigo == "AJSTSAL")--}}
                                {{--<td><a href="{{route('ajusteVer',['id' => $movimiento->ajuste->id])}}">{{$movimiento->detalle}}</a></td>--}}
                                {{--@else--}}
                                {{--<td><a href="">{{$movimiento->detalle}}</a></td>--}}
                                {{--@endif--}}
                                @if($movimiento->tipo_movimiento->codigo == "ENTC" || $movimiento->tipo_movimiento->codigo == "ENTP")
                                    <td class="entradaCSS">{{$movimiento->cantidad}}</td>
                                    <td class="entradaCSS">${{ number_format($movimiento->costo_unitario,3) }}</td>
                                    <td class="entradaCSS">${{ number_format($movimiento->costo_total,2) }}</td>
                                    <td class="salidaCSS"> --</td>
                                    <td class="salidaCSS"> --</td>
                                    <td class="salidaCSS"> --</td>
                                @elseif($movimiento->tipo_movimiento->codigo == "SALO" || $movimiento->tipo_movimiento->codigo == "SALP")
                                    <td class="entradaCSS"> --</td>
                                    <td class="entradaCSS"> --</td>
                                    <td class="entradaCSS"> --</td>
                                    <td class="salidaCSS">{{$movimiento->cantidad}}</td>
                                    <td class="salidaCSS">${{ number_format($movimiento->costo_unitario,3) }}</td>
                                    <td class="salidaCSS">${{ number_format($movimiento->costo_total,2) }}</td>
                                @elseif($movimiento->tipo_movimiento->codigo == "AJSE")
                                    <td class="entradaCSS">{{$movimiento->cantidad}}</td>
                                    <td class="entradaCSS">${{ number_format($movimiento->costo_unitario,3) }}</td>
                                    <td class="entradaCSS">${{ number_format($movimiento->costo_total,2) }}</td>
                                    <td class="salidaCSS"> --</td>
                                    <td class="salidaCSS"> --</td>
                                    <td class="salidaCSS"> --</td>
                                @elseif($movimiento->tipo_movimiento->codigo == "AJSS")
                                    <td class="entradaCSS"> --</td>
                                    <td class="entradaCSS"> --</td>
                                    <td class="entradaCSS"> --</td>
                                    <td class="salidaCSS">{{$movimiento->cantidad}}</td>
                                    <td class="salidaCSS">${{ number_format($movimiento->costo_unitario,3) }}</td>
                                    <td class="salidaCSS">${{ number_format($movimiento->costo_total,2) }}</td>
                                @else
                                    <td class="entradaCSS"> --</td>
                                    <td class="entradaCSS"> --</td>
                                    <td class="entradaCSS"> --</td>
                                    <td class="salidaCSS"> --</td>
                                    <td class="salidaCSS"> --</td>
                                    <td class="salidaCSS"> --</td>
                                @endif
                                <td class="existenciaCSS">{{$movimiento->cantidad_existencia}}</td>
                                <td class="existenciaCSS">
                                    ${{ number_format($movimiento->costo_unitario_existencia,3) }}</td>
                                <td class="existenciaCSS">
                                    ${{ number_format($movimiento->costo_total_existencia,2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>

@endsection

@section('JSExtras')
    {{--Daterange--}}
    <script src="{{asset('plugins/moment.min.js')}}"></script>
    <script src="{{asset('plugins/daterangepicker.js')}}"></script>
    <script>

        $(function () {

            $('#daterange-btn').daterangepicker(
                {
                    ranges   : {
                        'Hoy'       : [moment(), moment()],
                        'Ayer'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Últimos 7 Días' : [moment().subtract(6, 'days'), moment()],
                        'Últimos 30 Días': [moment().subtract(29, 'days'), moment()],
                        'Este mes'  : [moment().startOf('month'), moment().endOf('month')],
                        'Mes pasado'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                    startDate: moment().subtract(29, 'days'),
                    endDate  : moment(),
                    "opens": "center"
                },
                function (start, end) {
//                    $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                    $('#fecha-inicio').val(start.format('YYYY-MM-DD'));
                    $('#fecha-fin').val(end.format('YYYY-MM-DD'));
                }
            );
        });
    </script>
@endsection
