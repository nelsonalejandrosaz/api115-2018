@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Ventas
@endsection

@section('CSSExtras')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('/plugins/dataTables.bootstrap.css') }}">
@endsection

@section('contentheader_title')
    Ventas
@endsection

@section('contentheader_description')
    -- Lista de ventas
@endsection

@section('main-content')

    @include('partials.alertas')

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header">
                    <h3 class="box-title">Lista de ordenes de pedido por facturar</h3>
                    <a href="{{ route('ordenPedidoNueva') }}" class="btn btn-lg btn-primary pull-right"><span
                                class="fa fa-plus"></span> Nuevo</a>
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
                                <td>{{$ordenPedido->fecha->format('d/m/Y')}}</td>
                                @if($ordenPedido->fecha_entrega != null)
                                    <td>{{$ordenPedido->fecha_entrega->format('d/m/Y')}}</td>
                                @else
                                    <td>Sin fecha especificada</td>
                                @endif
                                    <td>{{$ordenPedido->cliente->nombre}}</td>
                                <td>{{$ordenPedido->vendedor->nombre}}</td>
                                <td>
                                    @if($ordenPedido->estado_id == 2)
                                        No facturado
                                    @else
                                        Facturado
                                    @endif
                                </td>
                                <td align="center">
                                    <a href="{{ route('ventaNueva', ['id' => $ordenPedido->id]) }}"
                                       class="btn btn-success"><span class="fa fa-money"></span></a>
                                    {{--<button type="button" class="btn btn-danger" data-toggle="modal"--}}
                                            {{--data-target="#modalEliminar" data-ordenPedido="{{ $ordenPedido->id }}"--}}
                                            {{--data-id="{{ $ordenPedido->id }}">--}}
                                        {{--<span class="fa fa-trash"></span>--}}
                                    {{--</button>--}}
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