@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Orden de pedido
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
    {{-- DataPicker --}}
    <link rel="stylesheet" href="{{asset('/css/datepicker.css')}}">
@endsection

@section('contentheader_title')
    Orden de pedido n° {{$ordenPedido->numero}}
@endsection

@section('contentheader_description')

@endsection

@section('main-content')

    @include('partials.alertas')

    <!-- Form de nuevo proveedor -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Detalle</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" action="{{ route('ordenPedidoBodegaPost',['id' => $ordenPedido->id]) }}" method="POST" id="orden-form">
            {{ csrf_field() }}
            {{ method_field('PUT') }}
            <div class="box-body">
                {{-- Cabecera --}}
                <div class="col-md-6 col-sm-12">

                    {{-- Fecha ingreso --}}
                    <div class="form-group">
                        <label class="col-md-4  control-label"><b>Fecha venta</b></label>
                        <div class="col-md-8 ">
                            <div class="input-group">
                                <input readonly type="date" class="form-control" name="fechaIngreso"
                                       value="{{$ordenPedido->fecha->format('Y-m-d')}}">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Fecha entrega --}}
                    <div class="form-group">
                        <label class="col-md-4  control-label">Fecha entrega</label>
                        <div class="col-md-8 ">
                            @if($ordenPedido->fecha_entrega != null)
                                <div class="input-group">
                                    <input readonly type="date" class="form-control" name="fechaEntrega"
                                           value="{{$ordenPedido->fecha_entrega->format('Y-m-d')}}">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            @else
                                <input readonly type="text" class="form-control" name="fechaEntrega"
                                       value="Sin fecha especificada">
                            @endif
                        </div>
                    </div>

                    {{-- Numero Orden Pedido --}}
                    <div class="form-group">
                        <label class="col-md-4  control-label">Fecha hora enviado:</label>
                        <div class="col-md-8 ">
                            <input readonly type="text" class="form-control" name="numero" value="{{$ordenPedido->created_at->format('d/m/Y h:i:s A')}}"
                            >
                        </div>
                    </div>

                </div>

                <div class="col-md-6 col-sm-12">

                    {{-- Numero Orden Pedido --}}
                    <div class="form-group">
                        <label class="col-md-4  control-label">Orden venta n°:</label>
                        <div class="col-md-8 ">
                            <input readonly type="text" class="form-control" name="numero" value="{{$ordenPedido->numero}}"
                            >
                        </div>
                    </div>

                    {{-- Despachado por --}}
                    <div class="form-group">
                        <label class="col-md-4  control-label"><b>Vendedor</b></label>
                        <div class="col-md-8 ">
                            <input readonly type="text" class="form-control"
                                   value="{{$ordenPedido->vendedor->nombre}} {{$ordenPedido->vendedor->apellido}}"
                                   name="despachadoPor">
                        </div>
                    </div>


                </div>

                {{-- Fila --}}
                <div class="col-md-12">
                    {{-- Tabla de productos --}}
                    <table class="table table-bordered" id="tblProductos">
                        <tr>
                            <th style="width:40%">Producto (Presentación)</th>
                            <th style="width:15%">Unidad medida</th>
                            <th style="width:15%">Cantidad orden pedido</th>
                            <th style="width:20%">Cantidad a descargar</th>
                            <th style="width:10%">Estado</th>
                        </tr>
                        @foreach($ordenPedido->salidas as $salida)
                            <tr>
                                {{--Productos--}}
                                <td>
                                    @if($salida->descripcion_presentacion != null)
                                        <input readonly type="text" class="form-control" name="" value="{{ $salida->movimiento->producto()->withTrashed()->first()->nombre }} ({{ $salida->descripcion_presentacion }})">
                                    @else
                                        <input readonly type="text" class="form-control" name="" value="{{ $salida->movimiento->producto()->withTrashed()->first()->nombre }}">
                                    @endif
                                </td>
                                {{--Unidad de medida--}}
                                <td>
                                    <input readonly type="text" class="form-control unidadCls" name="" id="unidadMedida" value="{{$salida->unidad_medida->abreviatura}}">
                                </td>
                                {{--Cantidad--}}
                                <td>
                                    <input readonly type="text" class="form-control cantidadCls"
                                           value="{{$salida->cantidad}}">
                                </td>
                                {{--Cantidad a sacar--}}
                                <td>
                                    <div class="input-group">
                                        <input readonly type="number" class="form-control cantidad" name="cantidades[]" value="{{$salida->movimiento->cantidad}}">
                                        <span class="input-group-addon">Kg</span>
                                    </div>
                                </td>
                                {{--Estado--}}
                                <td>
                                    @if(round($salida->movimiento->cantidad,4) > round($salida->movimiento->producto->cantidad_existencia,4))
                                        <span class="label label-warning" title="Cantidad en inventario: {{number_format($salida->movimiento->producto->cantidad_existencia,4)}} Kgs">Insuficiente</span>
                                    @else
                                        <span class="label label-success" title="Cantidad en inventario: {{number_format($salida->movimiento->producto->cantidad_existencia,4)}} Kgs">Suficiente</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>

                </div>
            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('ordenPedidoListaBodega') }}" class="btn btn-lg btn-default"><span class="fa fa-mail-reply"></span> Regresar a lista</a>
                {{--<a href="{{ route('ordenPedidoPDF',['id' => $ordenPedido->id]) }}" class="btn btn-lg btn-success pull-right">Procesar orden pedido</a>--}}
                @if($ordenPedido->estado_orden->codigo == 'SP')
                    <button type="submit" class="btn btn-lg btn-success pull-right" style="margin-left: 5px" id="enviar-buttom"><span class="fa fa-check-square-o"></span> Despachar orden pedido</button>
                    <button type="button" class="btn btn-lg btn-warning pull-right" id="editar-buttom"><span class="fa fa-edit"></span> Editar cantidades</button>
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
        $(document).on('ready', funcionPrincipal());

        function funcionPrincipal() {
            Validacion();
            $('#editar-buttom').click(EditarCantidades);
        }
        
        function Validacion() {
            $('#orden-form').validate({
                ignore: [],
                onfocusout: false,
                onkeyup: false,
                rules: {
                    "cantidades[]": {
                        required: true,
                        min: 0.001,
                    },
                },
                messages: {
                    "cantidades[]": {
                        required: function () {
                            toastr.error('Por favor complete la cantidad a descargar', 'Ups!');
                        },
                        min: function () {
                            toastr.error('La cantidad a descargar debe ser mayor a cero', 'Ups!');
                        },
                    },
                },
                submitHandler: function (form) {
                    $('#enviar-buttom').attr('disabled','true');
                    toastr.success('Por favor espere a que se procese','Excelente!!');
                    form.submit();
                }
            });
        }
        
        function EditarCantidades() {
            $('.cantidad').removeAttr('readonly');
            toastr.info('Ahora puede editar las cantidades a despachar','Perfecto!!');
        }

        function EnviarOrdenPedido() {
            $('#enviar-buttom-id').attr('disabled', 'true');
            $('#venta-form-id').submit();
        }


    </script>
    @include('comun.select2Jses')
@endsection

