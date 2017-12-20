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
    -- Lista de ordenes de pedidos
@endsection

@section('main-content')

    @include('partials.alertas')

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Lista de facturas</h3>
                    <a href="{{ route('ordenPedidoNueva') }}" class="btn btn-lg btn-primary pull-right"><span class="fa fa-plus"></span> Nuevo</a>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="tablaDT" class="table table-hover">
                        <thead>
                        <tr>
                            <th style="width:5%">ID</th>
                            <th style="width:10%">Número</th>
                            <th style="width:10%">Fecha ingreso</th>
                            <th style="width:20%">Cliente</th>
                            <th style="width:10%">Estado</th>
                            <th style="width:10%">Acción</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($ordenesPedidos as $ordenPedido)
                            <tr>
                                <td>{{$ordenPedido->id}}</td>
                                <td>{{$ordenPedido->fechaIngreso}}</td>
                                <td>{{$ordenPedido->cliente->nombre}}</td>
                                <td>{{$ordenPedido->numero}}</td>
                                <td align="center">
                                    <a href="{{ route('ordenPedidoVer', ['id' => $ordenPedido->id]) }}" class="btn btn-info"><span class="fa fa-eye"></span></a>
                                    <a href="{{ route('ordenPedidoVer', ['id' => $ordenPedido->id]) }}" class="btn btn-warning"><span class="fa fa-edit"></span></a>
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalEliminar" data-ordenPedido="{{ $ordenPedido->id }}" data-id="{{ $ordenPedido->id }}">
                                        <span class="fa fa-trash"></span>
                                    </button>
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
