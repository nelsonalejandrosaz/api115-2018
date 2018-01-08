@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Productos
@endsection

@section('CSSExtras')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('/plugins/dataTables.bootstrap.css') }}">
@endsection

@section('contentheader_title')
    Productos
@endsection

@section('contentheader_description')
    -- Lista de productos existentes
@endsection

@section('main-content')

    @include('partials.alertas')
    @include('partials.modalEliminar')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header">
                    <h3 class="box-title">Productos</h3>
                    <a href="{{ route('productoNuevo') }}" class="btn btn-lg btn-primary pull-right"><span
                                class="fa fa-plus"></span> Nuevo</a>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="tablaDT" class="table table-hover">
                        <thead>
                        <tr>
                            <th style="width:10%">Código</th>
                            <th style="width:20%">Nombre</th>
                            <th style="width:15%">Unidad medida</th>
                            <th style="width:10%">Cantidad</th>
                            <th style="width:15%">Costo</th>
                            <th style="width:15%">Precio</th>
                            <th style="width:15%">Acción</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($productos as $producto)
                            <tr>
                                <td>{{$producto->codigo}}</td>
                                <td>{{$producto->nombre}}</td>
                                <td>{{$producto->unidadMedida->nombre}}</td>
                                <td>{{$producto->cantidadExistencia}}</td>
                                <td>$ {{number_format($producto->costo,2)}}</td>
                                <td>$ {{number_format($producto->precio,2)}}</td>
                                <td align="center">
                                    <a href="{{ route('productoVer', ['id' => $producto->id]) }}" class="btn btn-info"><span class="fa fa-eye"></span></a>
                                    <a href="{{ route('productoEditar', ['id' => $producto->id]) }}" class="btn btn-warning"><span class="fa fa-edit"></span></a>
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalEliminar" data-objeto="{{ $producto->nombre }}"
                                            data-id="{{ $producto->id }}" data-ruta="producto">
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
    <script src="{{ asset('/js/modal-eliminar.js') }}"></script>
    @include('comun.dataTablesJSes')
@endsection