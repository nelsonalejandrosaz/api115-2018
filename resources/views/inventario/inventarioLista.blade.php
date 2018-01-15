@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Inventario general
@endsection

@section('CSSExtras')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('/plugins/dataTables.bootstrap.css') }}">
@endsection

@section('contentheader_title')
    Inventario general
@endsection

@section('contentheader_description')
    -- Productos existentes en bodega
@endsection

@section('main-content')

    @include('partials.alertas')

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header">
                    <h3 class="box-title">Lista de productos</h3>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="tablaDT" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th style="width: 10%">Código</th>
                            <th style="width: 30%">Nombre</th>
                            <th style="width: 10%">Unidad Medida</th>
                            <th style="width: 10%">Cantidad Existencia</th>
                            <th style="width: 10%">Costo unitario</th>
                            <th style="width: 10%">Costo total</th>
                            <th style="width: 15%">Stock</th>
                            <th style="width: 5%">Acción</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($productos as $producto)
                            <tr>
                                <td>{{$producto->codigo}}</td>
                                <td>{{$producto->nombre}}</td>
                                <td>{{$producto->unidadMedida->abreviatura}}</td>
                                <td>{{$producto->cantidadExistencia}}</td>
                                <td>${{ number_format($producto->costo,2)}}</td>
                                <td>${{ number_format($producto->costoTotal,2)}}</td>
                                @if($producto->porcentajeStock >= 40)
                                    <td>
                                        <div class="progress progress-xs">
                                            <div class="progress-bar progress-bar-success" style="width: {{ $producto->porcentajeStock }}%"></div>
                                        </div>
                                    </td>
                                @elseif($producto->porcentajeStock >= 20)
                                    <td>
                                        <div class="progress progress-xs">
                                            <div class="progress-bar progress-bar-yellow" style="width: {{ $producto->porcentajeStock }}%"></div>
                                        </div>
                                    </td>
                                @else
                                    <td>
                                        <div class="progress progress-xs">
                                            <div class="progress-bar progress-bar-danger" style="width: {{ $producto->porcentajeStock }}%"></div>
                                        </div>
                                    </td>
                                @endif
                                <td align="center">
                                    <a href="{{ route('kardexProducto', ['id' => $producto->id]) }}" class="btn btn-primary"><span class="fa fa-th"></span></a>
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
