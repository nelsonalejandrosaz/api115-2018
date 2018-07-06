@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Editar
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
@endsection

@section('contentheader_title')
    Editar
@endsection

@section('contentheader_description')

@endsection

@section('main-content')

    @include('partials.alertas')

    <!-- Form de nuevo producto -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Editar</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" action="{{ route('exportar.configuracion.update', ['id' => $exportacion_sac->id]) }}" method="POST">
            {{ csrf_field() }}
            <div class="box-body">
                <div class="col-md-6 col-sm-12">

                    {{-- Id del producto --}}
                    <input type="hidden" name="id" value="{{ $exportacion_sac->id }}">

                    {{-- Codigo del producto --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">ID Partida</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" name="id_partida" value="{{$exportacion_sac->id_partida}}">
                        </div>
                    </div>

                    {{-- Codigo del producto --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">ID Cuenta</label>
                        <div class="col-sm-8">
                            <input readonly type="text" class="form-control" name="codigo" value="{{$exportacion_sac->concepto}}">
                        </div>
                    </div>


                </div>
                <div class="col-md-6 col-sm-12">

                    {{-- Cantidad minima --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Concepto</label>
                        <div class="col-sm-8">
                            <input type="number" min="0" class="form-control" name="id_cuenta" value="{{ $exportacion_sac->id_cuenta }}">
                        </div>
                    </div>

                </div>
            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('exportar.configuracion') }}" class="btn btn-lg btn-default"><span class="fa fa-close"></span> Cancelar</a>
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
            $('#margen_ganancia').val(margen.toFixed(2));
        }

        function cambioMargen() {
            var costo = $('#costo').val();
            var margen = $('#margen_ganancia').val();
            if ($('#costo').val().length <= 0)
            {
                alert("Debe rellenar el campo costo antes de asignar precios");
                $('#margen_ganancia').val('');
            }
            precio = costo * (1 + (margen/100));
            $('#precio').val(precio.toFixed(2));
        }
    </script>
    @include('comun.select2Jses')
@endsection
