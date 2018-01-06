@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Pruebas
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
@endsection

@section('contentheader_title')
    Pruebas
@endsection

@section('contentheader_description')
    -- DEV
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
                    <div class="col-sm-8">
                        <select class="form-control select2" name="unidad_medida_id" id="prueba">
                        </select>
                    </div>

                </div>
                <div class="col-md-6 col-sm-12">
                    <h4>Control de inventario</h4>
                    <br>

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
    <!-- Select2 -->
    <script src="{{asset('/plugins/select2.full.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            var productoUMid = 2;
            $('#prueba').select2({
                // Activamos la opcion "Tags" del plugin
                tags: false,
                tokenSeparators: [','],
                ajax: {
                    dataType: 'json',
                    url: '{{ route('unidadesMedidaJSON') }}',
                    delay: 250,
                    data: function(params) {
                        return {
                            umo: productoUMid
                        }
                    },
                    processResults: function (data, page) {
                        return {
                            results: data
                        };
                    },
                },
                placeholder: 'Search for a repository',
            });
        });
    </script>
    {{--@include('comun.select2Jses')--}}
@endsection
