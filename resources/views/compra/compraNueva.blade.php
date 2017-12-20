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
            <h3 class="box-title">Detalle de factura</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" action="{{route('compraNuevaPost')}}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="box-body">

                {{-- Fila  --}}
                <div class="col-md-6">

                    {{-- Fecha --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label"><b>Fecha ingreso:</b></label>
                        <div class="col-md-8">
                            <input type="date" class="form-control" name="fechaIngreso">
                        </div>
                    </div>

                    {{-- Proveedor --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label"><b>Proveedor:</b></label>
                        <div class="col-md-8">
                            <select class="form-control select2" style="width: 100%" name="proveedor_id">
                                <option value="" disabled selected>Seleccione un proveedor</option>
                                @foreach($proveedores as $proveedor)
                                    <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{--Detalle--}}
                    <div class="form-group">
                        <label class="col-md-4 control-label">Detalle:</label>
                        <div class="col-md-8">
                            <textarea class="form-control" name="detalle"></textarea>
                        </div>
                    </div>

                </div>

                {{-- Fila  --}}
                <div class="col-md-6">

                    {{-- Numero factura --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label"><b>Factura n°:</b></label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="numero">
                        </div>
                    </div>

                    {{-- Ingresado por --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label"><b>Ingresado por:</b></label>
                        <div class="col-md-8">
                            <input disabled type="text" class="form-control" name="ingresadoPor" value="{{ Auth::user()->nombre }} {{ Auth::user()->apellido }}">
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
                            <td style="width: 5%">#</td>
                            <th style="width: 40%">Producto</th>
                            <th style="width: 10%">Unidad medida</th>
                            <th style="width: 10%">Cantidad</th>
                            <th style="width: 15%">Costo unitario</th>
                            <th style="width: 15%">Costo total</th>
                            <th style="width: 5%">
                                <button class="btn btn-success" id="btnNuevoProducto" onclick="funcionNuevoProducto()"
                                        type="button">
                                    <span class="fa fa-plus"></span> Agregar
                                </button>
                            </th>
                        </tr>
                        <tr id="base">
                            {{--id--}}
                            <td>
                                1
                            </td>
                            {{--producto--}}
                            <td>
                                <select class="form-control select2 selProd" style="width: 100%" name="productos_id[]"
                                        id="selectProductos">
                                    <option value="" disabled selected>Seleccione un producto</option>
                                    @foreach($productos as $producto)
                                        <option value="{{ $producto->id }}"
                                                data-um="{{ $producto->unidadMedida->abreviatura }}">{{ $producto->nombre }}</option>
                                    @endforeach
                                </select>
                            </td>
                            {{--unidad medida--}}
                            <td>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="---" id="unidadMedidaLbl" disabled>
                                </div>
                            </td>
                            {{--cantidad--}}
                            <td>
                                <div class="input-group">
                                    <input class="form-control cantidadCls" type="number" value="0" name="cantidades[]" id="cantidad" required>
                                </div>
                            </td>
                            {{--costo unitario --}}
                            <td>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="number" class="form-control costoUnitarioCls" value="0" name="" id="costoUnitario" required>
                                </div>
                            </td>
                            {{--costo total --}}
                            <td>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="number" class="form-control costoTotalCls" value="0" name="valoresTotales[]" id="costoTotal" required>
                                </div>
                            </td>
                            <td align="center">
                                {{-- <div id="a1" class="btn btn-danger">
                                      <span class="fa fa-remove"></span>
                                </div> --}}
                            </td>
                        </tr>
                    </table>
                </div>

            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('compraLista') }}" class="btn btn-lg btn-default">Cancelar</a>
                <button type="submit" class="btn btn-lg btn-success pull-right">Guardar</button>
            </div>
        </form>
    </div><!-- /.box -->

@endsection

@section('JSExtras')
    {{--<script src="{{ asset('/js/compra-nueva.js') }}"></script>--}}
    @include('comun.select2Jses');
    <script>
        var numero = 2;

        $(document).on('ready', funcionPrincipal());

        function funcionPrincipal() {
            $("body").on("click", ".btn-danger", fEliminarProducto);
            agregarFuncion();
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
                                    '<div class="input-group"><input class="form-control" type="number" placeholder="100" name="cantidades[]" required><span class="input-group-addon unimed" id="spamUM">---</span></div>'
                                )
                        )
                        .append
                        (
                            $('<td>')
                                .append
                                (
                                    '<div class="input-group"><span class="input-group-addon">$</span><input type="number" class="form-control" placeholder="100" name="valoresTotales[]" required></div>'
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
        }

        function fEliminarProducto() {
            // $(this).remove().end();
            // $(this).closest('tr').remove();
            $(this).parent().parent().remove();
            numero--;
        }

        function agregarFuncion() {
            $('.selProd').each(
                function (index, value) {
                    $(this).change(unidadMedida);
                }
            );
            $('.cantidadCls').each(
                function (index, value) {
                    $(this).change(cambioCantidad);
                }
            );
            $('.costoUnitarioCls').each(
                function (index, value) {
                    $(this).change(cambioCostoUnitario)
                }
            );
            $('.costoTotalCls').each(
                function (index, value) {
                    $(this).change(cambioCostoTotal)
                }
            );
        }

        function unidadMedida() {
            idSelect = $(this).parent().parent().find('#selectProductos').val();
            // console.log(idSelect);
            // unidadMedida = $(this).find('option[value="'+idSelect+'"]').data('um');
            // console.log($(this).find('option[value="'+idSelect+'"]').data('um'));
            // $(this).parent().parent().find('#spamUM').text(unidadMedida);
            um = $(this).find('option[value="' + idSelect + '"]').data('um');
            // console.log($(this).parent().parent().find('#spamUM').text(um));
            $(this).parent().parent().find('#unidadMedidaLbl').val(um)
        }

        function cambioCantidad() {
            var cantidad = $(this).parent().parent().find('#cantidad').val();
            var costoUnitario = $(this).parent().parent().parent().find('#costoUnitario').val();
            var costoTotal = $(this).parent().parent().parent().find('#costoTotal').val();
            if (cantidad !== 0 || costoUnitario !== 0) {
                costoTotal = cantidad * costoUnitario;
                $(this).parent().parent().parent().find('#costoTotal').val(costoTotal);
            } else if (cantidad !== 0 || costoTotal !== 0) {
                costoUnitario = costoTotal / cantidad;
                $(this).parent().parent().parent().find('#costoUnitario').val(costoUnitario);
            }
        }

        function cambioCostoUnitario() {
            var costoUnitario = $(this).parent().parent().parent().find('#costoUnitario').val();
            var cantidad = $(this).parent().parent().parent().find('#cantidad').val();
            var costoTotal = $(this).parent().parent().parent().find('#costoTotal').val();
            if (cantidad !== 0 || costoUnitario !== 0) {
                costoTotal = cantidad * costoUnitario;
                $(this).parent().parent().parent().find('#costoTotal').val(costoTotal);
            }
        }

        function cambioCostoTotal() {
            var costoTotal = $(this).parent().parent().parent().find('#costoTotal').val();
            var costoUnitario = $(this).parent().parent().parent().find('#costoUnitario').val();
            var cantidad = $(this).parent().parent().parent().find('#cantidad').val();
            if (cantidad !== 0 || costoTotal !== 0) {
                costoUnitario = costoTotal / cantidad;
                $(this).parent().parent().parent().find('#costoUnitario').val(costoUnitario);
            }
        }

    </script>
@endsection

