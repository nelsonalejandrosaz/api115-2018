@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Ventas realizadas
@endsection

@section('CSSExtras')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('/plugins/dataTables.bootstrap.css') }}">
@endsection

@section('contentheader_title')
    Ventas realizadas
@endsection

@section('contentheader_description')
    -- Lista de ventas realizadas
@endsection

@section('main-content')

    @include('partials.alertas')
    @include('partials.modalEliminar')

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header">
                    <h3 class="box-title">Lista de ventas</h3>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="tablaDT" class="table table-hover">
                        <thead>
                        <tr>
                            <th style="width:10%">Número</th>
                            <th style="width:10%">Fecha emisión</th>
                            <th style="width:25%">Cliente</th>
                            <th style="width:15%">Vendedor</th>
                            <th style="width:15%">Condición de pago</th>
                            <th style="width:10%">Saldo</th>
                            <th style="width:15%">Acción</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($ventas as $venta)
                            <tr>
                                <td>{{$venta->numero}}</td>
                                <td>{{$venta->fecha->format('d/m/Y')}}</td>
                                <td>{{$venta->orden_pedido->cliente->nombre}}</td>
                                <td>{{$venta->orden_pedido->vendedor->nombre}}</td>
                                <td>{{$venta->orden_pedido->condicion_pago->nombre}}</td>
                                <td>$ {{number_format($venta->saldo,2)}}</td>
                                <td align="center">
                                    @if($venta->estado_venta_id != 3)
                                        @if(Auth::user()->rol->nombre == 'Administrador')
                                        <a href="{{ route('abonoNuevo', ['id' => $venta->id]) }}"
                                           class="btn btn-success"><span class="fa fa-credit-card"></span></a>
                                        <a href="{{ route('ventaVerFactura', ['id' => $venta->id]) }}"
                                           class="btn btn-info"><span class="fa fa-eye"></span></a>
                                        <button type="button" class="btn btn-danger" data-toggle="modal"
                                                data-target="#modalEliminar" data-numero="{{ $venta->numero }}"
                                                data-id="{{ $venta->id }}">
                                            <span class="fa fa-minus-square"></span>
                                        </button>
                                        @endif
                                        @if(Auth::user()->rol->nombre == 'Vendedor')
                                                <a href="{{ route('ventaVerFactura', ['id' => $venta->id]) }}"
                                                   class="btn btn-info"><span class="fa fa-eye"></span></a>
                                        @endif
                                    @else
                                        <a href="{{ route('ventaVerFactura', ['id' => $venta->id]) }}"
                                           class="btn btn-info"><span class="fa fa-eye"></span></a>
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
            $('#modalEliminar').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget); // Button that triggered the modal
                let numero_venta = button.data('numero'); // Extract info from data-* attributes
                let id_venta = button.data('id');
                let ruta = '/venta/' + id_venta;
                // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                let modal = $(this);
                // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
                modal.find('#mensaje01').text('Al anular venta se ingresara al inventario el producto y el saldo del cliente se saldará');
                modal.find('#mensaje02').text('Realmente desea anular la venta numero: ' + numero_venta);
                modal.find('#myform').attr("action", ruta);
            });

            $("#tablaDT").DataTable(
                {
                    order: [[1, "asc"]],
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
        })
    </script>
@endsection