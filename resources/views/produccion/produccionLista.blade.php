@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Producciones
@endsection

@section('CSSExtras')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('/plugins/dataTables.bootstrap.css') }}">
@endsection

@section('contentheader_title')
    Producciones
@endsection

@section('contentheader_description')
    -- Lista de producciones realizadas
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
                <a href="{{ route('produccionNuevo') }}" class="btn btn-lg btn-primary"><span class="fa fa-plus"></span> Nueva producción</a>
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
                    {{--<h3 class="box-title">Lista de producciones</h3>--}}
                {{--</div><!-- /.box-header -->--}}
                <div class="box-body table-responsive">
                    <table id="tablaDT" class="table table-hover">
                        <thead>
                        <tr>
                            <th style="width:10%">Número</th>
                            <th style="width:10%">Fecha realizada</th>
                            <th style="width:25%">Producto</th>
                            <th style="width:20%">Realizada por</th>
                            <th style="width:15%">Acción</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($producciones as $produccion)
                            <tr>
                                <td>{{$produccion->id}}</td>
                                <td>{{$produccion->fecha->format('d/m/Y')}}</td>
                                <td>{{$produccion->producto->nombre}}</td>
                                <td>{{$produccion->bodeguero->nombre}}</td>
                                <td align="center">
                                    @if($produccion->procesado == false)
                                        <a href="{{ route('produccionPrevia', ['id' => $produccion->id]) }}" class="btn btn-warning"><span class="fa fa-check"></span></a>
                                    @else
                                        <a href="{{ route('produccionVer', ['id' => $produccion->id]) }}" class="btn btn-info"><span class="fa fa-eye"></span></a>
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
    <script>
        $(document).ready(Principal);

        function Principal() {
            $('#consultar-buttom').click(OpcionesForm);
            $("#tablaDT").DataTable(
                {
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
            // let uri = "/produccion?" + fechas_str;
            let uri = window.location.pathname + "?" + fechas_str;
            toastr.info("Filtrando por fechas seleccionadas","Excelente!!");
            window.location.href = uri;
        }

    </script>
@endsection
