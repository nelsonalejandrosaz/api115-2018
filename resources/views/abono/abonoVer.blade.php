@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Ver abono
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
@endsection

@section('contentheader_title')
    Ver abono
@endsection

@section('contentheader_description')
    Detalle del abono de cliente: {{$cliente->nombre}}
@endsection

@section('main-content')

    @include('partials.alertas')

    {{--Barra herramientas--}}
    <div class="box box-default box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">Opciones</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
        </div><!-- /.box-header -->

        <!-- form start -->
        <form class="form-horizontal" id="fechas-form-id">
            <div class="box-body">
                <div class="col-md-6 col-sm-12">

                </div>

            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('abonoLista') }}" class="btn btn-lg btn-default"><span
                            class="fa fa-mail-reply"></span> Regresar a lista abonos</a>
                <a href="{{ route('abonoNuevoSinVenta') }}" class="btn btn-lg btn-success pull-right" style="margin-left: 10px"><span class="fa fa-plus"></span> Nuevo abono</a>
                {{--<a href='javascript:window.print(); void 0;'  style="margin-left: 10px"><span class="fa fa-file-excel-o"></span> Exportar Excel</a>--}}
            </div>
        </form>
    </div><!-- /.box -->

    <!-- Form de nuevo producto -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Datos del abono</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" action="" method="POST">
            {{ csrf_field() }}
            <div class="box-body">
                <div class="col-md-6 col-sm-12">
                    <h4>Datos de cliente</h4>
                    <br>


                    {{-- Nombre del cliente --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Cliente</label>
                        <div class="col-sm-8">
                            <input readonly type="text" class="form-control" placeholder="Producto" name="nombre"
                                   value="{{ $cliente->nombre }}">
                        </div>
                    </div>

                    {{-- Numero de venta --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">N° documento</label>
                        <div class="col-sm-8">
                            <input readonly type="text" class="form-control" placeholder="Producto" name="nombre"
                                   value="{{ $venta->numero }}">
                        </div>
                        {{--<div class="col-sm-3">--}}
                            {{--<a href="{{ Storage::url($venta->ruta_archivo) }}" target="_blank" class="btn btn-info pull-right"><span class="fa fa-file"></span> Ver factura</a>--}}
                        {{--</div>--}}
                    </div>

                    {{-- Numero de venta --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">N° recibo caja</label>
                        <div class="col-sm-8">
                            <input readonly type="text" class="form-control" placeholder="Producto" name="nombre"
                                   value="{{ $abono->recibo_caja }}">
                        </div>
                    </div>

                    {{-- Saldo venta --}}
                    {{--<div class="form-group">--}}
                        {{--<label class="col-sm-3 control-label">Saldo factura</label>--}}
                        {{--<div class="col-sm-9">--}}
                            {{--<div class="input-group">--}}
                                {{--<span class="input-group-addon">$</span>--}}
                                {{--<input readonly type="text" class="form-control" placeholder="Producto" name="saldo_venta"--}}
                                       {{--value="{{ number_format($venta->saldo,2) }}">--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    {{-- Saldo total --}}
                    {{--<div class="form-group">--}}
                        {{--<label class="col-sm-3 control-label">Saldo cliente</label>--}}
                        {{--<div class="col-sm-9">--}}
                            {{--<div class="input-group">--}}
                                {{--<span class="input-group-addon">$</span>--}}
                                {{--<input readonly type="text" class="form-control" placeholder="Producto" name="saldo_total"--}}
                                       {{--value="{{ number_format($cliente->saldo,2) }}">--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                </div>
                <div class="col-md-6 col-sm-12">
                    <h4>Detalle de abono</h4>
                    <br>

                    {{-- Fecha --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b>Fecha ingreso:</b></label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input readonly type="date" class="form-control" name="fecha" value="{{$abono->fecha->format('Y-m-d')}}">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Cantidad abono --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Cantidad abonada</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input readonly type="number" class="form-control" placeholder="0" name="cantidad"
                                       value="{{ number_format($abono->cantidad,2) }}">
                            </div>
                        </div>
                    </div>

                    {{-- Forma de pago --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Forma de pago</label>
                        <div class="col-sm-8">
                            <input readonly type="text" class="form-control" placeholder="Producto" name="nombre"
                                   value="{{ $abono->forma_pago->nombre }}">
                        </div>
                    </div>

                    {{-- Detalle --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Detalle</label>
                        <div class="col-sm-8">
                            <textarea readonly name="detalle" class="form-control">{{ $abono->detalle }}</textarea>
                        </div>
                    </div>

                </div>
            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('abonoLista') }}" class="btn btn-lg btn-default"><span class="fa fa-mail-reply"></span> Regresar a lista</a>
                {{--<button type="submit" class="btn btn-lg btn-success pull-right"><span class="fa fa-credit-card"></span> Abonar</button>--}}
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

    </script>
    @include('comun.select2Jses')
@endsection
