@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Nuevo abono
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
@endsection

@section('contentheader_title')
    Nuevo abono
@endsection

@section('contentheader_description')
    Realizar un abono para cliente: {{$cliente->nombre}}
@endsection

@section('main-content')

    @include('partials.alertas')

    <!-- Form de nuevo producto -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Datos del producto</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" action="{{ route('abonoNuevoPost',['id' => $venta->id]) }}" method="POST">
            {{ csrf_field() }}
            <div class="box-body">
                <div class="col-md-6 col-sm-12">
                    <h4>Datos de cliente</h4>
                    <br>


                    {{-- Nombre del cliente --}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Cliente</label>
                        <div class="col-sm-9">
                            <input readonly type="text" class="form-control" placeholder="Producto" name="nombre"
                                   value="{{ $cliente->nombre }}">
                        </div>
                    </div>

                    {{-- Numero de venta --}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Numero factura</label>
                        <div class="col-sm-5">
                            <input readonly type="text" class="form-control" placeholder="Producto" name="nombre"
                                   value="{{ $venta->numero }}">
                        </div>
                        <div class="col-sm-4">
                            <a href="{{ Storage::url($venta->ruta_archivo) }}" target="_blank" class="btn btn-info pull-right"><span class="fa fa-file"></span> Ver factura</a>
                        </div>
                    </div>

                    {{-- Saldo venta --}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Saldo factura</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input readonly type="text" class="form-control" placeholder="Producto" name="saldo_venta"
                                       value="{{ number_format($venta->saldo,2) }}">
                            </div>
                        </div>
                    </div>

                    {{-- Saldo total --}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Saldo cliente</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input readonly type="text" class="form-control" placeholder="Producto" name="saldo_total"
                                       value="{{ number_format($cliente->saldo,2) }}">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-6 col-sm-12">
                    <h4>Detalle de abono</h4>
                    <br>

                    {{-- Fecha --}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><b>Fecha ingreso:</b></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="date" class="form-control" name="fecha">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Cantidad abono --}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Cantidad a abonar</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="number" min="0.00" step="0.01" class="form-control" placeholder="0" name="cantidad"
                                       value="{{ old('cantidad') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Detalle --}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Detalle</label>
                        <div class="col-sm-9">
                            <textarea name="detalle" class="form-control">{{ old('detalle') }}</textarea>
                        </div>
                    </div>

                </div>
            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('produccionLista') }}" class="btn btn-lg btn-default"><span class="fa fa-close"></span> Cancelar</a>
                <button type="submit" class="btn btn-lg btn-success pull-right"><span class="fa fa-credit-card"></span> Abonar</button>
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
