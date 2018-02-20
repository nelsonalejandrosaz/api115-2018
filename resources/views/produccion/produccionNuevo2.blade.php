@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Nueva Producción
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
@endsection

@section('contentheader_title')
    Nueva Producción
@endsection

@section('contentheader_description')
    Realizar una nueva producción
@endsection

@section('main-content')

    @include('partials.alertas')

    <!-- Form de nuevo producto -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Datos del producto</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" action="{{ route('produccionNuevaPost') }}" method="POST">
            {{ csrf_field() }}
            <div class="box-body">
                <div class="col-md-6 col-sm-12">
                    <h4>Formulas</h4>
                    <br>

                    {{-- Fecha --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label"><b>Fecha producción</b></label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="date" class="form-control" name="fecha">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Producto --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b>Producto</b></label>
                        <div class="col-sm-6">
                            <select class="form-control select2" name="formula_id" onchange="cambioProducto()"
                                    id="productoID">
                                <option value="" selected disabled>Seleccione el producto a producir</option>
                                @foreach($formulas as $formula)
                                    <option value="{{ $formula->id }}"
                                            data-unidadmedida="{{$formula->producto->unidad_medida->nombre}}"
                                            data-factor="{{$formula->producto->factor_volumen}}">{{ $formula->producto->nombre }} -- Formula {{$formula->version}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <a href="" target="_blank" class="btn btn-info" id="ver-f-id"><span class="fa fa-eye"></span></a>
                        </div>
                    </div>

                    {{-- Peso volumen --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Peso referencia unidad de volumen</label>
                        <div class="col-sm-8">
                            <input readonly type="text" class="form-control" placeholder="Seleccione el producto" name=""
                                   value="" id="factorVolumenID">
                        </div>
                    </div>

                    {{-- Registrado por --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b>Registrado por</b></label>
                        <div class="col-sm-8">
                            <input readonly type="text" class="form-control" placeholder="Producto" name="nombre"
                                   value="{{Auth::user()->nombre}} {{Auth::user()->apellido}}">
                        </div>
                    </div>

                    {{-- Fabricado por --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b>Fabricado por</b></label>
                        <div class="col-sm-8">
                            <select class="form-control select2" name="fabricado_id[]" multiple>
                                @foreach($bodegueros as $bodeguero)
                                    <option value="{{ $bodeguero->id }}">{{ $bodeguero->nombre }} {{ $bodeguero->apellido }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                </div>
                <div class="col-md-6 col-sm-12">
                    <h4>Cantidad y detalle</h4>
                    <br>

                    {{-- Cantidad produccion --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b>Cantidad a producir</b></label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input type="number" min="0.00" step="any" class="form-control" placeholder="0"
                                       name="cantidad"
                                       value="{{ old('cantidad') }}">
                                <span class="input-group-addon">Kgs</span>
                            </div>
                        </div>
                    </div>

                    {{-- Lote --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Lote</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" placeholder="ej. 12345" name="lote"
                                   value="">
                        </div>
                    </div>

                    {{-- Fecha vencimiento --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Fecha vencimiento</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input type="date" class="form-control" name="fecha_vencimiento"
                                       value="">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Detalle --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Detalle</label>
                        <div class="col-sm-8">
                            <textarea name="detalle" class="form-control" rows="5">{{ old('detalle') }}</textarea>
                        </div>
                    </div>

                </div>
            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('produccionLista') }}" class="btn btn-lg btn-default"><span
                            class="fa fa-close"></span> Cancelar</a>
                <button type="submit" class="btn btn-lg btn-success pull-right"><span class="fa fa-gears"></span>
                    Producir
                </button>
            </div>
        </form>
    </div><!-- /.box -->

@endsection

@section('JSExtras')
    <script>


        function cambioProducto() {
            let productoId = $('#productoID').val();
            let unidadMedida = $('#productoID').find(':selected').data('unidadmedida');
            let factor = $('#productoID').find(':selected').data('factor');
            console.log(factor);
            $('#unidadMedidalbl').val(unidadMedida);
            let factor_unidad = 'No hay referencia de peso para este producto';
            if (factor !== 0) {
                factor_unidad = '1 Gl = ' + factor + ' Kg';
            }
            $('#factorVolumenID').val(factor_unidad);
            let link = "/formula/" + $('#productoID').find(':selected').val();
            $('#ver-f-id').attr("href", link);
        }
    </script>
    @include('comun.select2Jses')
@endsection
