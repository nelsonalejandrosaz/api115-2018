@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Proveedores
@endsection

@section('CSSExtras')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('/plugins/dataTables.bootstrap.css') }}">
@endsection

@section('contentheader_title')
    Proveedores
@endsection

@section('contentheader_description')
    -- Lista de proveedores
@endsection

@section('main-content')

    @include('partials.modalEliminar')

    @include('partials.alertas')

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header">
                    <h3 class="box-title">Proveedores</h3>
                    <a href="{{ route('proveedorNuevo') }}" class="btn btn-lg btn-primary pull-right"><span
                                class="fa fa-plus"></span> Nuevo</a>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="tablaDT" class="table table-hover">
                        <thead>
                        <tr>
                            <th>Nombre empresa</th>
                            <th>Contacto</th>
                            <th>Teléfono principal</th>
                            <th>Teléfono secundario</th>
                            <th>Acción</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($proveedores as $proveedor)
                            <tr>
                                <td>{{$proveedor->nombre}}</td>
                                <td>{{$proveedor->nombreContacto}}</td>
                                <td>{{$proveedor->telefono1}}</td>
                                <td>{{$proveedor->telefono2}}</td>
                                {{-- <td>{{$proveedor->direccion}}</td> --}}
                                <td align="center">
                                    <a href="{{ route('proveedorVer', ['id' => $proveedor->id]) }}" class="btn btn-info"><span class="fa fa-eye"></span></a>
                                    <a href="{{ route('proveedorEditar', ['id' => $proveedor->id]) }}"
                                       class="btn btn-warning"><span class="fa fa-edit"></span></a>
                                    <button type="button" class="btn btn-danger" data-toggle="modal"
                                            data-target="#modalEliminar" data-objeto="{{ $proveedor->nombre }}"
                                            data-id="{{ $proveedor->id }}" data-ruta="proveedor">
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