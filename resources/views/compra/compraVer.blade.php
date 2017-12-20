@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Ver compra
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
@endsection

@section('contentheader_title')
    Ver compra
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
        <form class="form-horizontal" action="" method="">
            {{ csrf_field() }}
            <div class="box-body">

                {{-- Fila  --}}
                <div class="col-md-6">

                    {{-- Fecha --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label"><b>Fecha ingreso:</b></label>
                        <div class="col-md-8">
                            <input disabled type="date" class="form-control" name="fechaIngreso"
                                   value="{{$compra->fechaIngreso}}">
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
                            <input disabled type="text" class="form-control" name="ingresadoPor" value="{{$compra->ingresadoPor->nombre}} {{$compra->ingresadoPor->apellido}}">
                        </div>
                    </div>

                    {{-- Ruta archivo --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label"><b>Copia factura:</b></label>
                        <div class="col-md-8">
                            {{--<input type="file" class="form-control" name="rutaArchivo">--}}
                            <a class="btn btn-lg btn-default" target="_blank" href="{{Storage::url($compra->rutaArchivo)}}">Ver archivo</a>
                        </div>
                    </div>

                </div>

                {{-- Fila --}}
                <div class="col-md-12">
                    {{-- Tabla de productos --}}
                    <table class="table table-bordered" id="tblProductos">
                        <tr>
                            <td>#</td>
                            <th style="width: 45%">Producto</th>
                            <th style="width: 20%">Cantidad</th>
                            <th style="width: 20%">Precio</th>
                        </tr>
                        @php($i = 1)
                        @foreach($compra->movimientos as $movimiento)
                            <tr id="base">
                                <td>
                                    {{$i}}
                                </td>
                                <td>
                                    <select disabled class="form-control select2 selProd" style="width: 100%"
                                            name="productos_id[]"
                                            id="selectProductos">
                                        <option value="" disabled selected>Seleccione un producto</option>
                                        @foreach($productos as $producto)
                                            @if($producto->id == $movimiento->producto_id)
                                                <option selected value="{{ $producto->id }}"
                                                        data-um="{{ $producto->unidadMedida->abreviatura }}">{{ $producto->nombre }}</option>
                                            @else
                                                <option value="{{ $producto->id }}"
                                                        data-um="{{ $producto->unidadMedida->abreviatura }}">{{ $producto->nombre }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    {{-- <input type="number" class="form-control" placeholder="100" name="cantidades[]" required> --}}
                                    <div class="input-group">
                                        <input disabled class="form-control" type="number" name="cantidades[]"
                                               required value="{{$movimiento->cantidadMovimiento}}">
                                        <span class="input-group-addon unimed" id="spamUM">---</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <span class="input-group-addon">$</span>
                                        <input disabled type="number" class="form-control"
                                               name="valoresTotales[]"
                                               required value="{{$movimiento->valorTotalMovimiento}}">
                                    </div>
                                </td>
                            </tr>
                            @php($i++)
                        @endforeach
                    </table>
                </div>

            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('compraLista') }}" class="btn btn-lg btn-default">Regresar</a>
            </div>
        </form>
    </div><!-- /.box -->

@endsection

@section('JSExtras')
    <script src="{{ asset('/js/compra-nueva.js') }}"></script>
    @include('comun.select2Jses');
@endsection

