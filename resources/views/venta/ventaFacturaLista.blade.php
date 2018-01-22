@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Ventas realizadas
@endsection

@section('CSSExtras')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('/plugins/dataTables.bootstrap.css') }}">
@endsection

@section('contentheader_title')
    Ventas realizadas
@endsection

@section('contentheader_description')
    -- Lista de ventas realizadas
@endsection

@section('main-content')

    @include('partials.alertas')

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header">
                    <h3 class="box-title">Lista de ventas</h3>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="tablaDT" class="table table-hover">
                        <thead>
                        <tr>
                            <th style="width:10%">Número</th>
                            <th style="width:10%">Fecha emisión</th>
                            <th style="width:25%">Cliente</th>
                            <th style="width:15%">Vendedor</th>
                            <th style="width:15%">Condición de pago</th>
                            <th style="width:10%">Saldo</th>
                            <th style="width:15%">Acción</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($ventas as $venta)
                            <tr>
                                <td>{{$venta->numero}}</td>
                                <td>{{$venta->fecha->format('d/m/Y')}}</td>
                                <td>{{$venta->orden_pedido->cliente->nombre}}</td>
                                <td>{{$venta->orden_pedido->vendedor->nombre}}</td>
                                <td>{{$venta->orden_pedido->condicion_pago->nombre}}</td>
                                <td>$ {{number_format($venta->saldo,2)}}</td>
                                <td align="center">
                                    <a href="{{ route('abonoNuevo', ['id' => $venta->id]) }}"
                                       class="btn btn-success"><span class="fa fa-credit-card"></span></a>
                                    <a href="{{ route('ventaVerFactura', ['id' => $venta->id]) }}"
                                       class="btn btn-info"><span class="fa fa-eye"></span></a>
                                </td>
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
    @include('comun.dataTablesJSes')
@endsection