@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Ver compra
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
@endsection

@section('contentheader_title')
    Ver compra n° {{$compra->numero}}
@endsection

@section('contentheader_description')
    -- Detalle de compra ingresada al inventario
@endsection

@section('main-content')

    @include('partials.alertas')

    <!-- Form de nuevo proveedor -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Detalle de factura</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" action="{{ route('compraProcesar',['id' => $compra->id]) }}" method="POST">
            {{ csrf_field() }}
            <div class="box-body">

                {{-- Fila  --}}
                <div class="col-md-6">

                    {{-- Fecha --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label"><b>Fecha ingreso:</b></label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input disabled type="date" class="form-control" name="fechaIngreso"
                                       value="{{$compra->fecha}}">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Proveedor --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label"><b>Proveedor:</b></label>
                        <div class="col-md-8">
                            <select disabled class="form-control select2" style="width: 100%" name="proveedor_id">
                                <option value="" disabled selected>Seleccione un proveedor</option>
                                @foreach($proveedores as $proveedor)
                                    @if($proveedor->id == $compra->proveedor_id)
                                        <option selected value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                                    @else
                                        <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{--Detalle--}}
                    <div class="form-group">
                        <label class="col-md-4 control-label"><b>Detalle:</b></label>
                        <div class="col-md-8">
                            <textarea disabled class="form-control" name="detalle">{{$compra->detalle}}</textarea>
                        </div>
                    </div>

                </div>

                {{-- Fila  --}}
                <div class="col-md-6">

                    {{-- Codigo factura --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label">Factura n°:</label>
                        <div class="col-md-8">
                            <input disabled type="text" class="form-control" name="numero" value="{{$compra->numero}}">
                        </div>
                    </div>

                    {{-- Ingresado por --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label">Ingresado por:</label>
                        <div class="col-md-8">
                            <input disabled type="text" class="form-control" name="ingresadoPor" value="{{$compra->ingresado->nombre}} {{$compra->ingresado->apellido}}">
                        </div>
                    </div>

                    {{-- Ruta archivo --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label"><b>Copia factura:</b></label>
                        <div class="col-md-8">
                            {{--<input type="file" class="form-control" name="rutaArchivo">--}}
                            <a class="btn btn-lg btn-default" target="_blank" href="{{Storage::url($compra->ruta_archivo)}}">Ver archivo</a>
                        </div>
                    </div>

                </div>

                {{-- Fila --}}
                <div class="col-md-12">
                    {{-- Tabla de productos --}}
                    <table class="table table-bordered" id="tblProductos">
                        <tr>
                            <th style="width: 50%">Producto</th>
                            <th style="width: 10%">Unidad medida</th>
                            <th style="width: 10%">Cantidad</th>
                            <th style="width: 15%">Costo unitario</th>
                            <th style="width: 15%">Costo total</th>
                        </tr>
                        @php($i = 1)
                        @foreach($compra->entradas as $entrada)
                            <tr id="base">

                                {{--producto--}}
                                <td>
                                    <select disabled class="form-control select2 selProd" style="width: 100%"
                                            name="productos_id[]"
                                            id="selectProductos">
                                        <option value="" disabled selected>Seleccione un producto</option>
                                        @foreach($productos as $producto)
                                            @if($producto->id == $entrada->movimiento->producto_id)
                                                <option selected value="{{ $producto->id }}"
                                                        data-um="{{ $producto->unidad_medida->abreviatura }}">{{$producto->codigo}} -- {{ $producto->nombre }}</option>
                                            @else
                                                <option value="{{ $producto->id }}"
                                                        data-um="{{ $producto->unidad_medida->abreviatura }}">{{ $producto->nombre }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </td>
                                {{--unidad medida--}}
                                <td>
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="{{$entrada->movimiento->producto->unidad_medida->abreviatura}}" id="unidadMedidaLbl" disabled>
                                    </div>
                                </td>
                                {{--cantidad--}}
                                <td>
                                    <div class="input-group">
                                        <input class="form-control cantidadCls" type="number" value="{{$entrada->cantidad}}" name="cantidades[]" id="cantidad" disabled>
                                    </div>
                                </td>
                                {{--costo unitario --}}
                                <td>
                                    <div class="input-group">
                                        <span class="input-group-addon">$</span>
                                        <input type="number" step="0.01" class="form-control costoUnitarioCls" min="0.01" value="{{number_format($entrada->costo_unitario,2)}}" name="costoUnitarios[]" id="costoUnitario" disabled>
                                    </div>
                                </td>
                                {{--costo total --}}
                                <td>
                                    <div class="input-group">
                                        <span class="input-group-addon">$</span>
                                        <input type="number" step="0.01" class="form-control costoTotalCls" min="0.01" value="{{number_format($entrada->costo_total,2)}}" name="costoTotales[]" id="costoTotal" disabled>
                                    </div>
                                </td>
                            </tr>
                            @php($i++)
                        @endforeach
                    </table>

                    @php($compra_iva = $compra->compra_total * \App\Configuracion::find(1)->iva)
                    <table class="table table-bordered">
                        <tr>
                            <th style="width:70%"></th>
                            <th style="width:15%">Compra Total</th>
                            <th style="width:15%">
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="number" class="form-control" value="{{number_format($compra->compra_total,2)}}" name="compraTotal" id="compraTotal" disabled>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <th style="width:65%"></th>
                            <th style="width:15%">Compra Total + IVA</th>
                            <th style="width:15%">
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input readonly type="number" class="form-control" value="{{number_format($compra_iva,2)}}" name="compra-total-iva"
                                           id="compra-total-iva-id">
                                </div>
                            </th>
                        </tr>
                    </table>

                </div>

            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('compraLista') }}" class="btn btn-lg btn-default"><span class="fa fa-mail-reply"></span> Regresar</a>
                @if($compra->estado_compra_id == \App\EstadoCompra::whereCodigo('INGRE')->first()->id)
                    <button type="submit" class="btn btn-lg btn-success pull-right"><span class="fa fa-check-square-o"></span>
                        Procesar
                    </button>
                @endif
            </div>
        </form>
    </div><!-- /.box -->

@endsection

@section('JSExtras')
    <script src="{{ asset('/js/compra-nueva.js') }}"></script>
    @include('comun.select2Jses');
@endsection

