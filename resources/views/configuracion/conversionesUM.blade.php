@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Factores de conversión
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
@endsection

@section('contentheader_title')
    Factores de conversión
@endsection

@section('contentheader_description')
    -- Ingresar un factor para la conversión de unidades
@endsection

@section('main-content')

    @include('partials.alertas')

    <!-- Form de nuevo producto -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Datos del la conversión</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" action="{{ route('conversionUnidadesNuevoPost') }}" method="POST">
            {{ csrf_field() }}
            <div class="box-body">
                <div class="col-md-6 col-sm-12">
                    <h4>Unidades de medida</h4>
                    <br>

                    {{-- Codigo de la conversión --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b>Código de conversión</b></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="Código" name="codigo" value="{{old('codigo')}}">
                        </div>
                    </div>

                    {{-- Nombre de la conversión --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b>Nombre de conversión</b></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="Nombre" name="nombre" value="{{old('nombre')}}">
                        </div>
                    </div>

                    {{-- Unidad de medida origen --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b>Unidad medida origen</b></label>
                        <div class="col-sm-8">
                            <select class="form-control select2" name="unidadMedidaOrigen_id">
                                <option value="" selected disabled>Seleccione una opción</option>
                                @foreach($unidadMedidas as $unidadMedida)
                                    <option value="{{ $unidadMedida->id }}">{{ $unidadMedida->nombre }}
                                        - {{ $unidadMedida->abreviatura }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Unidad de medida destino --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b>Unidad medida destino</b></label>
                        <div class="col-sm-8">
                            <select class="form-control select2" name="unidadMedidaDestino_id">
                                <option value="" selected disabled>Seleccione una opción</option>
                                @foreach($unidadMedidas as $unidadMedida)
                                    <option value="{{ $unidadMedida->id }}">{{ $unidadMedida->nombre }}
                                        - {{ $unidadMedida->abreviatura }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>
                <div class="col-md-6 col-sm-12">
                    <h4>Factor de conversión</h4>
                    <br>

                    {{-- Factor de conversión --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b>Factor de conversión</b></label>
                        <div class="col-sm-8">
                            <input type="number" min="0.00" step="0.01" class="form-control" placeholder="0" name="factor"
                                   value="{{ old('factor') }}">
                        </div>
                    </div>

                </div>
            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('conversionUnidadesLista') }}" class="btn btn-lg btn-default">Cancelar</a>
                <button type="submit" class="btn btn-lg btn-success pull-right">Guardar</button>
            </div>
        </form>
    </div><!-- /.box -->

@endsection

@section('JSExtras')
    @include('comun.select2Jses')
@endsection
