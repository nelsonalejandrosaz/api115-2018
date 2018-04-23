@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Nuevo proveedor
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
@endsection

@section('contentheader_title')
    Nuevo proveedor
@endsection

@section('contentheader_description')
    -- Agregar nuevo proveedor
@endsection

@section('main-content')

    @include('partials.alertas')

    <!-- Form de nuevo proveedor -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Datos de proveedor</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" action="{{ route('proveedorNuevoPost') }}" method="POST">
            {{ csrf_field() }}
            <div class="box-body">
                <div class="col-xs-6">
                    <h4>Datos generales</h4>
                    <br>

                    {{-- Nombre del proveedor --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b>Nombre proveedor</b></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="ej. Indrustrias Juan Perez S.A. de C.V." name="nombre">
                        </div>
                    </div>

                    {{-- Direccion del proveedor --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Direccion</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="ej. Avenida San Jose..." name="direccion">
                        </div>
                    </div>

                    {{-- Localidad --}}
                    <div class="form-group">
                        <label class="col-md-4  control-label"><b>Localidad</b></label>
                        <div class="col-md-8 ">
                            <select class="form-control select2" style="width: 100%" name="nacional">
                                <option selected disabled>Selecciona una opción</option>
                                <option value="1">Nacional</option>
                                <option value="0">Internacional</option>
                            </select>
                        </div>
                    </div>

                    {{-- NIT --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">NIT</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="ej. 0123-012345-012-0" name="nit">
                        </div>
                    </div>

                    {{-- NRC --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">NRC</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="ej. Juan Perez" name="nrc">
                        </div>
                    </div>

                </div>
                <div class="col-xs-6">
                    <h4>Contacto y teléfonos</h4>
                    <br>

                    {{-- Contacto del proveedor --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Contacto</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="ej. Juan Perez" name="nombre_contacto">
                        </div>
                    </div>

                    {{-- Telefono principal del proveedor --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Telefono principal</label>
                        <div class="col-sm-8">
                            <input type="tel" class="form-control" placeholder="ej. 7777-7777"
                                   name="telefono_1">
                        </div>
                    </div>
                    {{-- Telefono secundario del proveedor --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Telefono secundario</label>
                        <div class="col-sm-8">
                            <input type="tel" class="form-control" placeholder="ej. 7777-7777"
                                   name="telefono_2">
                        </div>
                    </div>
                </div>
            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('proveedorLista') }}" class="btn btn-lg btn-default"><span class="fa fa-close"></span> Cancelar</a>
                <button type="submit" class="btn btn-lg btn-success pull-right"><span class="fa fa-save"></span> Guardar</button>
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
