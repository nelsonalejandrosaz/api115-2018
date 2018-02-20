@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Clientes ventas
@endsection

@section('CSSExtras')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('/plugins/dataTables.bootstrap.css') }}">
@endsection

@section('contentheader_title')
    Clientes ventas
@endsection

@section('contentheader_description')
    -- Lista de ventas realizadas a los clientes
@endsection

@section('main-content')

    @include('partials.modalEliminar')

    @include('partials.alertas')

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header with-border">
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="tablaDT" class="table table-hover">
                        <thead>
                        <tr>
                            <th style="width:35%">Nombre empresa</th>
                            <th style="width:15%">Total ventas</th>
                            <th style="width:15%">Total ventas pendientes</th>
                            <th style="width:15%">Saldo</th>
                            <th style="width:20%">Acción</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($clientes as $cliente)
                            <tr>
                                <td>{{$cliente->nombre}}</td>
                                <td>{{$cliente->numero_ventas}}</td>
                                <td>{{$cliente->numero_ventas_pendientes}}</td>
                                <td>$ {{number_format($cliente->saldo,2)}}</td>
                                {{-- <td>{{$proveedor->direccion}}</td> --}}
                                <td align="center">
                                    <a href="{{ route('clienteSaldoVer', ['id' => $cliente->id]) }}" class="btn bg-gray"><span class="fa fa-list"></span></a>
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
    <script src="{{ asset('/js/modal-eliminar.js') }}"></script>
    @include('comun.dataTablesJSes')
@endsection