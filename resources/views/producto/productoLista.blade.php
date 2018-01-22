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
                            <th style="width:10%">Cantidad</th>
                            <th style="width:15%">Unidad medida</th>
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
                                <td>{{$producto->cantidad_existencia}}</td>
                                <td>{{$producto->unidad_medida->nombre}}</td>
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
    <!-- DataTables -->
    <script src="{{ asset('/plugins/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/plugins/dataTables.bootstrap.min.js') }}"></script>
    <script>
        $(function () {
            $("#tablaDT").DataTable(
                {
                    "order": [[ 1, "asc" ]],
                    language: {
                        processing:     "Procesando...",
                        search:         "Buscar:",
                        lengthMenu:     "Mostrar _MENU_ registros",
                        info:           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                        infoEmpty:      "Mostrando registros del 0 al 0 de un total de 0 registros",
                        infoFiltered:   "(filtrado de un total de _MAX_ registros)",
                        infoPostFix:    "",
                        loadingRecords: "Cargando...",
                        zeroRecords:    "No se encontraron resultados",
                        emptyTable:     "Ningún dato disponible en esta tabla",
                        paginate: {
                            first:      "Primero",
                            previous:   "Anterior",
                            next:       "Siguiente",
                            last:       "Último"
                        },
                        aria: {
                            sortAscending:  ": Activar para ordenar la columna de manera ascendente",
                            sortDescending: ": Activar para ordenar la columna de manera descendente"
                        }
                    }
                }
            );
        });
    </script>
@endsection