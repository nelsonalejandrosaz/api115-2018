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

                    {{-- Producto --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label"><b>Producto asociado:</b></label>
                        <div class="col-md-8">
                            <select class="form-control select2" style="width:100%" name="producto_id" onchange="cambioProducto()" id="productoID">
                                <option selected disabled value="0">Seleccione un producto</option>
                                @foreach($productos as $producto)
                                    <option value="{{ $producto->id }}"
                                            data-unidadmedida="{{$producto->unidad_medida->nombre}}">{{ $producto->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Unidad de medida formula--}}
                    <div class="form-group">
                        <label class="col-md-4 control-label">Unidad de medida</label>
                        <div class="col-md-8">
                            <input readonly type="text" class="form-control"
                                   value="Seleccione un producto" id="unidadMedidalbl">
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
                                   value="1"
                                   name="version">
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
                    <table class="table table-bordered" id="tblProductos">
                        <tr>
                            <th style="width: 65%">Código -- Producto</th>
                            <th style="width: 30%">Porcentaje</th>
                            <th style="width: 5%">
                                <button class="btn btn-success" id="btnNuevoProducto" onclick="funcionNuevoProducto()"
                                        type="button">
                                    <span class="fa fa-plus"></span>
                                </button>
                            </th>
                        </tr>
                        <tr>
                            <td>
                                <select class="form-control select2 selProd" style="width:100%" name="productos[]"
                                        id="selectProductos">
                                    <option selected disabled value="">Seleccione un producto</option>
                                    @foreach($productos as $producto)
                                        <option value="{{ $producto->id }}"
                                                data-um="{{ $producto->unidad_medida->abreviatura }}">{{$producto->codigo}}
                                            -- {{ $producto->nombre }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <div class="input-group">
                                    <input type="number" class="form-control cant" value="1" min="1" max="100"
                                           name="porcentajes[]" onkeyup="calcularTotal()" onchange="calcularTotal()">
                                    <span class="input-group-addon">%</span>
                                </div>
                            </td>
                            <td align="center">

                            </td>
                        </tr>
                    </table>
                    <table class="table table-bordered">
                        <th style="width: 65%; text-align: right; vertical-align: middle;">Total:</th>
                        <th style="width: 30%">
                            <div class="input-group">
                                <input type="number" class="form-control" placeholder="0" id="totalPorcentajeInput"
                                       min="100" max="100" value="0" disabled>
                                <span class="input-group-addon">%</span>
                            </div>
                        </th>
                        <th style="width: 5%"></th>
                    </table>
                </div>
                {{-- Fin fila --}}

            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('formulaLista') }}" class="btn btn-lg btn-default"><span class="fa fa-close"></span> Cancelar</a>
                <button type="button" onclick="verificarSuma()" class="btn btn-lg btn-success pull-right"><span class="fa fa-floppy-o"></span> Guardar
                </button>
            </div>
        </form>
    </div><!-- /.box -->

@endsection

@section('JSExtras')
    {{-- Funcion para cargar mas filas de productos --}}
    <script>
        $(document).on('ready', funcionPrincipal());

        function funcionPrincipal() {
            $("body").on("click", ".btn-danger", funcionEliminarProducto);
            selecionarValor();
            calcularTotal();
        }

        function selecionarValor() {
            $(":input").click(function () {
                $(this).select();
            });
            $(".cant").focusout(function () {
                var numeroDato = ($(this).val().length === 0) ? 0 : parseFloat($(this).val());
                $(this).val(numeroDato.toFixed(2));
            });
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
                                    copia
                                )
                        )
                        .append
                        (
                            $('<td>')
                                .append
                                (
                                    '<div class="input-group"><input type="number" class="form-control cant" value="1" min="1" max="100" name="porcentajes[]" onkeyup="calcularTotal()" onchange="calcularTotal()"><span class="input-group-addon">%</span></div>'
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
            selecionarValor();
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
            $('#totalPorcentajeInput').val(totalPorcentaje.toFixed(2));
            // console.log(totalPorcentaje);
        }

        function cambioProducto() {
            var productoId = $('#productoID').val();
            var unidadMedida = $('#productoID').find('option[value="' + productoId + '"]').data('unidadmedida');
            $('#unidadMedidalbl').val(unidadMedida);
        }

    </script>
    {{-- Fin de funcion para cargar mas filas de productos --}}

    @include('comun.select2Jses')
@endsection

