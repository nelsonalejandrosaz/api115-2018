@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Ver producción
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
@endsection

@section('contentheader_title')
    Ver producción
@endsection

@section('contentheader_description')
    -- Detalles de la producción
@endsection

@section('main-content')

    @include('partials.alertas')

    <!-- Form de nuevo producto -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Datos del producto</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal">
            {{ csrf_field() }}
            <div class="box-body">
                <div class="col-md-6 col-sm-12">
                    <h4>Formulas</h4>
                    <br>

                    {{-- Fecha --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label"><b>Fecha ingreso:</b></label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="date" class="form-control" name="fecha" value="{{$produccion->fecha}}"
                                       disabled>
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Producto --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b>Producto</b></label>
                        <div class="col-sm-8">
                            <select class="form-control select2" name="formula_id" disabled>
                                @foreach($formulas as $formula)
                                    @if($formula->id == $produccion->formula_id)
                                        <option selected
                                                value="{{ $formula->id }}">{{ $formula->producto->nombre }}</option>
                                    @else
                                        <option value="{{ $formula->id }}">{{ $formula->producto->nombre }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Nombre del producto --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b>Realizado por</b></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="Producto" name="nombre"
                                   value="{{$produccion->bodeguero->nombre}} {{$produccion->bodeguero->apellido}}"
                                   disabled>
                        </div>
                    </div>


                </div>
                <div class="col-md-6 col-sm-12">
                    <h4>Cantidad y detalle</h4>
                    <br>

                    {{-- Cantidad produccion --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Cantidad producida</label>
                        <div class="col-sm-8">
                            <input disabled type="number" min="0.00" class="form-control" placeholder="0" name="cantidad"
                                   value="{{$produccion->cantidad}}">
                        </div>
                    </div>

                    {{-- Detalle --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Detalle</label>
                        <div class="col-sm-8">
                            <textarea disabled name="detalle" class="form-control" rows="5">{{$produccion->detalle}}</textarea>
                        </div>
                    </div>

                </div>

                {{-- Fila --}}
                <div class="col-md-12">
                    {{-- Tabla de productos --}}
                    <table class="table table-bordered" id="tblProductos">
                        <tr>
                            <th style="width:80%">Producto </th>
                            <th style="width:20%">Cantidad</th>
                        </tr>
                        @foreach($produccion->salidas as $salida)
                            <tr>
                                {{--Productos--}}
                                <td>
                                    <select style="width:100%" class="form-control select2 selProd" name="productos_id[]"
                                            id="selectProductos" disabled>
                                        @foreach($productos as $producto)
                                            @if($producto->id == $salida->movimiento->producto_id)
                                                <option selected value="{{ $producto->id }}"
                                                        data-cu="{{ $producto->precio }}"
                                                        data-um="{{$producto->unidadMedida->abreviatura}}">{{$producto->codigo}} -- {{ $producto->nombre }}
                                                </option>
                                            @else
                                                <option value="{{ $producto->id }}" data-cu="{{ $producto->precio }}"
                                                        data-um="{{$producto->unidadMedida->abreviatura}}">{{$producto->codigo}} -- {{ $producto->nombre }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </td>
                                {{--Cantidad--}}
                                <td>
                                    <input type="text" class="form-control cantidadCls"
                                           pattern="^[+-]?[0-9]{1,3}(?:,?[0-9]{3})*(?:\.[0-9]{2})?$" name="cantidades[]"
                                           id="cantidad" value="{{$salida->cantidad}}" disabled>
                                </td>
                            </tr>
                        @endforeach
                    </table>

                </div>

            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('produccionLista') }}" class="btn btn-lg btn-default">Cancelar</a>
                <button type="submit" class="btn btn-lg btn-success pull-right">Producir</button>
            </div>
        </form>
    </div><!-- /.box -->

@endsection

@section('JSExtras')
    <script>
        function cambioPrecio() {
            var costo = $('#costo').val();
            var precio = $('#precio').val();
            if ($('#costo').val().length <= 0) {
                alert("Debe rellenar el campo costo antes de asignar precios");
                $('#precio').val('');
            }
            margen = ((precio - costo) / costo) * 100;
            $('#margenGanancia').val(margen.toFixed(2));
        }

        function cambioMargen() {
            var costo = $('#costo').val();
            var margen = $('#margenGanancia').val();
            if ($('#costo').val().length <= 0) {
                alert("Debe rellenar el campo costo antes de asignar precios");
                $('#margenGanancia').val('');
            }
            precio = costo * (1 + (margen / 100));
            $('#precio').val(precio.toFixed(2));
        }
    </script>
    @include('comun.select2Jses')
@endsection
