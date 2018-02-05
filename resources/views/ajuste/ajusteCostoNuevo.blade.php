@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Ajuste de costo
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
@endsection

@section('contentheader_title')
    Ajuste al costo de producto
@endsection

@section('contentheader_description')
    -- Realizar ajuste al costo del producto
@endsection

@section('main-content')

    @include('partials.alertas')

    <!-- Form de nuevo proveedor -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Datos de ajuste</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" action="{{ route('ajusteCostoNuevoPost') }}" method="POST">
            {{ csrf_field() }}
            <div class="box-body">

                <div class="col-md-6 col-sm-12">
                    <h4>Producto</h4>
                    <br>

                    {{-- Fecha --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label"><b>Fecha ajuste:</b></label>
                        <div class="col-md-9 ">
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
                        <label class="col-sm-3 control-label"><b>Producto</b></label>
                        <div class="col-sm-9">
                            <select class="form-control select2" style="width: 100%" name="producto_id" id="productoID">
                                <option value="" selected disabled>Seleccione un producto</option>
                                @foreach($productos as $producto)
                                    <option value="{{ $producto->id }}" data-vu="{{ $producto->costo }}" data-ca="{{ $producto->cantidad_existencia }}">{{ $producto->codigo }} -- {{ $producto->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Tipo de ajuste --}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><b>Tipo</b></label>
                        <div class="col-sm-9">
                            <select class="form-control select2" style="width: 100%" name="tipo_ajuste_id">
                                <option value="" selected disabled>Selecione un tipo de ajuste</option>
                                @foreach($tipoAjustes as $tipoAjuste)
                                    @if($tipoAjuste->tipo == 'COSTO')
                                        <option value="{{ $tipoAjuste->id }}">{{ $tipoAjuste->nombre }}</option>
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
                            <textarea class="form-control" placeholder="Descripcion" name="detalle"></textarea>
                        </div>
                    </div>
                    {{-- Reralizado por --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label">Realizado por:</label>
                        <div class="col-md-9 ">
                            <input disabled type="text" class="form-control"  name="realizado_id" value="{{ Auth::user()->nombre }} {{ Auth::user()->apellido }}">
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12">
                    <h4>Cantidad y costo</h4>
                    <br>

                    {{-- Cantidad Actual --}}
                    <div class="form-group">
                        <label class="col-md-4 col-sm-2 control-label">Cantidad existencia actual</label>
                        <div class="col-md-8 col-sm-10">
                            <input type="number" min="0" class="form-control" placeholder="0" name="cantidad_anterior" id="cantidadID" disabled>
                        </div>
                    </div>

                    {{-- Costo unitario Actual --}}
                    <div class="form-group">
                        <label class="col-md-4 col-sm-2 control-label">Costo unitario</label>
                        <div class="col-md-8 col-sm-10">
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="number" step="0.01" min="0" class="form-control" placeholder="0.00" name="valor_unitario_anterior" id="valorUnitarioID" disabled>
                            </div>
                        </div>
                    </div>

                    {{-- Costo unitario ajuste --}}
                    <div class="form-group">
                        <label class="col-md-4 col-sm-2 control-label"><b>Costo unitario ajuste</b></label>
                        <div class="col-md-8 col-sm-10">
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="number" step="0.01" min="0" class="form-control" placeholder="0.00" name="costo_ajuste">
                            </div>
                        </div>
                    </div>

                </div>

            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('ajusteLista') }}" class="btn btn-lg btn-default"><span class="fa fa-close"></span> Cancelar</a>
                <button type="submit" class="btn btn-lg btn-success pull-right"><span class="fa fa-floppy-o"></span> Guardar</button>
            </div>
        </form>
    </div><!-- /.box -->

@endsection

@section('JSExtras')
    @include('comun.select2Jses')
    <script>
        $("#productoID").change(valorActual);
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

