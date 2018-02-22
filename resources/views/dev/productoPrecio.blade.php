@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Precios del producto
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
@endsection

@section('contentheader_title')
    Precios del producto: {{$producto->nombre}}
@endsection

@section('contentheader_description')

@endsection

@section('main-content')

    @include('partials.alertas')
    @include('partials.modalEliminar')

    {{-- Div oculto --}}
    <div class="hidden">
        {{--Presentacion--}}
        <input type="text" class="form-control" name="presentacion[]" placeholder="ej. Kilogramo" id="presentacion-input">

        {{--Descripcion en factura--}}
        <input type="text" class="form-control" name="descripcion[]" placeholder="ej. Tarro de 250g" id="descripcion-input">

        {{--Unidad de medida--}}
        <select style="width: 100%" class="form-control select2" name="unidad_medida_id[]" id="unidad-medida-select">
            <option value="" selected disabled>Seleccione una opción</option>
            @foreach($unidad_medidas as $unidad_medida)
                <option value="{{ $unidad_medida->id }}">{{ $unidad_medida->nombre }}
                    - {{ $unidad_medida->abreviatura }}</option>
            @endforeach
        </select>

        {{--Precio--}}
        <div class="input-group" id="precio-div">
            <span class="input-group-addon">$</span>
            <input type="number" min="0.00" step="any" class="form-control cantidadCls" name="precio[]" id="precio-input">
        </div>

        {{--Precio con IVA--}}
        <div class="input-group" id="precio-iva-div">
            <span class="input-group-addon">$</span>
            <input readonly type="number" min="0.00" step="any" class="form-control cant" id="precio-iva-input">
        </div>

        {{--Factor--}}
        <input type="number" min="0.00" step="any" class="form-control factor" name="factor[]" id="factor-input">

        {{--Boton eliminar--}}
        <button type="button" class="btn btn-danger btn-eliminar" id="eliminar-button"><span class="fa fa-remove"></span></button>

        {{--IVA--}}
        <input type="number" value="{{ \App\Configuracion::find(1)->iva }}" id="iva-input">

    </div>

    <!-- Form de nuevo proveedor -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Precios asignados</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form id="precios-form-id" class="form-horizontal" action="{{ route('productoPrecioPost',['id' => $producto->id]) }}" method="POST">
            {{ csrf_field() }}
            <div class="box-body">
                {{-- Fila  --}}
                <div class="col-md-6">

                    {{-- Producto --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label"><b>Producto</b></label>
                        <div class="col-md-8">
                            <input readonly type="text" class="form-control"
                                   value="{{ $producto->nombre }}">
                        </div>
                    </div>

                    {{-- Unidad de medida --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label">Unidad de medida</label>
                        <div class="col-md-8">
                            <input readonly type="text" class="form-control"
                                   value="{{ $producto->unidad_medida->nombre }}" id="unidadMedidalbl">
                        </div>
                    </div>

                </div>

                <div class="col-md-6">

                    @if(Auth::user()->rol->nombre == 'Administrador')
                    {{-- Costo--}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b>Costo</b></label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input readonly type="number" min="0.00" step="0.01" class="form-control" placeholder="0.00" name="costo"
                                       value="{{ $producto->costo }}" id="costo">
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Cantidad actual --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b>Cantidad existencia</b></label>
                        <div class="col-sm-8">
                            <input readonly type="number" class="form-control" placeholder="0.00" name="prueba"
                                   value="{{ $producto->cantidad_existencia }}" id="costo">
                        </div>
                    </div>

                </div>
                {{-- Fin fila --}}

                {{-- Fila --}}
                <div class="col-md-12">
                    {{-- Tabla de productos --}}
                    <table class="table table-bordered" id="precios-table">
                        <tr>
                            {{--<th style="width: 5%">#</th>--}}
                            <th style="width: 20%">Presentación</th>
                            <th style="width: 15%">Descripcion en factura</th>
                            <th style="width: 15%">Unidad Medida</th>
                            <th style="width: 15%">Precio sin IVA</th>
                            <th style="width: 15%">Precio con IVA</th>
                            <th style="width: 10%">Equivalente en Kg</th>
                            <th style="width: 5%">
                                @if(Auth::user()->rol->nombre == 'Administrador')
                                <button class="btn btn-success" id="btnNuevoProducto" onclick="NuevaFila()"
                                        type="button">
                                    <span class="fa fa-plus"></span>
                                </button>
                                @endif
                            </th>
                        </tr>

                        @php($i = 1)
                        @foreach($producto->precios as $precio)
                        <tr>
                            {{--<td style="vertical-align: middle">--}}
                                {{--{{$i}}--}}
                            {{--</td>--}}
                            <td>
                                <input type="text" tabindex="1" class="form-control" name="presentacion[]" value="{{$precio->presentacion}}">
                            </td>
                            <td>
                                <input type="text" tabindex="2" class="form-control" name="descripcion[]" placeholder="ej. Tarro de 250g" value="{{$precio->nombre_factura}}">
                            </td>
                            <td>
                                <select style="width: 100%" tabindex="3" class="form-control select2" name="unidad_medida_id[]">
                                    <option value="" selected disabled>Seleccione una opción</option>
                                    @foreach($unidad_medidas as $unidad_medida)
                                        @if($unidad_medida->id == $precio->unidad_medida_id)
                                            <option selected value="{{ $unidad_medida->id }}">{{ $unidad_medida->nombre }}
                                                - {{ $unidad_medida->abreviatura }}</option>
                                        @else
                                            <option value="{{ $unidad_medida->id }}">{{ $unidad_medida->nombre }}
                                                - {{ $unidad_medida->abreviatura }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="number" tabindex="4" min="0.00" step="any" class="form-control cantidadCls" name="precio[]"
                                           id="precio-id" value="{{$precio->precio}}">
                                </div>
                            </td>
                            <td>
                                @php($iva = \App\Configuracion::find(1)->iva)
                                @php($precio_iva = $precio->precio * $iva )
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input readonly type="number" min="0.00" step="any" class="form-control cant" name="precio_iva[]"
                                           id="precio-iva-input" value="{{ number_format($precio_iva,4) }}">
                                </div>
                            </td>
                            <td>
                                <input type="number" tabindex="5" min="0.00" step="any" class="form-control factor" name="factor[]"
                                       value="{{$precio->factor}}">
                            </td>
                            <td align="center">
                                @if( Auth::user()->rol->nombre == 'Administrador')
                                    {{--<button type="button" class="btn btn-danger" click="funcionEliminarProducto()" type="button"><span class="fa fa-remove"></span></button>--}}
                                    <button type="button" tabindex="6" class="btn btn-danger" data-toggle="modal"
                                            data-target="#modalEliminar"
                                            data-id="{{ $precio->id }}" data-ruta="producto">
                                        <span class="fa fa-remove"></span>
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @php($i++)
                        @endforeach
                    </table>

                </div>
                {{-- Fin fila --}}

            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('productoLista') }}" class="btn btn-lg btn-default"><span class="fa fa-mail-reply"></span> Regresar</a>
                @if(Auth::user()->rol->nombre == 'Administrador')
                <button type="submit" class="btn btn-lg btn-success pull-right" id="enviar-buttom"><span class="fa fa-floppy-o"></span> Guardar
                </button>
                @endif
            </div>
        </form>
    </div><!-- /.box -->

@endsection

@section('JSExtras')
    {{--Validacion--}}
    <script src="{{asset('/plugins/jquery-validation/dist/jquery.validate.js')}}"></script>
    <script src="{{asset('/plugins/jquery-validation/dist/additional-methods.min.js')}}"></script>
    {{-- Funcion para cargar mas filas de productos --}}
    <script>
        $(document).on('ready', Principal());

        function Principal() {
            $("body").on("click", ".btn-eliminar", funcionEliminarProducto);
            SelecionarValor();
            AgregarFuncion();
            EnviarForm();
            // $('#enviar-buttom-id').click(EnviarForm);

            $('#modalEliminar').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var nombreObj = button.data('objeto'); // Extract info from data-* attributes
                var precio_id = button.data('id');
                var ruta = '/producto/precio/' + precio_id;
                // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                var modal = $(this);
                // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
                modal.find('#mensaje01').text('Realmente desea eliminar el precio');
                modal.find('#mensaje02').text('Realmente desea eliminar el precio');
                modal.find('#myform').attr("action", ruta);
            });

        }

        function EnviarForm() {
            console.log('Voy aqui');
            $('#precios-form-id').validate({
                ignore: [],
                onfocusout: false,
                onkeyup: false,
                rules: {
                    "presentacion[]": {
                        required: true,
                    },
                    "unidad_medida_id[]": {
                        required: true,
                    },
                    "precio[]": {
                        required: true,
                    },
                    "factor[]": {
                        required: true,
                    }
                },
                messages: {
                    "presentacion[]": {
                        required: function () {
                            toastr.error('Por favor complete el campo presentación', 'Ups!');
                        },
                    },
                    "unidad_medida_id[]": {
                        required: function () {
                            toastr.error('Por favor seleccione una unidad de medida', 'Ups!');
                        },
                    },
                    "precio[]": {
                        required: function () {
                            toastr.error('Por favor complete el campo precio', 'Ups!');
                        },
                    },
                    "factor[]": {
                        required: function () {
                            toastr.error('Por favor complete el campo factor', 'Ups!');
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

        function NuevaFila() {
            let presentacion = $('#presentacion-input').clone();
            let descripcion = $('#descripcion-input').clone();
            let unidad_medida = $('#unidad-medida-select').clone();
            let precio = $('#precio-div').clone();
            let precio_iva = $('#precio-iva-div').clone();
            let factor = $('#factor-input').clone();
            let eliminar_boton = $('#eliminar-button').clone();
            $('#precios-table')
                .append
                (
                    $('<tr>')
                        .append
                        (
                            $('<td>')
                                .append
                                (
                                    presentacion
                                )
                        )
                        .append
                        (
                            $('<td>')
                                .append
                                (
                                    descripcion
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
                            $('<td>')
                                .append
                                (
                                    precio
                                )
                        )
                        .append
                        (
                            $('<td>')
                                .append
                                (
                                    precio_iva
                                )
                        )
                        .append
                        (
                            $('<td>')
                                .append
                                (
                                    factor
                                )
                        )
                        .append
                        (
                            $('<td>').attr('align', 'center')
                                .append
                                (
                                    eliminar_boton
                                )
                        )
                );
            $(".select2").select2();
            AgregarFuncion();
        }

        function SelecionarValor() {
            $(":input").click(function () {
                $(this).select();
            });
            $(".cant").focusout(function () {
                let numeroDato = ($(this).val().length === 0) ? 0 : parseFloat($(this).val());
                $(this).val(numeroDato.toFixed(4));
            });
            $(".factor").focusout(function () {
                let numeroDato = ($(this).val().length === 0) ? 0 : parseFloat($(this).val());
                $(this).val(numeroDato.toFixed(4));
            });
        }

        function funcionEliminarProducto() {
            $(this).parent().parent().remove();
            calcularTotal();
        }

        /**
         * Estado: Verificada
         */
        function AgregarFuncion() {
            $('.cantidadCls').each(
                function (index, value) {
                    $(this).change(CambioPrecio);
                    $(this).keyup(CambioPrecio);
                });
        }

        /**
         * Estado: Verificada y funcionando
         */
        function CambioPrecio() {
            let precio_input = $(this);
            let precio = precio_input.val();
            let iva = $('#iva-input').val();
            let precio_iva_input = precio_input.parent().parent().parent().find('#precio-iva-input');
            let precio_iva = precio * iva;
            precio_iva = parseFloat(precio_iva);
            precio_iva_input.val(precio_iva.toFixed(4));
        }

    </script>
    {{-- Fin de funcion para cargar mas filas de productos --}}

    @include('comun.select2Jses')
@endsection

