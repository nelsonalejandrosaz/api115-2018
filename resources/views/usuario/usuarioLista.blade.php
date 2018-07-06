@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Usuarios
@endsection

@section('CSSExtras')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('/plugins/dataTables.bootstrap.css') }}">
@endsection

@section('contentheader_title')
    Usuarios
@endsection

@section('contentheader_description')
    -- Lista de usuarios del sistema
@endsection

@section('main-content')

    @include('partials.modalEliminar')

    @include('partials.alertas')

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header">
                    <h3 class="box-title">Usuarios</h3>
                    <a href="{{ route('usuarioNuevo') }}" class="btn btn-lg btn-primary pull-right"><span
                                class="fa fa-plus"></span> Nuevo</a>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="tablaDT" class="table table-hover">
                        <thead>
                        <tr>
                            <th style="width:20%">Nombre empresa</th>
                            <th style="width:20%">Contacto</th>
                            <th style="width:20%">Teléfono principal</th>
                            <th style="width:20%">Teléfono secundario</th>
                            <th style="width:20%">Acción</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($usuarios as $usuario)
                            <tr>
                                <td>{{$usuario->nombre}}</td>
                                <td>{{$usuario->apellido}}</td>
                                <td>{{$usuario->username}}</td>
                                <td>{{$usuario->rol->nombre}}</td>
                                {{-- <td>{{$proveedor->direccion}}</td> --}}
                                <td align="center">
                                    <a href="{{ route('usuarioVer', ['id' => $usuario->id]) }}" class="btn btn-info"><span class="fa fa-eye"></span></a>
                                    <a href="{{ route('usuarioEditar', ['id' => $usuario->id]) }}"
                                       class="btn btn-warning"><span class="fa fa-edit"></span></a>
                                    {{--<button type="button" class="btn btn-danger" data-toggle="modal"--}}
                                            {{--data-target="#modalEliminar" data-objeto="{{ $usuario->nombre }}"--}}
                                            {{--data-id="{{ $usuario->id }}" data-ruta="cliente">--}}
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