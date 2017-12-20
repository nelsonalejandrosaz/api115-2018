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
                    <a href="{{route('ajusteNuevo')}}" class="btn btn-lg btn-primary pull-right"><span class="fa fa-plus"></span> Nuevo</a>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="tablaDT" class="table table-hover">
                        <thead>
                        <tr>
                            <th style="width:10%">ID</th>
                            <th style="width:15%">Fecha ingreso</th>
                            <th style="width:35%">Detalle</th>
                            <th style="width:20%">Tipo ajuste</th>
                            <th style="width:20%">Acción</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($ajustes as $ajuste)
                            <tr>
                                <td>{{$ajuste->id}}</td>
                                <td>{{ \Carbon\Carbon::parse($ajuste->fechaIngreso)->format('d/m/Y')}}</td>
                                <td>{{$ajuste->detalle}}</td>
                                <td>{{$ajuste->tipoAjuste->nombre}}</td>
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
