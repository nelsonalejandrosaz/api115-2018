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
    -- Lista de ordenes de pedidos en proceso
@endsection

@section('main-content')

    @include('partials.alertas')

    {{--Cuadro de herramientas // para colapsarlo --> collapsed-box --}}
    <div class="box box-default box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">Opciones</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
        </div><!-- /.box-header -->

        <!-- form start -->
        <form class="form-horizontal" method="GET" id="opciones-form">
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
                <button type="button" class="btn btn-lg btn-success pull-right" id="consultar-buttom"><span
                            class="fa fa-search"></span> Consultar
                </button>
            </div>
        </form>
    </div>
    {{--Fin cuadro de herramientas--}}

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                {{--<div class="box-header">--}}
                    {{--<h3 class="box-title">Lista de ordenes en proceso</h3>--}}
                {{--</div><!-- /.box-header -->--}}
                <div class="box-body table-responsive">
                    <table id="tablaDT" class="table table-hover">
                        <thead>
                        <tr>
                            <th style="width:10%">Número</th>
                            <th style="width:10%">Fecha ingreso</th>
                            <th style="width:10%">Fecha entrega</th>
                            <th style="width:20%">Vendedor</th>
                            <th style="width:10%">Estado</th>
                            <th style="width:15%">Acción</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($ordenesPedidos->sortBy('fecha_entrega') as $ordenPedido)
                            <tr>
                                <td>{{$ordenPedido->numero}}</td>
                                <td>{{$ordenPedido->fecha->format('d/m/Y')}}</td>
                                @if($ordenPedido->fecha_entrega != null)
                                    <td>{{$ordenPedido->fecha_entrega->format('d/m/Y')}}</td>
                                @else
                                    <td>Sin fecha definida</td>
                                @endif
                                <td>{{$ordenPedido->vendedor->nombre}}</td>
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
                                    @if( $ordenPedido->estado_id == 1 )
                                        <a href="{{ route('ordenPedidoVerBodega', ['id' => $ordenPedido->id]) }}"
                                           class="btn btn-success"><span class="fa fa-check-square-o"></span></a>
                                    @else
                                        <a href="{{ route('ordenPedidoVerBodega', ['id' => $ordenPedido->id]) }}"
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
    <script src="{{ asset('/plugins/moment.min.js') }}"></script>
    <script src="{{ asset('/plugins/datetime-moment.js') }}"></script>
    <script>

        $(document).ready(Principal);

        function Principal() {
            $('#consultar-buttom').click(OpcionesForm);
            $.fn.dataTable.moment( 'd/M/YYYY' );
            $("#tablaDT").DataTable(

                {
                    ordering: false,
                    order: [[0, "desc"]],
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
        }

        function OpcionesForm() {
            let fecha_form = $('#opciones-form');
            let fechas_str = fecha_form.serialize();
            let uri = "/orden-pedido-bodega/procesada?" + fechas_str;
            toastr.info("Filtrando por fechas seleccionadas","Excelente!!");
            window.location.href = uri;
        }

    </script>
@endsection
