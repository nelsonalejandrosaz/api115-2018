@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Venta
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
@endsection

@section('contentheader_title')
    Venta
@endsection

@section('contentheader_description')
    -- Venta sin orden de pedido
@endsection

@section('main-content')

    @include('partials.alertas')

    <div class="hidden">
        {{--Detalle producto--}}
        <input type="text" class="form-control" name="detalle[]" id="detalle-input-id">
        {{--Unidad de medida--}}
        <input type="text" class="form-control unidadCls" name="unidad_medida[]" id="unidad-medida-input-id">
        {{--Cantidad--}}
        <input type="number" class="form-control cantidadCls" name="cantidad[]" id="cantidad-input-id">
        {{--Precio unitario--}}
        <div class="input-group">
            <span class="input-group-addon">$</span>
            <input type="number" class="form-control puCls" name="precio_unitario[]" id="precio-unitario-input-id">
        </div>
        {{--Venta exenta--}}
        <div class="input-group">
            <span class="input-group-addon">$</span>
            <input type="text" class="form-control veCls" name="venta_exenta[]" id="venta-exenta-id">
        </div>
        {{--Venta gravada--}}
        <div class="input-group">
            <span class="input-group-addon">$</span>
            <input type="text" class="form-control vaCls" name="venta_gravada[]" id="venta-gravada-id">
        </div>
        {{--Eliminar boton--}}
        <button type="button" class="btn btn-danger" id="eliminar-fila-id"><span class="fa fa-remove"></span></button>
    </div>

    <!-- Form de nuevo proveedor -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Detalle de venta</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" action="" method="POST">
            {{ csrf_field() }}
            <div class="box-body">
                {{-- Cabecera --}}
                <div class="col-md-6 col-sm-12">

                    <h4>Información de venta</h4>
                    <br>

                    {{-- Fecha ingreso --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label"><b>Fecha venta</b></label>
                        <div class="col-md-9 ">
                            <input type="date" class="form-control" name="fecha"
                                   value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                        </div>
                    </div>

                    {{-- Cliente --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label"><b>Cliente</b></label>
                        <div class="col-md-9 ">
                            <select class="form-control select2" style="width: 100%" name="cliente_id" id="clienteID"
                                    onchange="cambioCliente()">
                                <option value="" selected disabled>Seleciona un cliente</option>
                                @foreach($clientes as $cliente)
                                    @if($cliente->id == old('cliente_id'))
                                        <option selected value="{{ $cliente->id }}"
                                                data-direccion="{{$cliente->direccion}}"
                                                data-municipio="{{$cliente->municipio->nombre}}">{{ $cliente->nombre }}</option>
                                    @else
                                        <option value="{{ $cliente->id }}"
                                                data-direccion="{{$cliente->direccion}}"
                                                data-municipio="{{$cliente->municipio->nombre}}">{{ $cliente->nombre }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- NRC --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label"><b>NRC</b></label>
                        <div class="col-md-9 ">
                            <input readonly type="text" class="form-control" name="nrc"
                                   value="">
                        </div>
                    </div>

                    {{-- Municipio --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label">Municipio</label>
                        <div class="col-md-9 ">
                            <input readonly type="text" class="form-control" name="municipio" id="municipioID"
                                   value="">
                        </div>
                    </div>

                    {{-- Direccion --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label">Dirección</label>
                        <div class="col-md-9 ">
                            <textarea readonly class="form-control" placeholder="Seleccione el cliente" name="direccion"
                                      id="direccionID"></textarea>
                        </div>
                    </div>

                    {{-- Condicion pago --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label">Condición pago</label>
                        <div class="col-md-9 ">
                            <select class="form-control select2" style="width: 100%" name="condicion_pago_id"
                                    id="condicionPagoID">
                                <option selected disabled>Selecciona una condición de pago</option>
                                @foreach($condiciones_pago as $condicion_pago)
                                    @if($condicion_pago->id == old('condicion_pago_id'))
                                        <option selected
                                                value="{{ $condicion_pago->id }}">{{ $condicion_pago->nombre }}</option>
                                    @else
                                        <option value="{{ $condicion_pago->id }}">{{ $condicion_pago->nombre }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>

                <div class="col-md-6 col-sm-12">

                    <h4><br></h4>
                    <br>

                    {{-- Numero documento venta --}}
                    <div class="form-group">
                        <label class="col-md-4  control-label"><b>N° Documento</b></label>
                        <div class="col-md-8 ">
                            <input type="text" class="form-control" name="numero"
                                   placeholder="Numero factura o Crédito Fiscal" value="">
                        </div>
                    </div>

                    {{-- Tipo Documento --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b>Tipo de documento</b></label>
                        <div class="col-sm-8">
                            <select class="form-control select2" name="tipo_documento_id">
                                <option selected disabled>Seleccione una opción</option>
                                @foreach($tipoDocumentos as $tipoDocumento)
                                    <option value="{{ $tipoDocumento->id }}">{{ $tipoDocumento->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Despachado por --}}
                    <div class="form-group">
                        <label class="col-md-4  control-label"><b>Vendedor</b></label>
                        <div class="col-md-8 ">
                            <input readonly type="text" class="form-control"
                                   value="{{ Auth::user()->nombre }} {{ Auth::user()->apellido }}"
                                   name="despachadoPor">
                        </div>
                    </div>

                </div>

                {{-- Fila --}}
                <div class="col-md-12">

                    <br>
                    <h4>Detalle de venta</h4>

                    {{-- Tabla de productos --}}
                    <table class="table table-bordered" id="venta-table-id">
                        <thead>
                        <tr>
                            <th style="width:35%">Producto</th>
                            <th style="width:10%">Unidad medida</th>
                            <th style="width:10%">Cantidad</th>
                            <th style="width:10%">Precio unitario</th>
                            <th style="width:15%">Ventas exentas</th>
                            <th style="width:15%">Ventas gravadas</th>
                            <th style="width:5%; text-align: center">
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

                    <table class="table table-bordered">
                        <tr>
                            <td style="width:65%"></td>
                            <td style="width:15%">Suma</td>
                            <td style="width:15%">
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="number" class="form-control"
                                           value="" name="compraTotal"
                                           id="ventaTotal">
                                </div>
                            </td>
                            <td style="width:5%"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>- Flete</td>
                            <td>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="number" class="form-control"
                                           value="" name="compraTotal"
                                           id="ventaTotal">
                                </div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Total</td>
                            <td>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="number" class="form-control"
                                           value="" name="compraTotal"
                                           id="ventaTotal">
                                </div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Comisión</td>
                            <td>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="number" class="form-control"
                                           value="" name="compraTotal"
                                           id="ventaTotal">
                                </div>
                            </td>
                            <td></td>
                        </tr>
                    </table>

                </div>
            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('ventaOrdenesLista') }}" class="btn btn-lg btn-default"><span
                            class="fa fa-close"></span> Cancelar</a>
                <button type="button" class="btn btn-lg btn-success pull-right"><span class="fa fa-money"></span>
                    Generar venta
                </button>
            </div>
        </form>
    </div><!-- /.box -->

@endsection

@section('JSExtras')
    @include('comun.select2Jses')
    <script>
        $(document).ready(Principal);

        function Principal() {
            $('#btn-nueva-fila-id').click(AgregarFila);
            // $('#eliminar-fila-id').click(EliminarFila);
            $("body").on("click", ".btn-danger", EliminarFila);
        }

        function AgregarFila() {
            let detalle = $('#detalle-input-id').clone();
            let unidad_medida = $('#unidad-medida-input-id').clone();
            let cantidad = $('#cantidad-input-id').clone();
            let precio_unitario = $('#precio-unitario-input-id').clone();
            let venta_exenta = $('#venta-exenta-id').clone();
            let venta_grabada = $('#venta-gravada-id').clone();
            let eliminar = $('#eliminar-fila-id').clone();
            $('#venta-table-id > tbody')
                .append(
                    $('<tr>')
                        .append
                        (
                            $('<td>')
                                .append
                                (
                                    detalle
                                )
                        )
                        .append
                        (
                            $('<td>')
                                .append
                                (
                                    unidad_medida
                                )
                        )
                        .append
                        (
                            $('<td>').attr('align', 'center')
                                .append
                                (
                                    cantidad
                                )
                        )
                        .append
                        (
                            $('<td>').attr('align', 'center')
                                .append
                                (
                                    precio_unitario
                                )
                        )
                        .append
                        (
                            $('<td>').attr('align', 'center')
                                .append
                                (
                                    venta_exenta
                                )
                        )
                        .append
                        (
                            $('<td>').attr('align', 'center')
                                .append
                                (
                                    venta_grabada
                                )
                        )
                        .append
                        (
                            $('<td>').attr('align', 'center')
                                .append
                                (
                                    eliminar
                                )
                        )
                );
        }

        function EliminarFila() {
            $(this).parent().parent().remove();
        }

    </script>
@endsection

