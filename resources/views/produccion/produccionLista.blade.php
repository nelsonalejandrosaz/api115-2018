@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Producciones
@endsection

@section('CSSExtras')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('/plugins/dataTables.bootstrap.css') }}">
@endsection

@section('contentheader_title')
    Producciones
@endsection

@section('contentheader_description')
    -- Lista de producciones realizadas
@endsection

@section('main-content')

    @include('partials.alertas')

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header">
                    <h3 class="box-title">Lista de producciones</h3>
                    <a href="{{ route('produccionNuevo') }}" class="btn btn-lg btn-primary pull-right"><span class="fa fa-plus"></span> Nuevo</a>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="tablaDT" class="table table-hover">
                        <thead>
                        <tr>
                            <th style="width:10%">Número</th>
                            <th style="width:10%">Fecha realizada</th>
                            <th style="width:25%">Producto</th>
                            <th style="width:20%">Realizada por</th>
                            <th style="width:15%">Acción</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($producciones as $produccion)
                            <tr>
                                <td>{{$produccion->id}}</td>
                                <td>{{$produccion->fecha}}</td>
                                <td>{{$produccion->producto->nombre}}</td>
                                <td>{{$produccion->bodeguero->nombre}}</td>
                                <td align="center">
                                    @if($produccion->procesado == false)
                                        <a href="{{ route('produccionPrevia', ['id' => $produccion->id]) }}" class="btn btn-warning"><span class="fa fa-check"></span></a>
                                    @else
                                        <a href="{{ route('produccionVer', ['id' => $produccion->id]) }}" class="btn btn-info"><span class="fa fa-eye"></span></a>
                                    @endif
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
