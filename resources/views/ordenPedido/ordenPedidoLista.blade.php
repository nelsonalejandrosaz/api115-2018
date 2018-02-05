@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Ordenes de pedidos
@endsection

@section('CSSExtras')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('/plugins/dataTables.bootstrap.css') }}">
@endsection

@section('contentheader_title')
    Ordenes de pedidos
@endsection

@section('contentheader_description')
    -- Lista de ordenes de pedidos
@endsection

@section('main-content')

    @include('partials.alertas')
    @include('partials.modalEliminar')

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header">
                    <h3 class="box-title">Lista de facturas</h3>
                    <a href="{{ route('ordenPedidoNueva') }}" class="btn btn-lg btn-primary pull-right"><span
                                class="fa fa-plus"></span> Nuevo</a>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="tablaDT" class="table table-hover">
                        <thead>
                        <tr>
                            <th style="width:10%">Número</th>
                            <th style="width:10%">Fecha ingreso</th>
                            <th style="width:10%">Fecha entrega</th>
                            <th style="width:25%">Cliente</th>
                            <th style="width:20%">Vendedor</th>
                            <th style="width:10%">Estado</th>
                            <th style="width:15%">Acción</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($ordenesPedidos as $ordenPedido)
                            <tr>
                                <td>{{$ordenPedido->numero}}</td>
                                <td>{{$ordenPedido->fecha->format('d/m/Y')}}</td>
                                @if($ordenPedido->fecha_entrega != null)
                                    <td>{{$ordenPedido->fecha_entrega->format('d/m/Y')}}</td>
                                @else
                                    <td>Sin fecha definida</td>
                                @endif
                                <td>{{$ordenPedido->cliente->nombre}}</td>
                                <td>{{$ordenPedido->vendedor->nombre}}</td>
                                <td>
                                    @if($ordenPedido->estado_id == 1)
                                        En proceso
                                    @elseif($ordenPedido->estado_id == 2)
                                        Procesado
                                    @elseif($ordenPedido->estado_id == 3)
                                        Facturado
                                    @endif
                                </td>
                                <td align="center">
                                    <a href="{{ route('ordenPedidoVer', ['id' => $ordenPedido->id]) }}"
                                       class="btn btn-info"><span class="fa fa-eye"></span></a>
                                    @if($ordenPedido->estado_id == 1)
                                        <button type="button" class="btn btn-danger" data-toggle="modal"
                                                data-target="#modalEliminar" data-numero="{{ $ordenPedido->numero }}"
                                                data-id="{{ $ordenPedido->id }}">
                                            <span class="fa fa-trash"></span>
                                        </button>
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
    <!-- DataTables -->
    <script src="{{ asset('/plugins/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/plugins/dataTables.bootstrap.min.js') }}"></script>
    <script !src="">
        $(function () {
            $("#tablaDT").DataTable(
                {
                    order: [[1, "asc"]] ,
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

            $('#modalEliminar').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var numero_orden = button.data('numero'); // Extract info from data-* attributes
                var id_orden = button.data('id');
                var ruta = '/ordenPedido/' + id_orden;
                // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                var modal = $(this);
                // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
                modal.find('#mensaje01').text('Realmente desea desactivar:');
                modal.find('#mensaje02').text('Realmente desea desactivar: ' + numero_orden);
                modal.find('#myform').attr("action", ruta);
            });
        })
    </script>
@endsection
