@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Compras
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
@endsection

@section('contentheader_title')
    Compras
@endsection

@section('contentheader_description')
    -- Ingresar producto al inventario
@endsection

@section('main-content')

    @include('partials.alertas')

    <!-- Form de nuevo proveedor -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Detalle del documento</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" action="{{route('compraNuevaPost')}}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="box-body">

                {{-- Fila  --}}
                <div class="col-md-6">

                    {{-- Fecha --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label"><b>Fecha ingreso</b></label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="date" class="form-control" name="fecha" value="{{ old('fecha') }}">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Proveedor --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label"><b>Proveedor</b></label>
                        <div class="col-md-8">
                            <select class="form-control select2" style="width: 100%" name="proveedor_id">
                                <option disabled selected>Seleccione un proveedor</option>
                                @foreach($proveedores as $proveedor)
                                    @if($proveedor->id == old('proveedor_id'))
                                        <option selected value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                                    @else
                                        <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{--Detalle--}}
                    <div class="form-group">
                        <label class="col-md-4 control-label">Detalle</label>
                        <div class="col-md-8">
                            <textarea class="form-control" name="detalle">{{ old('detalle') }}</textarea>
                        </div>
                    </div>

                </div>

                {{-- Fila  --}}
                <div class="col-md-6">

                    {{-- Numero factura --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label"><b>Factura o CCF n°</b></label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="numero" value="{{ old('numero') }}">
                        </div>
                    </div>

                    {{-- Ingresado por --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label"><b>Ingresado por:</b></label>
                        <div class="col-md-8">
                            <input readonly type="text" class="form-control" name="ingresado_id"
                                   value="{{ Auth::user()->nombre }} {{ Auth::user()->apellido }}">
                        </div>
                    </div>

                    {{--Condición de pago--}}
                    <div class="form-group">
                        <label for="condicionPagoID" class="col-md-4  control-label"><b>Condición pago</b></label>
                        <div class="col-md-8 ">
                            <select class="form-control select2" style="width: 100%" name="condicion_pago_id"
                                    id="condicionPagoID">
                                <option selected disabled>Selecciona una condición de pago</option>
                                @foreach($condiciones_pago as $condicion_pago)
                                    @if($condicion_pago->id == old('condicion_pago_id'))
                                        <option selected value="{{ $condicion_pago->id }}">{{ $condicion_pago->nombre }}</option>
                                    @else
                                        <option value="{{ $condicion_pago->id }}">{{ $condicion_pago->nombre }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Ruta archivo --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label">Copia factura:</label>
                        <div class="col-md-8">
                            <input type="file" class="form-control" name="archivo">
                        </div>
                    </div>

                </div>

                {{-- Fila --}}
                <div class="col-md-12">
                    {{-- Tabla de productos --}}
                    <table class="table table-bordered" id="tblProductos">
                        <tr>
                            <th style="width: 45%">Código -- Producto</th>
                            <th style="width: 10%">Unidad medida</th>
                            <th style="width: 10%">Cantidad</th>
                            <th style="width: 15%">Costo unitario</th>
                            <th style="width: 15%">Costo total</th>
                            <th style="width: 5%">
                                <button class="btn btn-success" id="btnNuevoProducto" onclick="funcionNuevoProducto()"
                                        type="button">
                                    <span class="fa fa-plus"></span>
                                </button>
                            </th>
                        </tr>
                        <tr id="base">

                            {{--producto--}}
                            <td>
                                <select class="form-control select2 selProd" style="width: 100%" name="productos_id[]"
                                        id="selectProductos">
                                    <option value="" disabled selected>Seleccione un producto</option>
                                    @foreach($productos as $producto)
                                        <option value="{{ $producto->id }}"
                                                data-um="{{ $producto->unidad_medida->abreviatura }}">{{$producto->codigo}}
                                            -- {{ $producto->nombre }}</option>
                                    @endforeach
                                </select>
                            </td>
                            {{--unidad medida--}}
                            <td>
                                <div class="input-group">
                                    <input readonly type="text" class="form-control" placeholder="---" id="unidadMedidaLbl">
                                </div>
                            </td>
                            {{--cantidad--}}
                            <td>
                                <div class="input-group">
                                    <input class="form-control cantidadCls" type="number" value="0" min="0.001"
                                           step="any"
                                           name="cantidades[]" id="cantidad" required>
                                </div>
                            </td>
                            {{--costo unitario --}}
                            <td>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="number" step="any" class="form-control costoUnitarioCls" min="0.01"
                                           value="0.00" name="costo_unitarios[]" id="costoUnitario" required>
                                </div>
                            </td>
                            {{--costo total --}}
                            <td>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="number" step="any" class="form-control costoTotalCls" min="0.01"
                                           value="0.00" name="costo_totales[]" id="costoTotal" required>
                                </div>
                            </td>
                            <td align="center">
                                {{-- <div id="a1" class="btn btn-danger">
                                      <span class="fa fa-remove"></span>
                                </div> --}}
                            </td>
                        </tr>
                    </table>

                    <table class="table table-bordered">
                        <tr>
                            <th style="width:65%"></th>
                            <th style="width:15%">Compra Total</th>
                            <th style="width:15%">
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input readonly type="number" class="form-control" value="0.00" name="compra-total"
                                           id="compraTotal">
                                </div>
                            </th>
                            <th style="width:5%"></th>
                        </tr>
                        <tr>
                            <th style="width:65%"></th>
                            <th style="width:15%">Compra Total + IVA</th>
                            <th style="width:15%">
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input readonly type="number" class="form-control" value="0.00" name="compra-total-iva"
                                           id="compra-total-iva-id">
                                </div>
                            </th>
                            <th style="width:5%"></th>
                        </tr>
                    </table>

                </div>

            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('compraLista') }}" class="btn btn-lg btn-default"><span class="fa fa-close"></span>
                    Cancelar</a>
                <button type="submit" class="btn btn-lg btn-success pull-right"><span class="fa fa-floppy-o"></span>
                    Guardar
                </button>
            </div>
        </form>
    </div><!-- /.box -->

    <div class="hidden">
        <input type="number" value="{{ \App\Configuracion::find(1)->iva }}" id="iva-id">
    </div>

@endsection

@section('JSExtras')
    {{--<script src="{{ asset('/js/compra-nueva.js') }}"></script>--}}
    @include('comun.select2Jses');
    <script>
        var numero = 2;

        $(document).on('ready', funcionPrincipal());

        function funcionPrincipal() {
            $("body").on("click", ".btn-danger", fEliminarProducto);
            selecionarValor();
            agregarFuncion();
        }

        function selecionarValor() {
            $(":input").click(function () {
                $(this).select();
            });
            $(".costoUnitarioCls,.costoTotalCls").focusout(function () {
                var numeroDato = ($(this).val().length === 0) ? 0 : parseFloat($(this).val());
                $(this).val(numeroDato.toFixed(4));
            });
            $(".cantidadCls").focusout(function () {
                var numeroCantidad = ($(this).val().length === 0) ? 0 : parseFloat($(this).val());
                $(this).val(numeroCantidad);
            });
        }

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
                                    '<div class="input-group">\n' +
                                    '<input type="text" class="form-control" placeholder="---" id="unidadMedidaLbl" disabled>\n' +
                                    '</div>'
                                )
                        )
                        .append
                        (
                            $('<td>')
                                .append
                                (
                                    '<div class="input-group">\n' +
                                    '<input class="form-control cantidadCls" type="number" value="0" min="0.001" step="any"\n' +
                                    '                                           name="cantidades[]" id="cantidad" required>' +
                                    '</div>'
                                )
                        )
                        .append
                        (
                            $('<td>')
                                .append
                                (
                                    '<div class="input-group">\n' +
                                    '<span class="input-group-addon">$</span>\n' +
                                    '<input type="number" step="any" class="form-control costoUnitarioCls" value="0.00" name="costo_unitarios[]" id="costoUnitario" required>\n' +
                                    '</div>'
                                )
                        )
                        .append
                        (
                            $('<td>')
                                .append
                                (
                                    '<div class="input-group">\n' +
                                    '<span class="input-group-addon">$</span>\n' +
                                    '<input type="number" step="any" class="form-control costoTotalCls" value="0.00" name="costo_totales[]" id="costoTotal" required>\n' +
                                    '</div>'
                                )
                        )
                        .append
                        (
                            $('<td>').attr('align', 'center')
                                .append
                                (
                                    '<button type="button" class="btn btn-danger" type="button"><span class="fa fa-remove"></span></button>'
                                )
                        )
                );
            //Initialize Select2 Elements
            $(".select2").select2();
            $(".select2").select2();
            numero++;
            // console.log('btn agregar');
            agregarFuncion();
            selecionarValor();
        }

        function fEliminarProducto() {
            // $(this).remove().end();
            // $(this).closest('tr').remove();
            $(this).parent().parent().remove();
            numero--;
            cambioTotal();
        }

        function agregarFuncion() {
            $('.selProd').each(
                function (index, value) {
                    $(this).keyup(unidadMedida);
                    $(this).change(unidadMedida);
                }
            );
            $('.cantidadCls').each(
                function (index, value) {
                    $(this).keyup(cambioCantidad);
                    $(this).change(cambioCantidad);
                }
            );
            $('.costoUnitarioCls').each(
                function (index, value) {
                    $(this).keyup(cambioCostoUnitario);
                    $(this).change(cambioCostoUnitario);
                }
            );
            $('.costoTotalCls').each(
                function (index, value) {
                    $(this).keyup(cambioCostoTotal);
                    $(this).change(cambioCostoTotal);
                }
            );
        }

        function cambioTotal() {
            let compraTotal = 0;
            let compraTotalIVA = 0;
            let iva = parseFloat($('#iva-id').val());
            $(".costoTotalCls").each(
                function (index, value) {
                    if ($.isNumeric($(this).val())) {
                        compraTotal = compraTotal + eval($(this).val());
                        compraTotal = parseFloat(compraTotal);
                        compraTotalIVA = compraTotal * iva;
                    }
                }
            );
            $("#compraTotal").val(compraTotal.toFixed(2));
            $("#compra-total-iva-id").val(compraTotalIVA.toFixed(2));
            console.log(iva);
            console.log(compraTotalIVA.toFixed(2));
        }

        function unidadMedida() {
            idSelect = $(this).parent().parent().find('#selectProductos').val();
            um = $(this).find('option[value="' + idSelect + '"]').data('um');
            $(this).parent().parent().find('#unidadMedidaLbl').val(um);
            cambioTotal();
        }

        function cambioCantidad() {
            var cantidad = parseFloat($(this).parent().parent().find('#cantidad').val());
//                parseFloat($(this).parent().parent().find('#cantidad').val());
            var costoUnitario = parseFloat($(this).parent().parent().parent().find('#costoUnitario').val());
            var costoTotal = parseFloat($(this).parent().parent().parent().find('#costoTotal').val());
            if (cantidad !== 0 || costoUnitario !== 0) {
                costoTotal = cantidad * costoUnitario;
                $(this).parent().parent().parent().find('#costoTotal').val(costoTotal.toFixed(2));
            } else if (cantidad !== 0 || costoTotal !== 0) {
                costoUnitario = costoTotal / cantidad;
                $(this).parent().parent().parent().find('#costoUnitario').val(costoUnitario.toFixed(4));
            }
            cambioTotal();
        }

        function cambioCostoUnitario() {
            var costoUnitario = parseFloat($(this).parent().parent().parent().find('#costoUnitario').val());
            var cantidad = parseFloat($(this).parent().parent().parent().find('#cantidad').val());
            var costoTotal = parseFloat($(this).parent().parent().parent().find('#costoTotal').val());
            if (cantidad !== 0 || costoUnitario !== 0) {
                costoTotal = cantidad * costoUnitario;
                $(this).parent().parent().parent().find('#costoTotal').val(costoTotal.toFixed(2));
            }
            cambioTotal();
        }

        function cambioCostoTotal() {
            var costoTotal = $(this).parent().parent().parent().find('#costoTotal').val();
            var costoUnitario = $(this).parent().parent().parent().find('#costoUnitario').val();
            var cantidad = $(this).parent().parent().parent().find('#cantidad').val();
            if (cantidad !== 0 || costoTotal !== 0) {
                costoUnitario = costoTotal / cantidad;
                $(this).parent().parent().parent().find('#costoUnitario').val(costoUnitario.toFixed(4));
            }
            cambioTotal();
        }

    </script>
@endsection

