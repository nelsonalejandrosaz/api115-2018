@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Anular documento
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
@endsection

@section('contentheader_title')
    Anular documento
@endsection

@section('contentheader_description')
    -- Anular documento
@endsection

@section('main-content')

    @include('partials.alertas')

    <!-- Form de nuevo proveedor -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Detalle del documento anulado</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" action="{{ route('ventaSinOrdenAnuladaNuevaPost') }}" method="POST" id="anular-form">
            {{ csrf_field() }}
            <div class="box-body">
                {{-- Cabecera --}}
                <div class="col-md-6 col-sm-12">

                    <h4>Información del documento</h4>
                    <br>

                    {{-- Fecha ingreso --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label"><b>Fecha venta</b></label>
                        <div class="col-md-9 ">
                            <input type="date" class="form-control" name="fecha"
                                   value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" title="Fecha de documento">
                        </div>
                    </div>

                    {{-- Cliente --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label"><b>Cliente</b></label>
                        <div class="col-md-9 ">
                            <select class="form-control select2" style="width: 100%" name="cliente_id" id="clienteID" title="Cliente documento">
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

                </div>

                <div class="col-md-6 col-sm-12">

                    <h4><br></h4>
                    <br>

                    {{-- Numero documento venta --}}
                    <div class="form-group">
                        <label class="col-md-4  control-label"><b>N° Documento</b></label>
                        <div class="col-md-8 ">
                            <input type="text" class="form-control" name="numero"
                                   placeholder="Numero factura o Crédito Fiscal" value="" title="Numero de documento">
                        </div>
                    </div>

                    {{-- Tipo Documento --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b>Tipo de documento</b></label>
                        <div class="col-sm-8">
                            <select class="form-control select2" name="tipo_documento_id" title="Tipo de documento">
                                <option selected disabled>Seleccione una opción</option>
                                @foreach($tipoDocumentos as $tipoDocumento)
                                    <option value="{{ $tipoDocumento->id }}">{{ $tipoDocumento->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Anulada por --}}
                    <div class="form-group">
                        <label class="col-md-4  control-label"><b>Anulada por</b></label>
                        <div class="col-md-8 ">
                            <input readonly type="text" class="form-control"
                                   value="{{ Auth::user()->nombre }} {{ Auth::user()->apellido }}"
                                   name="despachadoPor" title="Documento anulado por">
                        </div>
                    </div>

                </div>

            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('ventaLista',['tipo' => 'todo']) }}" class="btn btn-lg btn-default"><span
                            class="fa fa-close"></span> Cancelar</a>
                <button type="submit" class="btn btn-lg btn-danger pull-right"><span class="fa fa-minus-square"></span> Anular documento
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
            Validacion();
        }

        function Validacion() {
            $('#anular-form').validate({
                ignore: [],
                onfocusout: false,
                onkeyup: false,
                rules: {
                    "fecha": {
                        required: true,
                    },
                    "cliente_id": {
                        required: true,
                    },
                    "numero": {
                        required: true,
                    },
                    "tipo_documento_id": {
                        required: true,
                    },
                },
                messages: {
                    "fecha": {
                        required: function () {
                            toastr.error('Por favor digite la fecha', 'Ups!');
                        },
                    },
                    "cliente_id": {
                        required: function () {
                            toastr.error('Por favor seleccione el cliente', 'Ups!');
                        },
                    },
                    "numero": {
                        required: function () {
                            toastr.error('Por favor seleccione el tipo de documento', 'Ups!');
                        },
                    },
                    "tipo_documento_id": {
                        required: function () {
                            toastr.error('Por favor complete la fecha de entrega', 'Ups!');
                        },
                    },
                },
                submitHandler: function (form) {
                    $('#enviar-buttom').attr('disabled', 'true');
                    toastr.success('Por favor espere a que se procese', 'Excelente');
                    form.submit();
                }
            });
        }

    </script>
@endsection

