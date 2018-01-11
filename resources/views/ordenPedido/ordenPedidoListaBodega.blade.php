@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Ordenes de pedidos
@endsection

@section('CSSExtras')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('/plugins/dataTables.bootstrap.css') }}">
@endsection

@section('contentheader_title')
    Ordenes de pedidos
@endsection

@section('contentheader_description')
    -- Lista de ordenes de pedidos en proceso
@endsection

@section('main-content')

    @include('partials.alertas')

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header">
                    <h3 class="box-title">Lista de ordenes en proceso</h3>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="tablaDT" class="table table-hover">
                        <thead>
                        <tr>
                            <th style="width:10%">Número</th>
                            <th style="width:10%">Fecha ingreso</th>
                            <th style="width:10%">Fecha entrega</th>
                            <th style="width:25%">Cliente</th>
                            <th style="width:20%">Vendedor</th>
                            <th style="width:10%">Estado</th>
                            <th style="width:15%">Acción</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($ordenesPedidos as $ordenPedido)
                            <tr>
                                <td>{{$ordenPedido->numero}}</td>
                                <td>{{$ordenPedido->fechaIngreso->format('d/m/Y')}}</td>
                                @if($ordenPedido->fechaEntrega != null)
                                    <td>{{$ordenPedido->fechaEntrega->format('d/m/Y')}}</td>
                                @else
                                    <td>Sin fecha definida</td>
                                @endif
                                <td>{{$ordenPedido->cliente->nombre}}</td>
                                <td>{{$ordenPedido->vendedor->nombre}}</td>
                                <td>
                                    @if($ordenPedido->procesado)
                                        Procesado
                                    @else
                                        En proceso
                                    @endif
                                </td>
                                <td align="center">
                                    <a href="{{ route('ordenPedidoVerBodega', ['id' => $ordenPedido->id]) }}"
                                       class="btn btn-success"><span class="fa fa-check-square-o"></span></a>
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
