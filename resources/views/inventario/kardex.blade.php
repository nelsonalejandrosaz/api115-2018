@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Kardex
@endsection

@section('CSSExtras')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('/plugins/dataTables.bootstrap.css') }}">
    <style type="text/css">
        .entradaCSS {
            background-color: #F0F4C3;
        }
        .salidaCSS {
            background-color: #FFCDD2;
        }
        .existenciaCSS {
            background-color: #BBDEFB;
        }
        thead {
            background-color: #607D8B;
            color: white;
        }
    </style>
@endsection

@section('contentheader_title')
    Kardex del producto: {{$producto->nombre}}
@endsection

@section('contentheader_description')
    -- Entradas y salidas del producto
@endsection

@section('main-content')

    @include('partials.alertas')

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Kardex</h3>
                    <a href="{{ route('inventarioLista') }}" class="btn btn-lg btn-default pull-right">Regresar</a>
                </div><!-- /.box-header -->

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-md-4  control-label">Unidad de medida</label>
                            <div class="col-md-8 ">
                                <input type="text" class="form-control" name="numero" value="{{$producto->unidadMedida->nombre}}"
                                       disabled>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-body table-responsive">
                    <table id="tablaKardex" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th style="width: 10%" rowspan="2">Fecha</th>
                            <th style="width: 15%" rowspan="2">Detalle</th>
                            <th style="width: 25%" colspan="3">Entradas</th>
                            <th style="width: 25%" colspan="3">Salidas</th>
                            <th style="width: 25%" colspan="3">Existencias</th>
                        </tr>
                        <tr>
                            <th>Cantidad</th>
                            <th>Costo Unitario</th>
                            <th>Costo Total</th>
                            <th>Cantidad</th>
                            <th>Costo Unitario</th>
                            <th>Costo Total</th>
                            <th>Cantidad</th>
                            <th>Costo Unitario</th>
                            <th>Costo Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($movimientos as $movimiento)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($movimiento->fecha)->format('d/m/Y')}}</td>
                                    <td><a href="">{{$movimiento->detalle}}</a></td>
                                @if($movimiento->tipoMovimiento->codigo == "ENTRADA")
                                    <td class="entradaCSS">{{$movimiento->entrada->cantidad}}</td>
                                    <td class="entradaCSS">${{ number_format($movimiento->entrada->costoUnitario,2) }}</td>
                                    <td class="entradaCSS">${{ number_format($movimiento->entrada->costoTotal,2) }}</td>
                                    <td class="salidaCSS"> -- </td>
                                    <td class="salidaCSS"> -- </td>
                                    <td class="salidaCSS"> -- </td>
                                @elseif($movimiento->tipoMovimiento->codigo == "SALIDA")
                                    <td class="entradaCSS"> -- </td>
                                    <td class="entradaCSS"> -- </td>
                                    <td class="entradaCSS"> -- </td>
                                    <td class="salidaCSS">{{$movimiento->salida->cantidad}}</td>
                                    <td class="salidaCSS">${{ number_format($movimiento->salida->costoUnitario,2) }}</td>
                                    <td class="salidaCSS">${{ number_format($movimiento->salida->costoTotal,2) }}</td>
                                @else
                                    <td class="entradaCSS"> -- </td>
                                    <td class="entradaCSS"> -- </td>
                                    <td class="entradaCSS"> -- </td>
                                    <td class="salidaCSS"> -- </td>
                                    <td class="salidaCSS"> -- </td>
                                    <td class="salidaCSS"> -- </td>
                                @endif
                                <td class="existenciaCSS">{{$movimiento->cantidadExistencia}}</td>
                                <td class="existenciaCSS">${{ number_format($movimiento->costoUnitarioExistencia,2) }}</td>
                                <td class="existenciaCSS">${{ number_format($movimiento->costoTotalExistencia,2) }}</td>
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

@section('JSx')
    <!-- DataTables -->
    <script src="{{ asset('/plugins/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/plugins/dataTables.bootstrap.min.js') }}"></script>
    <script>
        $(function () {
            $("#tablaAlumnos").DataTable(
                {
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

        $.fn.dataTable.ext.search.push(
            function ( settings, data, dataIndex ) {
                var min = parseInt( $('#min').val(), 10 );
                var max = parseInt( $('#max').val(), 10 );
                var age = parseFloat( data[3] ) || 0; // use data for the age column

                if ( ( isNaN( min ) && isNaN( max ) ) ||
                    ( isNaN( min ) && age <= max ) ||
                    ( min <= age   && isNaN( max ) ) ||
                    ( min <= age   && age <= max ) )
                {
                    return true;
                }
                return false;
            }
        );

        $('#modalEliminar').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var nombreProducto = button.data('producto') // Extract info from data-* attributes
            var idProducto = button.data('id')
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            modal.find('.modal-body').text('Desea eliminar ' + nombreProducto)
            modal.find('#myform').attr("action", "/producto/" + idProducto)
        })
    </script>
@endsection
