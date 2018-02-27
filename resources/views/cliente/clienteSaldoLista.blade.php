@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Cuentas por cobrar
@endsection

@section('CSSExtras')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('/plugins/dataTables.bootstrap.css') }}">
@endsection

@section('contentheader_title')
    Cuentas por cobrar
@endsection

@section('contentheader_description')
    -- Lista de clientes con cuentas pendientes de pago
@endsection

@section('main-content')

    @include('partials.modalEliminar')

    @include('partials.alertas')

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                {{--<div class="box-header">--}}
                {{--</div><!-- /.box-header -->--}}
                <div class="box-body table-responsive">
                    <table id="tablaDT" class="table table-hover">
                        <thead>
                        <tr>
                            <th style="width:40%">Nombre empresa</th>
                            <th style="width:20%">Saldo</th>
                            <th style="width:20%">N° documentos pendientes</th>
                            <th style="width:20%">Acción</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($clientes as $cliente)
                            <tr>
                                <td>{{$cliente->nombre}}</td>
                                <td>$ {{number_format($cliente->saldo,2)}}</td>
                                 <td>{{$cliente->documentos_pendientes}}</td>
                                <td align="center">
                                    <a href="{{ route('cuentasPorCobrarVer', ['id' => $cliente->id]) }}" class="btn btn-info"><span class="fa fa-eye"></span></a>
                                    {{--<a href="{{ route('clienteEditar', ['id' => $cliente->id]) }}"--}}
                                       {{--class="btn btn-warning"><span class="fa fa-edit"></span></a>--}}
                                    {{--<button type="button" class="btn btn-danger" data-toggle="modal"--}}
                                            {{--data-target="#modalEliminar" data-objeto="{{ $cliente->nombre }}"--}}
                                            {{--data-id="{{ $cliente->id }}" data-ruta="cliente">--}}
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
    <script src="{{ asset('/js/modal-eliminar.js') }}"></script>
    @include('comun.dataTablesJSes')
@endsection