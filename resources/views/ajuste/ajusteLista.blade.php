@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Historico de ajustes
@endsection

@section('CSSExtras')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('/plugins/dataTables.bootstrap.css') }}">
@endsection

@section('contentheader_title')
    Histórico de ajustes
@endsection

@section('contentheader_description')
    -- Ajustes realizados al inventario
@endsection

@section('main-content')

    @include('partials.alertas')

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header">
                    <h3 class="box-title">Lista de ajustes</h3>
                    <a href="{{route('ajusteNuevo')}}" class="btn btn-lg btn-primary pull-right"><span class="fa fa-plus"></span> Nuevo ajuste existencia</a>
                    <a href="{{route('ajusteCostoNuevo')}}" class="btn btn-lg btn-primary pull-right" style="margin-right: 5px"><span class="fa fa-plus"></span> Nuevo ajuste costo</a>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="tablaDT" class="table table-hover">
                        <thead>
                        <tr>
                            <th style="width:15%">Fecha ingreso</th>
                            <th style="width:15%">Producto</th>
                            <th style="width:25%">Detalle</th>
                            <th style="width:15%">Tipo ajuste</th>
                            <th style="width:15%">Realizado por</th>
                            <th style="width:15%">Acción</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($ajustes as $ajuste)
                            <tr>
                                <td>{{ $ajuste->fecha->format('d-m-Y')  }}</td>
                                <td>{{ $ajuste->movimiento->producto->nombre }}</td>
                                <td>{{$ajuste->detalle}}</td>
                                <td>{{$ajuste->tipo_ajuste->nombre}}</td>
                                <td>{{$ajuste->realizado->nombre}}</td>
                                <td align="center">
                                    <a href="{{ route('ajusteVer', ['id' => $ajuste->id]) }}" class="btn btn-info"><span class="fa fa-eye"></span></a>
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalEliminar" data-ajuste="{{ $ajuste->id }}" data-id="{{ $ajuste->id }}">
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
