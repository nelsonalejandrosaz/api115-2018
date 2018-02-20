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

            {{--Cuadro de herramientas--}}
            <div class="box box-default collapsed-box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Opciones</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div><!-- /.box-header -->

                <!-- form start -->
                <form class="form-horizontal" action="{{ route('ordenPedidoListaPost') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="col-md-6 col-sm-12">
                            <h4>Fechas mostradas</h4>
                            {{-- Fecha inicio --}}
                            <div class="form-group">
                                <label class="col-md-4 control-label"><b>Fecha inicio</b></label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input type="date" class="form-control" name="fecha_inicio" id="fecha-inicio" value="{{ $extra['fecha_inicio']->format('Y-m-d') }}">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-6 col-sm-12">
                            <h4><br></h4>
                            {{-- Fecha fin --}}
                            <div class="form-group">
                                <label class="col-md-4 control-label"><b>Fecha fin</b></label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input type="date" class="form-control" name="fecha_fin" id="fecha-fin" value="{{ $extra['fecha_fin']->format('Y-m-d') }}">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div><!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-lg btn-success pull-right"><span
                                    class="fa fa-search"></span> Consultar
                        </button>
                        <a href="{{ route('ordenPedidoNueva') }}" class="btn btn-lg btn-primary" style="margin-right: 5px"><span
                                    class="fa fa-plus"></span> Nueva orden pedido</a>
                    </div>
                </form>
            </div>
            {{--Fin cuadro de herramientas--}}

            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Lista de ordenes de pedido</h3>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="tablaDT" class="table table-hover">
                        <thead>
                        <tr>
                            <th style="width:10%">Número</th>
                            <th style="width:10%">Fecha ingreso</th>
                            <th style="width:10%">Fecha entrega</th>
                            <th style="width:25%">Cliente</th>
                            <th style="width:15%">Vendedor</th>
                            <th style="width:10%">Tipo doc</th>
                            <th style="width:10%">Estado</th>
                            <th style="width:10%">Acción</th>
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
                                <td>{{ $ordenPedido->tipo_documento->codigo }}</td>
                                <td>
                                    @if($ordenPedido->estado_orden->codigo == 'SP')
                                        <span class="label label-warning">{{ $ordenPedido->estado_orden->nombre }}</span>
                                    @elseif($ordenPedido->estado_orden->codigo == 'PR')
                                        <span class="label label-info">{{ $ordenPedido->estado_orden->nombre }}</span>
                                    @elseif($ordenPedido->estado_orden->codigo == 'FC')
                                        <span class="label label-success">{{ $ordenPedido->estado_orden->nombre }}</span>
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
    <script src="{{ asset('/plugins/moment.min.js') }}"></script>
    <script src="{{ asset('/plugins/datetime-moment.js') }}"></script>
    <script !src="">
        $(function () {
            $.fn.dataTable.moment( 'dd/MM/YYYY' );
            $("#tablaDT").DataTable(
                {
                    order: [[1, "desc"]] ,
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
