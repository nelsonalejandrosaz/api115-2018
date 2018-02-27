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
        <textarea type="text" rows="5" class="form-control" name="detalle[]" id="detalle-input-id"></textarea>
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
            <input type="number" class="form-control veCls" name="venta_exenta[]" id="venta-exenta-id">
        </div>
        {{--Venta gravada--}}
        <div class="input-group" id="venta-gravada-id">
            <span class="input-group-addon">$</span>
            <input type="number" class="form-control vaCls" name="venta_gravada[]" >
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
        <form class="form-horizontal" action="{{ route('ventaSinOrdenPost') }}" method="POST" id="venta-form">
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
                            <input readonly type="date" class="form-control" name="fecha"
                                   value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                        </div>
                    </div>

                    {{-- Cliente --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label"><b>Cliente</b></label>
                        <div class="col-md-9 ">
                            <select class="form-control select2" style="width: 100%" name="cliente_id" id="clienteID">
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

                    {{-- Direccion --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label">Dirección</label>
                        <div class="col-md-9 ">
                            <textarea readonly class="form-control" placeholder="Seleccione el cliente" name="direccion"
                                      id="direccionID"></textarea>
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
                                <option value="1" selected>Factura</option>
                            </select>
                        </div>
                    </div>

                    {{-- Condicion pago --}}
                    <div class="form-group">
                        <label class="col-md-4  control-label"><b>Condición pago</b></label>
                        <div class="col-md-8 ">
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

                    {{-- Despachado por --}}
                    <div class="form-group">
                        <label class="col-md-4  control-label">Vendedor</label>
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
                            <th style="width:70%">Producto</th>
                            <th style="width:30%">Ventas gravadas</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>

                    <table class="table table-bordered">
                        <tr>
                            <td style="width:60%"></td>
                            <td style="width:10%">Suma</td>
                            <td style="width:30%">
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="number" class="form-control"
                                           value="" name="suma"
                                           id="ventaTotal">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>- Flete</td>
                            <td>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="number" class="form-control"
                                           value="" name="flete">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Total</td>
                            <td>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="number" class="form-control"
                                           value="" name="total"
                                           id="ventaTotal">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Comisión</td>
                            <td>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="number" class="form-control"
                                           value="" name="comision"
                                           id="ventaTotal">
                                </div>
                            </td>
                        </tr>
                    </table>

                </div>
            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('ventaOrdenesLista') }}" class="btn btn-lg btn-default"><span
                            class="fa fa-close"></span> Cancelar</a>
                <button type="submit" class="btn btn-lg btn-success pull-right"><span class="fa fa-shopping-bag"></span>
                    Generar venta
                </button>
            </div>
        </form>
    </div><!-- /.box -->

@endsection

@section('JSExtras')
    {{--Validacion--}}
    <script src="{{asset('/plugins/jquery-validation/dist/jquery.validate.js')}}"></script>
    <script src="{{asset('/plugins/jquery-validation/dist/additional-methods.min.js')}}"></script>
    @include('comun.select2Jses')
    <script>
        $(document).ready(Principal);

        function Principal() {
            // $('#btn-nueva-fila-id').click(AgregarFila);
            // $("body").on("click", ".btn-danger", EliminarFila);
            Validacion();
            AgregarFila();
        }

        function Validacion() {
            $('#venta-form').validate({
                ignore: [],
                onfocusout: false,
                onkeyup: false,
                rules: {
                    "cliente_id": {
                        required: true,
                    },
                    "numero": {
                        required: true,
                    },
                    "tipo_documento_id": {
                        required: true,
                    },
                    "condicion_pago_id": {
                        required: true,
                    },
                    "detalle[]": {
                        required: true,
                    },
                    "suma": {
                        required: true,
                        min: 0,
                    },
                    "flete": {
                        required: true,
                        min: 0,
                    },
                    "total": {
                        required: true,
                        min: 0,
                    },
                    "comision": {
                        required: true,
                        min: 0,
                    },
                    "venta_gravada[]": {
                        required: true,
                        min: 0,
                    },
                },
                messages: {
                    "cliente_id": {
                        required: function () {
                            toastr.error('Por favor un cliente', 'Ups!');
                        },
                    },
                    "numero": {
                        required: function () {
                            toastr.error('Por favor ingrese el numero del documento', 'Ups!');
                        },
                    },
                    "tipo_documento_id": {
                        required: function () {
                            toastr.error('Por favor elija un tipo de documento', 'Ups!');
                        },
                    },
                    "condicion_pago_id": {
                        required: function () {
                            toastr.error('Por favor elija una condición de pago', 'Ups!');
                        },
                    },
                    "detalle[]": {
                        required: function () {
                            toastr.error('Por favor ingrese el detalle','Ups!')
                        }
                    },
                    "suma": {
                        required: function () {
                            toastr.error('Por favor ingrese la unidad de medida','Ups!')
                        },
                        min: function () {
                            toastr.error('El numero debe ser mayor a 0','Ups!')
                        }
                    },
                    "flete": {
                        required: function () {
                            toastr.error('Por favor ingrese la cantidad','Ups!')
                        },
                        min: function () {
                            toastr.error('El numero debe ser mayor a 0','Ups!')
                        }
                    },
                    "total": {
                        required: function () {
                            toastr.error('Por favor ingrese el precio unitario','Ups!')
                        },
                        min: function () {
                            toastr.error('El numero debe ser mayor a 0','Ups!')
                        }
                    },
                    "comision": {
                        required: function () {
                            toastr.error('Por favor ingrese la venta exenta','Ups!')
                        },
                        min: function () {
                            toastr.error('El numero debe ser mayor a 0','Ups!')
                        }
                    },
                    "venta_gravada[]": {
                        required: function () {
                            toastr.error('Por favor ingrese la venta gravada','Ups!')
                        },
                        min: function () {
                            toastr.error('El numero debe ser mayor a 0','Ups!')
                        }
                    },
                },
                submitHandler: function (form) {
                    $('#enviar-buttom').attr('disabled','true');
                    toastr.success('Por favor espere a que se procese','Excelente');
                    form.submit();
                }
            });
        }

        function AgregarFila() {
            let detalle = $('#detalle-input-id').clone();
            let venta_gravada = $('#venta-gravada-id').clone();
            let eliminar = $('#eliminar-fila-id').clone();
            $('#venta-table-id').find('> tbody')
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
                            $('<td>').attr('align', 'center')
                                .append
                                (
                                    venta_gravada
                                )
                        )
                );
        }

        function EliminarFila() {
            $(this).parent().parent().remove();
        }

    </script>
@endsection

