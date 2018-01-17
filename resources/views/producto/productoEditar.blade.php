@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Editar producto
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
@endsection

@section('contentheader_title')
    Editar producto: {{$producto->nombre}}
@endsection

@section('contentheader_description')

@endsection

@section('main-content')

    @include('partials.alertas')

    <!-- Form de nuevo producto -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Datos del producto</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" action="{{ route('productoEditarPut', ['id' => $producto->id]) }}" method="POST">
            {{ csrf_field() }}
            {{ method_field('PUT') }}
            <div class="box-body">
                <div class="col-md-6 col-sm-12">
                    <h4>Datos generales</h4>
                    <br>

                    {{-- Id del producto --}}
                    <input type="hidden" name="id" value="{{ $producto->id }}">

                    {{-- Codigo del producto --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Codigo del producto</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="codigo" value="{{$producto->codigo}}">
                        </div>
                    </div>

                    {{-- Nombre del producto --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b>Nombre del producto</b></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="nombre" value="{{ $producto->nombre }}">
                        </div>
                    </div>

                    {{-- Categoria --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b>Categoria</b></label>
                        <div class="col-sm-8">
                            <select class="form-control select2" name="categoria_id">
                                @foreach($categorias as $categoria)
                                    @if($categoria->id == $producto->categoria_id)
                                        <option selected value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                    @else
                                        <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Unidad de medida compra --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b>Unidad de medida</b></label>
                        <div class="col-sm-8">
                            <select class="form-control select2" name="unidad_medida_id">
                                @foreach($unidadMedidas as $unidadMedida)
                                    @if($unidadMedida->id == $producto->unidad_medida_id)
                                        <option selected value="{{ $unidadMedida->id }}">{{ $unidadMedida->nombre }} - {{ $unidadMedida->abreviatura }}</option>
                                    @else
                                        <option value="{{ $unidadMedida->id }}">{{ $unidadMedida->nombre }} - {{ $unidadMedida->abreviatura }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Tipo de producto --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b>Tipo del producto</b></label>
                        <div class="col-sm-8">
                            <select class="form-control select2" name="tipo_producto_id">
                                @foreach($tipoProductos as $tipoProducto)
                                    @if($tipoProducto->id == $producto->tipo_producto_id)
                                        <option selected value="{{ $tipoProducto->id }}">{{ $tipoProducto->nombre }}</option>
                                    @else
                                        <option value="{{ $tipoProducto->id }}">{{ $tipoProducto->nombre }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>
                <div class="col-md-6 col-sm-12">
                    <h4>Control de inventario</h4>
                    <br>

                    {{-- Cantidad minima --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Cantidad minima</label>
                        <div class="col-sm-8">
                            <input type="number" min="0" class="form-control" name="existenciaMin"
                                   value="{{ $producto->existenciaMin }}">
                        </div>
                    </div>

                    {{-- Cantidad maxima --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Cantidad maxima</label>
                        <div class="col-sm-8">
                            <input type="number" min="0" class="form-control" name="existenciaMax"
                                   value="{{ $producto->existenciaMax }}">
                        </div>
                    </div>

                    {{-- Costo--}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Costo</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                            <input type="number" min="0.00" step="0.01" class="form-control" placeholder="0.00" name="costo"
                                   value="{{ $producto->costo }}" id="costo" disabled>
                            </div>
                        </div>
                    </div>

                    {{-- Precio --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Precio venta</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                            <input type="number" min="0.00" step="0.01" class="form-control" placeholder="0.00" name="precio"
                                   value="{{ $producto->precio }}" id="precio" onchange="cambioPrecio()">
                            </div>
                        </div>
                    </div>

                    {{-- Margen ganancia --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Margen ganancia</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                            <input type="number" min="0.00" step="0.01" class="form-control" placeholder="10%" name="margenGanancia"
                                value="{{ $producto->margenGanancia }}" id="margenGanancia" onchange="cambioMargen()">
                                <span class="input-group-addon">%</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('productoLista') }}" class="btn btn-lg btn-default"><span class="fa fa-close"></span> Cancelar</a>
                <button type="submit" class="btn btn-lg btn-success pull-right"><span class="fa fa-floppy-o"></span> Guardar</button>
            </div>
        </form>
    </div><!-- /.box -->

@endsection

@section('JSExtras')
    <script>
        function cambioPrecio() {
            var costo = $('#costo').val();
            var precio = $('#precio').val();
            if ($('#costo').val().length <= 0)
            {
                alert("Debe rellenar el campo costo antes de asignar precios");
                $('#precio').val('');
            }
            margen = ((precio - costo) / costo) * 100;
            $('#margenGanancia').val(margen.toFixed(2));
        }

        function cambioMargen() {
            var costo = $('#costo').val();
            var margen = $('#margenGanancia').val();
            if ($('#costo').val().length <= 0)
            {
                alert("Debe rellenar el campo costo antes de asignar precios");
                $('#margenGanancia').val('');
            }
            precio = costo * (1 + (margen/100));
            $('#precio').val(precio.toFixed(2));
        }
    </script>
    @include('comun.select2Jses')
@endsection
