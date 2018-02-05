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

    {{-- div para error de suma de porcentaje --}}
    <div id="divErrorSuma" hidden class="alert alert-danger alert-dismissable">
        {{-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> --}}
        <h4><i class="icon fa fa-ban"></i> Error!</h4>
        La suma debe sumar 100%
    </div>

    <!-- Form de nuevo proveedor -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Precios asignados</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form id="formDatos" class="form-horizontal" action="{{ route('productoPrecioPost',['id' => $producto->id]) }}" method="POST">
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
                            <input readonly type="number" min="0.00" step="0.01" class="form-control" placeholder="0.00" name="costo"
                                   value="{{ $producto->cantidad_existencia }}" id="costo">
                        </div>
                    </div>

                </div>
                {{-- Fin fila --}}

                {{--div escondido--}}
                <div class="hidden">
                    <select style="width: 100%" class="form-control select2" name="unidad_medida_id[]" id="selectUM">
                        <option value="" selected disabled>Seleccione una opción</option>
                        @foreach($unidad_medidas as $unidad_medida)
                            <option value="{{ $unidad_medida->id }}">{{ $unidad_medida->nombre }}
                                - {{ $unidad_medida->abreviatura }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Fila --}}
                <div class="col-md-12">
                    {{-- Tabla de productos --}}
                    <table class="table table-bordered" id="tblProductos">
                        <tr>
                            <th style="width: 7%">#</th>
                            <th style="width: 25%">Presentación</th>
                            <th style="width: 15%">Unidad Medida</th>
                            <th style="width: 15%">Precio</th>
                            <th style="width: 15%">Factor</th>
                            <th style="width: 5%">
                                @if(Auth::user()->rol->nombre == 'Administrador')
                                <button class="btn btn-success" id="btnNuevoProducto" onclick="funcionNuevoProducto()"
                                        type="button">
                                    <span class="fa fa-plus"></span>
                                </button>
                                @endif
                            </th>
                        </tr>

                        @php($i = 1)
                        @foreach($producto->precios as $precio)
                        <tr>
                            <td style="vertical-align: middle">
                                Precio {{$i}}
                            </td>
                            <td>
                                <input type="text" class="form-control" name="presentacion[]" value="{{$precio->presentacion}}">
                            </td>
                            <td>
                                <select style="width: 100%" class="form-control select2" name="unidad_medida_id[]">
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
                                    <input required type="number" min="0.00" step="any" class="form-control cant" name="precio[]"
                                           value="{{$precio->precio}}">
                                </div>
                            </td>
                            <td>
                                <input required type="number" min="0.00" step="any" class="form-control factor" name="factor[]"
                                       value="{{$precio->factor}}">
                            </td>
                            <td align="center">
                                @if( Auth::user()->rol->nombre == 'Administrador')
                                    {{--<button type="button" class="btn btn-danger" click="funcionEliminarProducto()" type="button"><span class="fa fa-remove"></span></button>--}}
                                    <button type="button" class="btn btn-danger" data-toggle="modal"
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
                <button type="button" onclick="verificarSuma()" class="btn btn-lg btn-success pull-right"><span class="fa fa-floppy-o"></span> Guardar
                </button>
                @endif
            </div>
        </form>
    </div><!-- /.box -->

@endsection

@section('JSExtras')
    {{-- Funcion para cargar mas filas de productos --}}
    <script>
        $(document).on('ready', funcionPrincipal());

        function funcionPrincipal() {
//            $("body").on("click", ".btn-danger", funcionEliminarProducto);
            selecionarValor();
            calcularTotal();

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

        function selecionarValor() {
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

        var numero = {{$i}};

        function funcionNuevoProducto() {
            copia = $('#selectUM').clone(false);
            $('#tblProductos')
                .append
                (
                    $('<tr>').attr('id', 'rowProducto' + numero)
                        .append
                        (
                            $('<td>').attr('style','vertical-align: middle')
                                .append
                                (
                                    'Precio ' + numero
                                )
                        )
                        .append
                        (
                            $('<td>')
                                .append
                                (
                                    '<input type="text" class="form-control" name="presentacion[]" placeholder="ej. Tarro de 1/2 Kg">'
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
                                    '<div class="input-group">\n' +
                                    '<span class="input-group-addon">$</span>\n' +
                                    '<input required type="number" min="0.00" step="0.01" class="form-control cant" placeholder="0.00" name="precio[]"\n' +
                                    '   value="">\n' +
                                    '</div>'
                                )
                        )
                        .append
                        (
                            $('<td>')
                                .append
                                (
                                    '<input required type="number" min="0.00" step="0.001" class="form-control cant" placeholder="0.00" name="factor[]"\n' +
                                    'value="">'
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
            $(this).parent().parent().remove();
            calcularTotal();
        }

        function verificarSuma() {
                $('#formDatos').submit();
        }

        function calcularTotal() {
            var totalPorcentaje = 0;
            var porcentajes = $('.cant');
            for (var i = 0; i < porcentajes.length; i++) {
                porcentaje = parseFloat(porcentajes[i].value);
                totalPorcentaje = totalPorcentaje + porcentaje;
            }
            $('#totalPorcentajeInput').val(totalPorcentaje.toFixed(3));
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

