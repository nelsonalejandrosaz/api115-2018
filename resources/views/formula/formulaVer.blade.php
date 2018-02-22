@extends('adminlte::layouts.app')

@section('htmlheader_title')
    {{-- {{ trans('message.tituloProveedorNuevo') }} --}}
    Ver fórmula
@endsection

@section('CSSx')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
    {{-- DataPicker --}}
    <link rel="stylesheet" href="{{asset('/css/datapicker/bootstrap-datepicker3.css')}}">
@endsection

@section('contentheader_title')
    {{-- {{ trans('message.tituloProveedorNuevo') }} --}}
    Ver fórmula
@endsection

@section('contentheader_description')

@endsection

@section('main-content')

    @include('partials.alertas')

    <!-- Form de nuevo proveedor -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Detalle de formula</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form id="formDatos" class="form-horizontal" action="">
            {{ csrf_field() }}
            <div class="box-body">

                {{-- Fila  --}}
                <div class="col-md-6">

                    {{-- Producto --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label">Producto asociado</label>
                        <div class="col-md-8">
                            <input readonly type="text" class="form-control"
                                   value="{{ $formula->producto->nombre }}"
                                   name="producto_id">
                        </div>
                    </div>

                    {{-- Cantidad--}}
                    <div class="form-group">
                        <label class="col-md-4 control-label">Cantidad fórmula</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input readonly type="text" class="form-control"
                                       value="{{ $formula->cantidad_formula }}" id="unidadMedidalbl">
                                <span class="input-group-addon">Kgs</span>
                            </div>
                        </div>
                    </div>

                    {{-- Descripcion --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label">Descripción</label>
                        <div class="col-md-8">
                            <textarea readonly class="form-control"
                                      name="descripcion">{{$formula->descripcion}}</textarea>
                        </div>
                    </div>

                </div>

                <div class="col-md-6">

                    {{-- Fecha --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label">Fecha ingreso</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input readonly type="date" class="form-control" name="fecha"
                                       value="{{$formula->fecha->format('Y-m-d')}}">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Version --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label">Version</label>
                        <div class="col-md-8">
                            <input readonly type="text" class="form-control"
                                   value="{{ $formula->version}}"
                                   name="version">
                        </div>
                    </div>

                    {{-- Estado --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label">Estado</label>
                        <div class="col-md-8">
                            @if($formula->activa)
                                <input readonly type="text" class="form-control"
                                       value="Activa"
                                       name="version">
                            @else
                                <input readonly type="text" class="form-control"
                                       value="No activa"
                                       name="version">
                            @endif
                        </div>
                    </div>

                    {{-- Ingresado por --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label">Ingresado por</label>
                        <div class="col-md-8">
                            <input readonly type="text" class="form-control"
                                   value="{{$formula->ingresado->nombre}} {{$formula->ingresado->apellido}}"
                                   name="ingresadoPor">
                        </div>
                    </div>

                </div>
                {{-- Fin fila --}}

                {{-- Fila --}}
                <div class="col-md-12">
                    {{-- Tabla de productos --}}
                    <table class="table table-bordered" id="tblProductos">
                        <tr>
                            <th style="width: 60%">Producto</th>
                            <th style="width: 40%">Cantidad</th>
                            </th>
                        </tr>
                        @php($total_gr = 0.00)
                        @foreach($formula->componentes as $componente)
                            @php($total_gr += $componente->cantidad)
                            <tr>
                                <td>
                                    <input readonly type="text" class="form-control" value="{{ $componente->producto->nombre }}">
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input readonly type="number" class="form-control cant"
                                               value="{{$componente->cantidad}}" name="porcentajes[]">
                                        <span class="input-group-addon">g</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </table>

                </div>
                {{-- Fin fila --}}

                {{-- Fila --}}
                <div class="col-md-12">
                    {{-- Tabla de productos --}}
                    <table class="table table-bordered" id="tbl-componentes-id">
                        <tbody>
                        <tr>
                            <td style="width: 60%">Total</td>
                            <td style="width: 40%">
                                <div class="input-group">
                                    <input readonly type="number" class="form-control" step="any"
                                           value="{{ $total_gr }}" id="total-formula-id">
                                    <span class="input-group-addon">g</span>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                {{-- Fin fila --}}

            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('formulaLista') }}" class="btn btn-lg btn-default"><span
                            class="fa fa-mail-reply"></span> Regresar a lista</a>
                @if(!$formula->activa)
                    <button type="submit" class="btn btn-lg btn-warning pull-right"><span class="fa fa-linux"></span> Activar formula</button>
                @endif
            </div>
        </form>
    </div><!-- /.box -->

@endsection

@section('JSx')
    {{-- Funcion para cargar mas filas de productos --}}
    <script>
        $(document).on('ready', funcionPrincipal());

        function funcionPrincipal() {
            $("body").on("click", ".btn-danger", funcionEliminarProducto);
            calcularTotal();
        }

        var numero = 2;

        function funcionNuevoProducto() {
            copia = $('#selectProductos').clone(false);
            $('#tblProductos')
                .append
                (
                    $('<tr>').attr('id', 'rowProducto' + numero)
                        .append
                        (
                            $('<td>')
                                .append
                                (
                                    numero
                                )
                        )
                        .append
                        (
                            $('<td>')
                                .append
                                (
                                    copia
                                )
                        )
                        .append
                        (
                            $('<td>')
                                .append
                                (
                                    '<div class="input-group"><input type="number" class="form-control cant" value="1" min="1" max="100" name="porcentajes[]" onchange="calcularTotal()"><span class="input-group-addon">%</span></div>'
                                )
                        )
                        .append
                        (
                            $('<td>').attr('align', 'center')
                                .append
                                (
                                    '<button type="button" class="btn btn-danger" click="funcionEliminarProducto()" type="button"><span class="fa fa-remove"></span></button>'
                                )
                        )
                );
            //Initialize Select2 Elements
            $(".select2").select2();
            $(".select2").select2();
            numero++;
            calcularTotal();
        }

        function funcionEliminarProducto() {
            // $(this).remove().end();
            // $(this).closest('tr').remove();
            // console.log($(this).parent().parent());
            $(this).parent().parent().remove();
            calcularTotal();
        }

        function verificarSuma() {
            var total = $('#totalPorcentajeInput').val();
            if (total != 100) {
                $('#divErrorSuma').show();
                setTimeout(function () {
                    $('#divErrorSuma').hide();
                }, 3000);
            } else {
                $('#formDatos').submit();
            }
        }

        function calcularTotal() {
            var totalPorcentaje = 0;
            var porcentajes = $('.cant');
            for (var i = 0; i < porcentajes.length; i++) {
                porcentaje = parseFloat(porcentajes[i].value);
                totalPorcentaje = totalPorcentaje + porcentaje;
            }
            $('#totalPorcentajeInput').val(totalPorcentaje);
            // console.log(totalPorcentaje);
        }

    </script>
    {{-- Fin de funcion para cargar mas filas de productos --}}

    <!-- Select2 -->
    <script src="{{asset('/plugins/select2.full.min.js')}}"></script>
    {{-- Data Picker --}}
    <script src="{{asset('/js/datapicker/bootstrap-datepicker.js')}}"></script>
    <script>
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
            // Inicializar el datapicker
            $('.datepicker').datepicker(
                {
                    format: "yyyy/mm/dd",
                    todayBtn: "linked",
                    language: "es",
                    autoclose: true
                });

        });
    </script>
@endsection

