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
                <div class="box-header with-border">
                    <h3 class="box-title">Productos</h3>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="tablaDT" class="table table-hover">
                        <thead>
                        <tr>
                            <th style="width:15%">Código</th>
                            <th style="width:35%">Nombre (Alternativo)</th>
                            <th style="width:15%">Cantidad en bodega</th>
                            <th style="width:10%">Unidad medida</th>
                            <th style="width:15%">Stock</th>
                            <th style="width:10%">Acción</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($productos as $producto)
                            <tr>
                                <td>{{$producto->codigo}}</td>
                                @if($producto->nombre_alternativo != null)
                                    <td>{{$producto->nombre}} ({{$producto->nombre_alternativo}})</td>
                                @else
                                    <td>{{$producto->nombre}}</td>
                                @endif
                                <td>{{$producto->cantidad_existencia}}</td>
                                <td>{{$producto->unidad_medida->abreviatura}}</td>
                                @if($producto->porcentaje_stock >= 40)
                                    <td>
                                        <div class="progress progress-xs">
                                            <div class="progress-bar progress-bar-success"
                                                 style="width: {{ $producto->porcentaje_stock }}%"></div>
                                        </div>
                                    </td>
                                @elseif($producto->porcentaje_stock >= 20)
                                    <td>
                                        <div class="progress progress-xs">
                                            <div class="progress-bar progress-bar-yellow"
                                                 style="width: {{ $producto->porcentaje_stock }}%"></div>
                                        </div>
                                    </td>
                                @else
                                    <td>
                                        <div class="progress progress-xs">
                                            <div class="progress-bar progress-bar-danger"
                                                 style="width: {{ $producto->porcentaje_stock }}%"></div>
                                        </div>
                                    </td>
                                @endif
                                {{--<td>$ {{number_format($producto->precios->first()->precio,2)}}</td>--}}
                                <td align="center">
                                    {{--<button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalPrecio" data-objeto="{{ $producto->nombre }}"--}}
                                    {{--data-id="{{ $producto->id }}" data-precio="{{ $producto->precio }}" data-ruta="producto/precio">--}}
                                    {{--<span class="fa fa-dollar"></span>--}}
                                    {{--</button>--}}
                                    <a href="{{ route('productoPrecio', ['id' => $producto->id]) }}"
                                       class="btn btn-success"><span class="fa fa-dollar"></span></a>
                                    {{--<a href="{{ route('productoVer', ['id' => $producto->id]) }}"--}}
                                       {{--class="btn btn-info"><span class="fa fa-eye"></span></a>--}}
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
    {{--<script src="{{ asset('/js/modal-eliminar.js') }}"></script>--}}
    <!-- DataTables -->
    <script src="{{ asset('/plugins/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/plugins/dataTables.bootstrap.min.js') }}"></script>
    <script>
        $(function () {

            $('#modalEliminar').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var nombreObj = button.data('objeto'); // Extract info from data-* attributes
                var idObj = button.data('id');
                var ruta = '/producto/' + idObj;
                // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                var modal = $(this);
                // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
                modal.find('#mensaje02').text('Realmente desea desactivar: ' + nombreObj);
                modal.find('#myform').attr("action", ruta);
            });

            $("#tablaDT").DataTable(
                {
                    "order": [[1, "asc"]],
                    language: {
                        processing: "Procesando...",
                        search: "Buscar:",
                        lengthMenu: "Mostrar _MENU_ registros",
                        info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                        infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                        infoFiltered: "(filtrado de un total de _MAX_ registros)",
                        infoPostFix: "",
                        loadingRecords: "Cargando...",
                        zeroRecords: "No se encontraron resultados",
                        emptyTable: "Ningún dato disponible en esta tabla",
                        paginate: {
                            first: "Primero",
                            previous: "Anterior",
                            next: "Siguiente",
                            last: "Último"
                        },
                        aria: {
                            sortAscending: ": Activar para ordenar la columna de manera ascendente",
                            sortDescending: ": Activar para ordenar la columna de manera descendente"
                        }
                    }
                }
            );
        });

    </script>
@endsection