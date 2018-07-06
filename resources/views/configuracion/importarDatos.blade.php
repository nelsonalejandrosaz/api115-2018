@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Importar datos
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
@endsection

@section('contentheader_title')
    Importar datos
@endsection

@section('contentheader_description')
    Importar datos a través de archivo
@endsection

@section('main-content')

    @include('partials.alertas')

    <!-- Form de nuevo producto -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Importar datos</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" action="{{ route('importarDatosPost') }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="box-body">
                <div class="col-md-12 col-sm-12">
                    <div class="form-group">
                        <label><b>Archivo</b></label>
                        <input type="file" name="archivoXLSX">
                        <p class="help-block">Suba en archivo llamado "datos-sistema-lgl.xlsx".</p>
                    </div>
                </div>
            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="#" class="btn btn-lg btn-default">Cancelar</a>
                <button type="submit" class="btn btn-lg btn-success pull-right">Importar</button>
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
