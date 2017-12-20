@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Ver ajuste
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
@endsection

@section('contentheader_title')
    Ver ajuste
@endsection

@section('contentheader_description')
    -- Datos del ajuste al inventario
@endsection

@section('main-content')

    @include('partials.alertas')

    <!-- Form de nuevo proveedor -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Datos de ajuste</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" action="{{ route('ajusteNuevoPost') }}" method="POST">
            {{ csrf_field() }}
            <div class="box-body">

                <div class="col-md-6 col-sm-12">
                    <h4>Producto</h4>
                    <br>
                    {{-- Fecha --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label"><b>Fecha ajuste:</b></label>
                        <div class="col-md-9 ">
                            <input disabled type="date" class="form-control" name="fechaIngreso" value="{{$ajuste->fechaIngreso}}">
                        </div>
                    </div>
                    {{-- Producto --}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><b>Producto</b></label>
                        <div class="col-sm-9">
                            <select disabled class="form-control select2" style="width: 100%" name="producto_id" id="productoID">
                                <option value="" selected disabled>Seleccione un producto</option>
                                @foreach($productos as $producto)
                                    @if($producto->id == $ajuste->movimiento->producto_id)
                                    <option selected value="{{ $producto->id }}" data-vu="{{ $producto->precioCompra }}" data-ca="{{ $producto->cantidad }}">{{ $producto->nombre }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- Tipo de ajuste --}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><b>Tipo</b></label>
                        <div class="col-sm-9">
                            <select disabled class="form-control select2" style="width: 100%" name="tipo_ajuste_id">
                                <option value="" selected disabled>Selecione un tipo de ajuste</option>
                                @foreach($tipoAjustes as $tipoAjuste)
                                    @if($tipoAjuste->id == $ajuste->tipo_ajuste_id)
                                    <option selected value="{{ $tipoAjuste->id }}">{{ $tipoAjuste->nombre }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- Descripcion del ajuste --}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><b>Descripción del ajuste</b></label>
                        <div class="col-sm-9">
                            {{-- <input type="text" class="form-control" placeholder="Descripcion" name="descripcion"> --}}
                            <textarea disabled class="form-control" placeholder="Descripcion" name="detalle">{{$ajuste->detalle}}</textarea>
                        </div>
                    </div>
                    {{-- Reralizado por --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label">Realizado por:</label>
                        <div class="col-md-9 ">
                            <input disabled type="text" class="form-control"  name="realizadoPor_id" value="{{$ajuste->realizadoPor->nombre}} {{$ajuste->realizadoPor->apellido}}">
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12">
                    <h4>Cantidad</h4>
                    <br>
                    {{-- Cantidad Actual --}}
                    <div class="form-group">
                        <label class="col-md-4 col-sm-2 control-label">Cantidad anterior</label>
                        <div class="col-md-8 col-sm-10">
                            <input type="number" min="0" class="form-control" placeholder="0" name="cantidadAnterior" id="cantidadID" disabled value="{{$ajuste->cantidadAnterior}}">
                        </div>
                    </div>
                    {{-- Valor unitario Actual --}}
                    <div class="form-group">
                        <label class="col-md-4 col-sm-2 control-label">Valor unitario anterior</label>
                        <div class="col-md-8 col-sm-10">
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input disabled type="number" step="0.01" min="0" class="form-control" placeholder="0.00" name="valorUnitarioAnterior" id="valorUnitarioID" disabled value="{{$ajuste->valorUnitarioAnterior}}">
                            </div>
                        </div>
                    </div>
                    {{-- Cantidad ajuste --}}
                    <div class="form-group">
                        <label class="col-md-4 col-sm-2 control-label"><b>Cantidad ajuste</b></label>
                        <div class="col-md-8 col-sm-10">
                            <input disabled type="number" min="0" class="form-control" placeholder="0" name="cantidadAjuste" value="{{$ajuste->cantidadAjuste}}">
                        </div>
                    </div>
                    {{-- Valor unitario ajuste --}}
                    <div class="form-group">
                        <label class="col-md-4 col-sm-2 control-label"><b>Valor unitario ajuste</b></label>
                        <div class="col-md-8 col-sm-10">
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input disabled type="number" step="0.01" min="0" class="form-control" placeholder="0.00" name="valorUnitarioAjuste" value="{{$ajuste->valorUnitarioAjuste}}">
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('ajusteLista') }}" class="btn btn-lg btn-default">Ver lista</a>
            </div>
        </form>
    </div><!-- /.box -->

@endsection

@section('JSExtras')
    @include('comun.select2Jses')
    <script>
        $("#productoID").change(valorActual)
        function valorActual() {
            id = $(this).val();
            valorUnitatio = $(this).find('option[value="'+id+'"]').data('vu');
            cantidad = $(this).find('option[value="'+id+'"]').data('ca');
            $("#cantidadID").val(cantidad);
            $("#valorUnitarioID").val(valorUnitatio);
            // $("cantidadID").val();
        }
    </script>
@endsection

