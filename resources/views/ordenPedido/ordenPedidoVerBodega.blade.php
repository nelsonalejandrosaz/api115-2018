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
        <form class="form-horizontal" action="{{ route('ordenPedidoBodegaPost',['id' => $ordenPedido->id]) }}" method="POST" id="venta-form-id">
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
                            <th style="width:50%">Producto (Presentación)</th>
                            <th style="width:15%">Unidad medida</th>
                            <th style="width:15%">Cantidad orden pedido</th>
                            <th style="width:20%">Cantidad a descargar</th>
                        </tr>
                        @foreach($ordenPedido->salidas as $salida)
                            <tr>
                                {{--Productos--}}
                                <td>
                                    <input readonly type="text" class="form-control" name="" value="{{ $salida->movimiento->producto()->withTrashed()->first()->nombre }} {{ $salida->descripcion_presentacion }}">
                                </td>
                                {{--Unidad de medida--}}
                                <td>
                                    <input readonly type="text" class="form-control unidadCls" name="" id="unidadMedida" value="{{$salida->unidad_medida->abreviatura}}">
                                </td>
                                {{--Cantidad--}}
                                <td>
                                    <input readonly type="text" class="form-control cantidadCls"
                                           pattern="^[+-]?[0-9]{1,3}(?:,?[0-9]{3})*(?:\.[0-9]{2})?$" name="cantidades[]"
                                           id="cantidad" value="{{$salida->cantidad}}">
                                </td>
                                {{--Cantidad a sacar--}}
                                <td>
                                    <input readonly type="text" class="form-control cantidadCls"
                                           pattern="^[+-]?[0-9]{1,3}(?:,?[0-9]{3})*(?:\.[0-9]{2})?$" name="cantidades[]"
                                           id="cantidad" value="{{$salida->movimiento->cantidad}} {{ $salida->movimiento->producto()->withTrashed()->first()->unidad_medida->abreviatura }}">
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
                    <button type="button" class="btn btn-lg btn-success pull-right" id="enviar-buttom-id"><span class="fa fa-check-square-o"></span> Despachar orden pedido</button>
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
            $('#enviar-buttom-id').click(EnviarOrdenPedido);
        }

        function EnviarOrdenPedido() {
            $('#enviar-buttom-id').attr('disabled', 'true');
            $('#venta-form-id').submit();
        }


    </script>
    @include('comun.select2Jses')
@endsection

