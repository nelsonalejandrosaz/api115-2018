@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Importar datos
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
    {{--date--}}
    <link rel="stylesheet" href="{{asset('/plugins/daterangepicker.css')}}">
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
                <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                        <label><b>Archivo</b></label>
                        <input type="file" name="archivoXLSX">
                        <p class="help-block">Suba en archivo llamado "datos-sistema-lgl.xlsx".</p>
                    </div>

                    {{-- Fecha inicio --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label"><b>Fecha inicio</b></label>
                        <div class="col-md-9 ">
                            <input readonly type="date" class="form-control" name="fecha_1" id="fecha-inicio">
                        </div>
                    </div>

                    {{-- Fecha fin --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label"><b>Fecha fin</b></label>
                        <div class="col-md-9 ">
                            <input readonly type="date" class="form-control" name="fecha_2" id="fecha-fin">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Date range button:</label>

                        <div class="input-group">
                            <button type="button" class="btn btn-default pull-right" id="daterange-btn">
                    <span>
                      <i class="fa fa-calendar"></i> Date range picker
                    </span>
                                <i class="fa fa-caret-down"></i>
                            </button>
                        </div>
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
    <script src="{{asset('plugins/moment.min.js')}}"></script>
    <script src="{{asset('plugins/daterangepicker.js')}}"></script>
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

        $('#daterange-btn').daterangepicker(
            {
                ranges   : {
                    'Hoy'       : [moment(), moment()],
                    'Ayer'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Últimos 7 Días' : [moment().subtract(6, 'days'), moment()],
                    'Últimos 30 Días': [moment().subtract(29, 'days'), moment()],
                    'Este mes'  : [moment().startOf('month'), moment().endOf('month')],
                    'Mes pasado'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment().subtract(29, 'days'),
                endDate  : moment()
            },
            function (start, end) {
                $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
                $('#fecha-inicio').val(start.format('YYYY-MM-DD'));
                $('#fecha-fin').val(end.format('YYYY-MM-DD'));
            }
        )

    </script>
    @include('comun.select2Jses')
@endsection
