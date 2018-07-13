@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Informe costo inventario
@endsection

@section('CSSx')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
    {{-- DataPicker --}}
    <link rel="stylesheet" href="{{asset('/css/datapicker/bootstrap-datepicker3.css')}}">
@endsection

@section('contentheader_title')
    Informe costo inventario
@endsection

@section('contentheader_description')

@endsection

@section('main-content')

    @include('partials.alertas')

    <div class="box box-default box-solid no-print">
        <div class="box-header with-border">
            <h3 class="box-title">Opciones de informe</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
        </div><!-- /.box-header -->

        <!-- form start -->
        <form class="form-horizontal" id="opciones-form">
            <div class="box-body">





            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('informeLista') }}" class="btn btn-lg btn-default"><span class="fa fa-mail-reply"></span> Regresar</a>
                <a href='javascript:window.print(); void 0;' class="btn btn-lg btn-success pull-right" style="margin-left: 10px"><span class="fa fa-print"></span> Imprimir</a>
                <button type="button" class="btn btn-lg btn-success pull-right" id="excel-buttom"><span class="fa fa-file-excel-o"></span> Exportar Excel</button>
            </div>
        </form>
    </div><!-- /.box -->

    <section class="invoice">
        <div class="row">
            {{--Encabezado--}}
            <div class="col-xs-12">
                <h2 class="page-header">
                    <i class="fa fa-globe"></i> LGL S.A. de C.V. -- Informe costo de inventario
                    <small class="pull-right">Generado: {{\Carbon\Carbon::now()->format('d/m/Y -- h:m:s A')}}</small>
                </h2>
                <p class="lead">Al dia: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
                Generado por: {{Auth::user()->nombre}} {{Auth::user()->apellido}} <br>
            </div>

            {{-- Tabla de productos --}}
            <div class="col-xs-12" style="padding-top: 20px">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-striped table-condensed" id="tblProductos">
                        <thead>
                        <tr>
                            <th style="width: 10%">Codigo</th>
                            <th style="width: 30%">Producto</th>
                            <th style="width: 20%">Cantidad</th>
                            <th style="width: 20%">Costo Unitario</th>
                            <th style="width: 20%">Costo Total</th>
                        </tr>
                        </thead>
                        <tbody>
                            @php($total_inventario = 0.00)
                            @foreach($productos_agrupados as $productos)
                                <tr>
                                    <td colspan="5"><b>{{$productos->first()->tipo_producto->nombre}}</b></td>
                                </tr>
                                @foreach($productos as $producto)
                                    <tr>
                                        <td>{{$producto->codigo}}</td>
                                        <td>{{$producto->nombre}}</td>
                                        <td>{{number_format($producto->cantidad_existencia,4)}} Kgs</td>
                                        <td>$ {{number_format($producto->costo,2)}}</td>
                                        <td>$ {{number_format($producto->costo_total,2)}}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="4"><b>SUB-TOTALES</b></td>
                                    <td><b>$ {{number_format($productos->sum('costo_total'),2)}}</b></td>
                                    @php($total_inventario += $productos->sum('costo_total'))
                                </tr>
                            @endforeach
                        </tbody>
                        <td colspan="4"><b>TOTAL INVENTARIO</b></td>
                        <td><b>$ {{ number_format($total_inventario,2) }}</b></td>
                    </table>
                </div>
            </div>
        </div>

    </section>

@endsection

@section('JSExtras')
    {{-- Funcion para cargar mas filas de productos --}}
    <script>
        $(document).ready(Principal);

        function Principal() {
            $('#excel-buttom').click(ExportarExcel);
        }

        function ExportarExcel() {
            let fecha_form = $('#opciones-form');
            let fechas_str = fecha_form.serialize();
            let path = window.location.pathname;
            let uri = path + "/excel" + "?" + fechas_str;
            toastr.info("Generando el Excel en la fecha selecionada","Excelente!!");
            window.location.href = uri;
        }

    </script>
@endsection

