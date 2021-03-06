@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Nuevo abono
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
@endsection

@section('contentheader_title')
    Nuevo abono
@endsection

@section('contentheader_description')
    -- Realizar un nuevo abono
@endsection

@section('main-content')

    @include('partials.alertas')

    <!-- Form de nuevo producto -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Datos del abono</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" action="{{ route('abonoNuevoSinVentaPost') }}" method="POST" id="abono-form">
            {{ csrf_field() }}
            <div class="box-body">
                <div class="col-md-6 col-sm-12">
                    <h4>Detalle de cliente</h4>
                    <br>

                    {{-- Nombre del cliente --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b>Cliente</b></label>
                        <div class="col-sm-8">
                            <select class="form-control select2" style="width: 100%" name="cliente_id" id="clienteID" onchange="cambioCliente()">
                                <option value="" selected disabled>Seleciona un cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}"
                                            data-saldo="{{$cliente->saldo}}">{{ $cliente->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Numero de venta --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b>Numero factura</b></label>
                        <div class="col-sm-5">
                            <select class="form-control select2" style="width: 100%" name="venta_id" id="ventaID" onchange="cambioVenta()">
                                <option value="" selected disabled>Selecciona un cliente</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <a href="" target="_blank" class="btn btn-info pull-right"><span class="fa fa-file"></span> Ver factura</a>
                        </div>
                    </div>

                    {{-- Saldo venta --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label" title="El saldo pendiente del documento">Saldo documento</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input readonly type="text" class="form-control" placeholder="0.00" name="saldo_venta"
                                       value="" id="saldoVentaID">
                            </div>
                        </div>
                    </div>

                    {{-- Saldo cliente --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label" title="El saldo pendiente del cliente">Saldo cliente</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input readonly type="text" class="form-control" placeholder="0.00" name="saldo_total"
                                       value="" id="saldoClienteID">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-6 col-sm-12">
                    <h4>Detalle de abono</h4>
                    <br>

                    {{-- Fecha --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b>Fecha ingreso:</b></label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input type="date" class="form-control" name="fecha" id="fecha-input" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Cantidad abono --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b>Cantidad abono</b></label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="number" min="0.00" step="0.01" class="form-control" placeholder="0" name="cantidad"
                                       value="{{ old('cantidad') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Recibo de caja --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">N° Recibo caja</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" placeholder="0" name="recibo_caja"
                                   value="{{ old('cantidad') }}">
                        </div>
                    </div>

                    {{-- Forma de pago --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b>Forma pago</b></label>
                        <div class="col-sm-8">
                            <select class="form-control select2" style="width: 100%" name="forma_pago_id" id="clienteID">
                                <option value="" selected disabled>Selecciona modo pago</option>
                                @foreach($tipo_abonos as $tipo_abono)
                                    <option value="{{ $tipo_abono->id }}">{{ $tipo_abono->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Detalle --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Detalle</label>
                        <div class="col-sm-8">
                            <textarea name="detalle" class="form-control">{{ old('detalle') }}</textarea>
                        </div>
                    </div>

                </div>
            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('abonoLista') }}" class="btn btn-lg btn-default"><span class="fa fa-close"></span> Cancelar</a>
                <button type="submit" class="btn btn-lg btn-success pull-right" id="enviar-buttom-id"><span class="fa fa-credit-card"></span> Abonar</button>
            </div>
        </form>
    </div><!-- /.box -->

@endsection

@section('JSExtras')
    {{--Validacion--}}
    <script src="{{asset('/plugins/jquery-validation/dist/jquery.validate.js')}}"></script>
    <script src="{{asset('/plugins/jquery-validation/dist/additional-methods.min.js')}}"></script>
    <script>

        $(document).on('ready', Principal());

        function Principal() {
            $('#fecha-input').dblclick(FechaModificar);
            Validacion();
        }

        function Validacion() {
            $('#abono-form').validate({
                ignore: [],
                onfocusout: false,
                onkeyup: false,
                rules: {
                    "cliente_id": {
                        required: true,
                    },
                    "venta_id": {
                        required: true,
                    },
                    "fecha": {
                        required: true,
                    },
                    "cantidad": {
                        required: true,
                        min: 0.01,
                    },
                    "forma_pago_id": {
                        required: true,
                    }
                },
                messages: {
                    "cliente_id": {
                        required: function () {
                            toastr.error('Por favor seleccione un cliente a abonar', 'Ups!');
                        },
                    },
                    "venta_id": {
                        required: function () {
                            toastr.error('Por favor seleccione un documento a abonar', 'Ups!');
                        },
                    },
                    "fecha": {
                        required: function () {
                            toastr.error('Por favor complete la fecha del abono', 'Ups!');
                        },
                    },
                    "cantidad": {
                        required: function () {
                            toastr.error('Por favor complete la cantidad a abonar', 'Ups!');
                        },
                        min: function () {
                            toastr.error('La cantidad debe ser mayor a cero', 'Ups!');
                        },
                    },
                    "forma_pago_id": {
                        required: function () {
                            toastr.error('Por favor seleccione una forma de pago', 'Ups!');
                        },
                    },
                },
                submitHandler: function (form) {
                    $('#enviar-buttom').attr('disabled','true');
                    toastr.success('Por favor espere a que se procese','Excelente');
                    form.submit();
                }
            });
        }

        function FechaModificar() {
            $('#fecha-input').removeAttr('readonly');
        }

        // function EnviarAbono() {
        //     $('#enviar-buttom-id').attr('disabled','true');
        //     $('#abono-form-id').submit();
        // }

        function cambioCliente() {
            let cliente_input = $('#clienteID');
            let ventas_select = $('#ventaID');
            let saldo = parseFloat(cliente_input.find(':selected').data('saldo'));
            let saldo_cliente_input = $('#saldoClienteID');
            saldo_cliente_input.val(saldo.toFixed(2));
            // Poner en select los diferentes precios del producto
            $.ajax({
                url: '/api/cliente/' + cliente_input.val(),
                type: 'GET',
                dataType: 'JSON',
                success: function (datos) {
                    ventas_select.select2('destroy');
                    ventas_select.empty();
                    ventas_select.append('<option value="" selected disabled>Selecciona una venta</option>');
                    for (var i = 0; i < datos.length; i++) {
                        if (i == 0) {
                            ventas_select.append('<option value="' + datos[i].id + '" data-saldo="' + datos[i].saldo + '">' + datos[i].numero + '</option>');
                        } else {
                            ventas_select.append('<option value="' + datos[i].id + '" data-saldo="' + datos[i].saldo + '">' + datos[i].numero + '</option>');
                        }
                    }
                    ventas_select.select2();
                },
                async: false
            });
        }

        function cambioVenta() {
            let ventas_select = $('#ventaID');
            let saldo = parseFloat(ventas_select.find(':selected').data('saldo'));
            let saldo_venta_input = $('#saldoVentaID');
            console.log(saldo);
            saldo_venta_input.val(saldo.toFixed(2));
        }

    </script>
    @include('comun.select2Jses')
@endsection
