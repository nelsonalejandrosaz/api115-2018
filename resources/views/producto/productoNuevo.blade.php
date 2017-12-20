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
    Ingresar un nuevo producto
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

                    {{-- Codigo del producto --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Codigo del producto</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="Autogenerado" name="codigo"
                                   disabled="">
                        </div>
                    </div>
                    {{-- Nombre del producto --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b>Nombre del producto</b></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="Producto" name="nombre"
                                   value="{{ old('nombre') }}">
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

                    {{-- Unidad de medida compra --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b>Unidad medida </b></label>
                        <div class="col-sm-8">
                            <select class="form-control select2" name="unidad_medida_id">
                                <option value="" selected disabled>Seleccione una opción</option>
                                @foreach($unidadMedidas as $unidadMedida)
                                    <option value="{{ $unidadMedida->id }}">{{ $unidadMedida->nombre }}
                                        - {{ $unidadMedida->abreviatura }}</option>
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
                                @foreach($tipoProductos as $tipoProducto)
                                    <option value="{{ $tipoProducto->id }}">{{ $tipoProducto->nombre }}</option>
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
                            <input type="number" min="0" class="form-control" placeholder="0" name="existenciaMin"
                                   value="{{ old('existenciaMin') }}">
                        </div>
                    </div>

                    {{-- Cantidad maxima --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Cantidad maxima</label>
                        <div class="col-sm-8">
                            <input type="number" min="0" class="form-control" placeholder="0" name="existenciaMax"
                                   value="{{ old('existenciaMax') }}">
                        </div>
                    </div>

                </div>
            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('productoLista') }}" class="btn btn-lg btn-default">Cancelar</a>
                <button type="submit" class="btn btn-lg btn-success pull-right">Guardar</button>
            </div>
        </form>
    </div><!-- /.box -->

@endsection

@section('JSExtras')
    @include('comun.select2Jses')
@endsection
