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
        <form class="form-horizontal" action="{{ route('produccionNuevoPost') }}" method="POST">
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
                        <div class="col-sm-8">
                            <select class="form-control select2" name="formula_id">
                                <option value="" selected disabled>Seleccione el producto a producir</option>
                                @foreach($formulas as $formula)
                                    <option value="{{ $formula->id }}">{{ $formula->producto->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Nombre del producto --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b>Realizado por</b></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="Producto" name="nombre"
                                   value="{{Auth::user()->nombre}} {{Auth::user()->apellido}}" disabled>
                        </div>
                    </div>



                </div>
                <div class="col-md-6 col-sm-12">
                    <h4>Cantidad y detalle</h4>
                    <br>

                    {{-- Cantidad produccion --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Cantidad a producir</label>
                        <div class="col-sm-8">
                            <input type="number" min="0.00" class="form-control" placeholder="0" name="cantidad"
                                   value="{{ old('cantidad') }}">
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
