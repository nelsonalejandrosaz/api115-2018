@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Orden de compra
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
    {{-- DataPicker --}}
    <link rel="stylesheet" href="{{asset('/css/datepicker.css')}}">
@endsection

@section('contentheader_title')
    Orden de compra
@endsection

@section('contentheader_description')

@endsection

@section('main-content')

    @include('partials.alertas')

    <!-- Form de nuevo proveedor -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Detalle</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" action="{{ route('ordenPedidoNuevaPost') }}" method="POST">
            {{ csrf_field() }}
            <div class="box-body">
                {{-- Cabecera --}}
                <div class="col-md-6 col-sm-12">

                    {{-- Fecha ingreso --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label"><b>Fecha venta</b></label>
                        <div class="col-md-9 ">
                            <input type="date" class="form-control" name="fechaIngreso">
                        </div>
                    </div>

                    {{-- Cliente --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label"><b>Cliente</b></label>
                        <div class="col-md-9 ">
                            <select class="form-control select2" style="width: 100%" name="cliente_id">
                                <option value="" selected disabled>Seleciona un cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- Municipio --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label">Municipio</label>
                        <div class="col-md-9 ">
                            <select class="form-control select2" style="width: 100%" name="municipio_id">
                                <option value="" selected disabled>Seleciona un municipio</option>
                                @foreach($municipios as $municipio)
                                    <option value="{{ $municipio->id }}">{{ $municipio->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Direccion --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label">Dirección</label>
                        <div class="col-md-9 ">
                            <textarea class="form-control" name="direccion"></textarea>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12">

                    {{-- Numero Orden Pedido --}}
                    <div class="form-group">
                        <label class="col-md-4  control-label">Orden venta n°:</label>
                        <div class="col-md-8 ">
                            <input type="text" class="form-control" name="numero">
                        </div>
                    </div>

                    {{-- Fecha entrega --}}
                    <div class="form-group">
                        <label class="col-md-4  control-label">Fecha entrega</label>
                        <div class="col-md-8 ">
                            <input type="date" class="form-control" name="fechaIngreso">
                        </div>
                    </div>

                    {{-- Despachado por --}}
                    <div class="form-group">
                        <label class="col-md-4  control-label"><b>Vendedor</b></label>
                        <div class="col-md-8 ">
                            <input type="text" class="form-control" value="{{ Auth::user()->nombre }} {{ Auth::user()->apellido }}" disabled name="despachadoPor">
                        </div>
                    </div>

                    {{-- Ruta archivo --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label">Copia orden</label>
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
                            <th style="width:5%">#</th>
                            <th style="width:40%">Producto -- (Cantidad existencia)</th>
                            <th style="width:10%">Unidad medida</th>
                            <th style="width:10%">Cantidad</th>
                            <th style="width:10%">Precio unitario</th>
                            <th style="width:10%">Ventas exentas</th>
                            <th style="width:10%">Ventas gravadas</th>
                            <th style="width:5%">
                                <button class="btn btn-success" id="btnNuevoProducto" onclick="funcionNuevoProducto()" type="button">
                                    <span class="fa fa-plus"></span>
                                </button>
                            </th>
                        </tr>
                        <tr id="rowProducto1">
                            <td>
                                1
                            </td>
                            <td>
                                <select class="form-control select2 selProd" name="productos_id[]" id="selectProductos">
                                    @foreach($productos as $producto)
                                        <option value="{{ $producto->id }}" data-vu="{{ $producto->precioCompra }}">{{ $producto->nombre }} -- ({{$producto->cantidad}})</option>
                                    @endforeach
                                </select>
                            </td>
                            {{--Unidad de medida--}}
                            <td>
                                <input type="text" class="form-control" name="" id="" disabled>
                            </td>
                            {{--Cantidad--}}
                            <td>
                                <input type="number" class="form-control cant" placeholder="" name="cantidades[]" id="cantidad">
                            </td>
                            {{--Precio unitario--}}
                            <td>
                                <input type="number" class="form-control" placeholder="0" name="" id="" disabled>
                            </td>
                            {{--Ventas exentas--}}
                            <td>
                                <input type="number" class="form-control" placeholder="0" name="valoresTotales[]" id="valorTotal" disabled>
                            </td>
                            {{--Ventas afectas--}}
                            <td>
                                <input type="number" class="form-control" placeholder="0" name="valoresTotales[]" id="valorTotal" disabled>
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
                <a href="{{ route('ordenPedidoLista') }}" class="btn btn-lg btn-default">Cancelar</a>
                <button type="submit" class="btn btn-lg btn-success pull-right">Guardar</button>
            </div>
        </form>
    </div><!-- /.box -->

@endsection

@section('JSExtras')
    {{-- Funcion para cargar mas filas de productos --}}
    <script>
        $(document).on('ready', funcionPrincipal());

        function funcionPrincipal() {
            $("body").on( "click", ".btn-danger",funcionEliminarProducto);
            agregarFuncion();
        }
        var numero = 2;

        function funcionNuevoProducto() {
            copia = $('#selectProductos').clone(false);
            $('#tblProductos')
                .append
                (
                    $('<tr>').attr('id','rowProducto'+numero)
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
                                    '<input type="number" class="form-control cant" placeholder="100" name="cantidades[]" id="cantidad">'
                                )
                        )
                        .append
                        (
                            $('<td>')
                                .append
                                (
                                    '<input type="number" class="form-control" placeholder="100" name="valoresTotales[]" id="valorTotal" disabled>'
                                )
                        )
                        .append
                        (
                            $('<td>').attr('align','center')
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
            agregarFuncion();
        }

        function funcionEliminarProducto() {
            // $(this).remove().end();
            // $(this).closest('tr').remove();
            // console.log($(this).parent().parent());
            $(this).parent().parent().remove();
        }

        function agregarFuncion() {
            $('.cant').each(
                function(index, value){
                    $(this).change(valorTotalJS)
                });
            $('.selProd').each(
                function(index, value){
                    $(this).change(valorTotalJS)
                });
        }

        function valorTotalJS() {
            idSelect = $(this).parent().parent().find('#selectProductos').val();
            // console.log(idSelect);
            valorUnitario = $(this).parent().parent().find('option[value="'+idSelect+'"]').data('vu');
            // cantidad = $(this).val();
            cantidad = $(this).parent().parent().find('#cantidad').val();
            valorTotal = valorUnitario * cantidad;
            $(this).parent().parent().find('#valorTotal').val(valorTotal);
        }

    </script>
    {{-- Fin de funcion para cargar mas filas de productos --}}

    <!-- Select2 -->
    <script src="{{asset('/plugins/select2.full.min.js')}}"></script>
    {{-- Data Picker --}}
    <script src="{{asset('/js/datepicker.js')}}"></script>
    <script>
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();

        });

        $('[data-toggle="datepicker"]').datepicker({
            language: 'es-ES',
            format: 'yyyy/mm/dd'
        });
    </script>
@endsection

