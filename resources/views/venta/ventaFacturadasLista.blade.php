@extends('adminlte::layouts.app')

@section('htmlheader_title')
    {{ $titulo }}
@endsection

@section('CSSExtras')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('/plugins/dataTables.bootstrap.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
@endsection

@section('contentheader_title')
    {{ $titulo }}
@endsection

@section('contentheader_description')
    -- Lista de ventas realizadas
@endsection

@section('main-content')

    @include('partials.alertas')
    @include('partials.modalEliminar')

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

                    <h4>Tipo de documento</h4>
                    {{-- Fecha inicio --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label"><b>Tipo documento</b></label>
                        <div class="col-md-8">
                            <select class="form-control select2" style="width:100%" name="tipo" id="tipo-select">
                                <option value="todo">Todos</option>
                                <option value="factura">Facturas</option>
                                <option value="ccf">Créditos Fiscales</option>
                                <option value="anulada">Anuladas</option>
                            </select>
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
                    {{--<h3 class="box-title">Lista de ventas</h3>--}}
                {{--</div><!-- /.box-header -->--}}
                <div class="box-body table-responsive">
                    <table id="tablaDT" class="table table-hover">
                        <thead>
                        <tr>
                            <th style="width:10%">Número</th>
                            <th style="width:10%">Fecha emisión</th>
                            <th style="width:25%">Cliente</th>
                            <th style="width:15%">Vendedor</th>
                            <th style="width:15%">Condición de pago</th>
                            <th style="width:10%">Estado</th>
                            <th style="width:15%">Acción</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($ventas as $venta)
                            <tr>
                                <td>{{$venta->numero}}</td>
                                <td>{{$venta->fecha->format('d/m/Y')}}</td>
                                <td>{{$venta->cliente->nombre}}</td>
                                <td>{{$venta->vendedor->nombre}}</td>
                                <td>
                                    <span class="label label-default">{{$venta->condicion_pago->nombre}}</span>
                                </td>
                                <td>
                                    @if($venta->estado_venta->codigo == 'PP')
                                            <span class="label label-warning">{{ $venta->estado_venta->nombre }}</span>
                                    @elseif($venta->estado_venta->codigo == 'PG')
                                            <span class="label label-success">{{ $venta->estado_venta->nombre }}</span>
                                    @elseif($venta->estado_venta->codigo == 'AN')
                                            <span class="label label-danger">{{ $venta->estado_venta->nombre }}</span>
                                    @endif
                                </td>
                                <td align="center">
                                    @if($venta->tipo_documento->codigo == 'FAC')
                                        <a href="{{ route('ventaVerFactura', ['id' => $venta->id]) }}"
                                           class="btn btn-info"><span class="fa fa-eye"></span></a>
                                    @else
                                        <a href="{{ route('ventaVerCFF', ['id' => $venta->id]) }}"
                                           class="btn btn-info"><span class="fa fa-eye"></span></a>
                                    @endif
                                    @if(Auth::user()->rol->nombre == 'Administrador' && $venta->estado_venta->id != 3)
                                        <button type="button" class="btn btn-danger" data-toggle="modal"
                                                data-target="#modalEliminar" data-numero="{{ $venta->numero }}"
                                                data-id="{{ $venta->id }}">
                                            <span class="fa fa-minus-square"></span>
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
    @include('comun.select2Jses')
    <!-- DataTables -->
    <script src="{{ asset('/plugins/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/plugins/dataTables.bootstrap.min.js') }}"></script>
    <script>
        $(document).ready(Principal);

        function Principal() {
            $('#consultar-buttom').click(OpcionesForm);
            $("#tablaDT").DataTable(
                {
                    order: [[1, "asc"]],
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
            $('#modalEliminar').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget); // Button that triggered the modal
                let numero_venta = button.data('numero'); // Extract info from data-* attributes
                let id_venta = button.data('id');
                let ruta = '/venta/anular/' + id_venta;
                // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                let modal = $(this);
                // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
                modal.find('#mensaje01').text('Al anular venta se ingresara al inventario el producto y el saldo del cliente se saldará');
                modal.find('#mensaje02').text('Realmente desea anular la venta numero: ' + numero_venta);
                modal.find('#myform').attr("action", ruta);
            });
        }

        function OpcionesForm() {
            let fecha_form = $('#opciones-form');
            let fechas_str = fecha_form.serialize();
            let tipo = $('#tipo-select').val();
            let uri = "/venta/tipo/" + tipo + "?" + fechas_str;
            toastr.info("Filtrando por fechas seleccionadas","Excelente!!");
            window.location.href = uri;
        }

    </script>
@endsection