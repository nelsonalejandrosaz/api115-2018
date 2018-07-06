@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Inventario general
@endsection

@section('CSSExtras')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('/plugins/dataTables.bootstrap.css') }}">
    {{-- Daterange --}}
    <link rel="stylesheet" href="{{asset('/plugins/daterangepicker.css')}}">
@endsection

@section('contentheader_title')
    Inventario general
@endsection

@section('contentheader_description')
    -- Productos existentes en bodega
@endsection

@section('main-content')

    @include('partials.alertas')
    @include('partials.modalFecha')

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header">
                    <h3 class="box-title">Lista de productos</h3>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                        <table id="tablaDT" class="table table-hover">
                        <thead>
                        <tr>
                            <th style="width: 10%">Código</th>
                            <th style="width: 40%">Nombre (Nombre alternativo)</th>
                            <th style="width: 10%">Cantidad Existencia</th>
                            <th style="width: 5%">Unidad Medida</th>
                            <th style="width: 25%">Stock</th>
                            <th style="width: 10%">Acción</th>
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
                                <td>{{number_format($producto->cantidad_existencia,3)}}</td>
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
                                <td align="center">
                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalFecha" data-objeto="{{ $producto->nombre }}"
                                            data-id="{{ $producto->id }}" data-ruta="inventario">
                                        <span class="fa fa-clock-o"></span>
                                    </button>
                                        <a href="{{ route('kardexProducto', ['id' => $producto->id]) }}"
                                           class="btn btn-primary"><span class="fa fa-th"></span></a>
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
    {{--Daterange--}}
    <script src="{{asset('plugins/moment.min.js')}}"></script>
    <script src="{{asset('plugins/daterangepicker.js')}}"></script>

    <script>

        $(function () {

            $('#daterange-btn').daterangepicker(
                {
                    ranges   : {
                        'Hoy'       : [moment(), moment()],
                        'Ayer'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Últimos 7 Días' : [moment().subtract(6, 'days'), moment()],
                        'Últimos 30 Días': [moment().subtract(29, 'days'), moment()],
                        'Este mes'  : [moment().startOf('month'), moment().endOf('month')],
                        'Mes pasado'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                    startDate: moment().subtract(29, 'days'),
                    endDate  : moment(),
                    "opens": "center"
                },
                function (start, end) {
//                    $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                    $('#fecha-inicio').val(start.format('YYYY-MM-DD'));
                    $('#fecha-fin').val(end.format('YYYY-MM-DD'));
                }
            );

            $('#modalFecha').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var nombreObj = button.data('objeto'); // Extract info from data-* attributes
                var idObj = button.data('id');
                var ruta = button.data('ruta');
                // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                var modal = $(this);
                // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
                modal.find('#consultarFecha').attr("action", "/" + ruta +"/" + idObj);
                var botonEnviar = modal.find('#btnEnviar');
                console.log(botonEnviar);
                botonEnviar.click(function () {
                    $('#consultarFecha').submit();
                });
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
