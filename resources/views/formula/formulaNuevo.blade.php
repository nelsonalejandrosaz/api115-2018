@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Ingreso de nueva fórmula
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
@endsection

@section('contentheader_title')
    Nueva formula
@endsection

@section('contentheader_description')
    -- Ingresar una nueva fórmula
@endsection

@section('main-content')

    @include('partials.alertas')

    {{-- div para error de suma de porcentaje --}}
    <div id="divErrorSuma" hidden class="alert alert-danger alert-dismissable">
        {{-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> --}}
        <h4><i class="icon fa fa-ban"></i> Error!</h4>
        La suma debe sumar 100%
    </div>

    <div class="hidden">

        {{--Productos select--}}
        <select required class="form-control select2" style="width:100%" name="productos[]" id="productos-select-id">
            <option selected disabled value="">Seleccione un producto</option>
            @foreach($productos as $producto)
                <option value="{{ $producto->id }}">{{$producto->codigo}} -- {{ $producto->nombre }}</option>
            @endforeach
        </select>

        {{--Cantidad input--}}
        <div class="input-group" id="cantidad-input-id">
            <input required type="number" class="form-control cant cantidad-formula-cls" min="0.001" step="any"
                   name="cantidades[]" value="0">
            <span class="input-group-addon">g</span>
        </div>

        {{--Eliminar boton--}}
        <button type="button" class="btn btn-danger" id="eliminar-button"><span class="fa fa-remove"></span></button>
    </div>

    <!-- Form de nuevo proveedor -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Detalle de formula</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form id="formDatos" class="form-horizontal" action="{{ route('formulaNuevoPost') }}" method="POST">
            {{ csrf_field() }}
            <div class="box-body">
                {{-- Fila  --}}
                <div class="col-md-6">

                    <h4>Información del producto</h4>

                    {{-- Producto --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label"><b>Producto asociado:</b></label>
                        <div class="col-md-8">
                            <select class="form-control select2" style="width:100%" name="producto_id" id="producto-aso-select-id">
                                <option selected disabled value="0">Seleccione un producto</option>
                                @foreach($productos as $producto)
                                    <option value="{{ $producto->id }}"
                                            data-unidadmedida="{{$producto->unidad_medida->nombre}}">{{ $producto->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Cantidad formula --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label"><b>Cantidad fórmula</b></label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="number" class="form-control" step="any"
                                       placeholder="ej. 1" name="cantidad_formula" id="cantidad-total-id">
                                <span class="input-group-addon">Kgs</span>
                            </div>
                        </div>
                    </div>

                    {{-- Descripcion --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label">Descripción:</label>
                        <div class="col-md-8">
                            <textarea class="form-control" name="descripcion"></textarea>
                        </div>
                    </div>

                </div>

                <div class="col-md-6">

                    <h4>Otra información</h4>

                    {{-- Fecha --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label"><b>Fecha ingreso:</b></label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="date" class="form-control" name="fecha">
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
                                   value="0"
                                   name="version" id="version-input-id">
                        </div>
                    </div>

                    {{-- Ingresado por --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label">Ingresado por:</label>
                        <div class="col-md-8">
                            <input readonly type="text" class="form-control"
                                   value="{{ Auth::user()->nombre }} {{ Auth::user()->apellido }}" name="ingresado_id">
                        </div>
                    </div>

                </div>
                {{-- Fin fila --}}

                {{-- Fila --}}
                <div class="col-md-12">
                    {{-- Tabla de productos --}}
                    <table class="table table-bordered" id="tbl-componentes-id">
                        <thead>
                            <tr>
                                <th style="width: 55%">Código -- Producto</th>
                                <th style="width: 35%">Cantidad</th>
                                <th style="width: 10%; text-align: center">
                                    <button class="btn btn-success" id="btn-nueva-fila-id"
                                            type="button">
                                        <span class="fa fa-plus"></span>
                                    </button>
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>

                </div>
                {{-- Fin fila --}}

                {{-- Fila --}}
                <div class="col-md-12">
                    {{-- Tabla de productos --}}
                    <table class="table table-bordered" id="tbl-componentes-id">
                        <tbody>
                        <tr>
                            <td style="width: 55%">Total</td>
                            <td style="width: 35%">
                                <div class="input-group">
                                    <input readonly type="number" class="form-control" step="any"
                                           value="0" id="total-formula-id">
                                    <span class="input-group-addon">g</span>
                                </div>
                            </td>
                            <td style="width: 10%; text-align: center"></td>
                        </tr>
                        </tbody>
                    </table>

                </div>
                {{-- Fin fila --}}

            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('formulaLista') }}" class="btn btn-lg btn-default"><span class="fa fa-close"></span> Cancelar</a>
                <button type="submit" class="btn btn-lg btn-success pull-right"><span class="fa fa-floppy-o"></span> Guardar
                </button>
            </div>
        </form>
    </div><!-- /.box -->

@endsection

@section('JSExtras')
    <!-- Select2 -->
    <script src="{{asset('/plugins/select2.full.min.js')}}"></script>
    {{-- Funcion para cargar mas filas de productos --}}
    <script>
        $(document).on('ready', funcionPrincipal());

        function funcionPrincipal() {
            $("body").on("click", ".btn-danger", funcionEliminarProducto);
            $('#btn-nueva-fila-id').click(nuevaFila);
            $('#producto-aso-select-id').change(cambioProducto);
            selecionarValor();
            $('.select2').select2();
            agregarFuncion();
        }

        function agregarFuncion() {
            $('.cant').keyup(sumarTotal);
            $('.cant').change(sumarTotal);
            selecionarValor();
        }

        function sumarTotal() {
            let total =0;
            $('.cant').each(function () {
                console.log($(this).val());
                total += parseFloat($(this).val());
            });
            $('#total-formula-id').val(total);
            let total_kg = total / 1000;
            $('#cantidad-total-id').val(total_kg.toFixed(4));
        }

        function selecionarValor() {
            $(":input").click(function () {
                $(this).select();
            });
            $(".cant").focusout(function () {
                var numeroDato = ($(this).val().length === 0) ? 0 : parseFloat($(this).val());
                $(this).val(numeroDato.toFixed(3));
            });
        }

        function nuevaFila() {
            let productos_select = $('#productos-select-id').clone(false);
            let cantidad_input = $('#cantidad-input-id').clone(false);
            let eliminar_button = $('#eliminar-button').clone(false);
            $('#tbl-componentes-id')
                .append
                (
                    $('<tr>')
                        .append
                        (
                            $('<td>')
                                .append
                                (
                                    productos_select
                                )
                        )
                        .append
                        (
                            $('<td>')
                                .append
                                (
                                    cantidad_input
                                )
                        )
                        .append
                        (
                            $('<td>').attr('align', 'center')
                                .append
                                (
                                    eliminar_button
                                )
                        )
                );
            $('.select2').select2();
            agregarFuncion();
        }

        function funcionEliminarProducto() {
            $(this).parent().parent().remove();
            sumarTotal()
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

        function cambioProducto() {
            let producto_asc_select = $('#producto-aso-select-id');
            $.ajax({
                url: '/api/formula/version/' + producto_asc_select.val(),
                type: 'GET',
                dataType: 'JSON',
                success: function (dato) {
                    cambioVersion(dato);
                },
            });
        }

        function cambioVersion(version) {
            $('#version-input-id').val(version);
        }

    </script>
@endsection

