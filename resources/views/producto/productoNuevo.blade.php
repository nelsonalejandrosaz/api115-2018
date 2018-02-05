@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Nuevo Producto
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
@endsection

@section('contentheader_title')
    Nuevo Producto
@endsection

@section('contentheader_description')
    -- Ingresar un nuevo producto
@endsection

@section('main-content')

    @include('partials.alertas')

    <!-- Form de nuevo producto -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Datos del producto</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" action="{{ route('productoNuevoPost') }}" method="POST">
            {{ csrf_field() }}
            <div class="box-body">
                <div class="col-md-6 col-sm-12">
                    <h4>Datos generales</h4>
                    <br>

                    {{-- Nombre del producto --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b>Nombre del producto</b></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="ej. Producto A" name="nombre"
                                   value="{{ old('nombre') }}">
                        </div>
                    </div>

                    {{-- Nombre del alternativo --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Nombre alternativo</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="ej. Producto Z" name="nombre_alternativo"
                                   value="{{ old('nombre_alternativo') }}">
                        </div>
                    </div>

                    {{-- Categoria --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b>Categoria</b></label>
                        <div class="col-sm-8">
                            <select class="form-control select2" name="categoria_id">
                                <option value="" selected disabled>Seleccione una opción</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Unidad de medida prederteminada --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b>Unidad medida </b></label>
                        <div class="col-sm-8">
                            <select  class="form-control select2" name="unidad_medida_id">
                                @foreach($unidad_medidas as $unidad_medida)
                                    @if($unidad_medida->abreviatura == 'Kg')
                                        <option selected value="{{ $unidad_medida->id }}">{{ $unidad_medida->nombre }}
                                            - {{ $unidad_medida->abreviatura }}</option>
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
                                <option value="" selected disabled>Seleccione una opción</option>
                                @foreach($tipo_productos as $tipo_producto)
                                    <option value="{{ $tipo_producto->id }}">{{ $tipo_producto->nombre }}</option>
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
                        <label class="col-sm-4 control-label">Cantidad mínima stock</label>
                        <div class="col-sm-8">
                            <input type="number" min="0.00" class="form-control" placeholder="ej. 0" name="existencia_min"
                                   value="{{ old('existencia_min') }}">
                        </div>
                    </div>

                    {{-- Cantidad maxima --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Cantidad máxima stock</label>
                        <div class="col-sm-8">
                            <input type="number" min="0.00" class="form-control" placeholder="ej. 500" name="existencia_max"
                                   value="{{ old('existencia_max') }}">
                        </div>
                    </div>

                    {{-- Costo--}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Costo inicial</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="number" min="0.00" step="0.01" class="form-control" placeholder="0.00" name="costo"
                                       value="{{ old('costo') }}" id="costo">
                            </div>
                        </div>
                    </div>

                    <h4>Peso de volumen</h4>
                    <br>

                    {{-- Unidad de medida prederteminada --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Unidad medida volumen equivalente</label>
                        <div class="col-sm-8">
                            <select class="form-control select2" name="unidad_medida_volumen">
                                <option value="" selected disabled>Seleccione una opción</option>
                                @foreach($unidad_medidas as $unidad_medida)
                                    @if($unidad_medida->tipo == 'Volumen')
                                        <option value="{{ $unidad_medida->id }}">{{ $unidad_medida->nombre }}
                                            - {{ $unidad_medida->abreviatura }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Cantidad minima --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Peso unidad medida volumen</label>
                        <div class="col-sm-8">
                            <input type="number" min="0" step="any" class="form-control" placeholder="ej. 0" name="factor_volumen"
                                   value="{{ old('existencia_min') }}">
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
