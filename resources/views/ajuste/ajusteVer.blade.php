@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Ajuste
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
@endsection

@section('contentheader_title')
    Ajuste de producto
@endsection

@section('contentheader_description')
    -- Realizar ajuste al inventario
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
                            <div class="input-group">
                                <input readonly type="date" class="form-control" name="fecha" value="{{ $ajuste->fecha->format('Y-m-d') }}">
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
                            <select disabled class="form-control select2" style="width: 100%" name="producto_id" id="productoID">
                                <option value="" selected disabled>Seleccione un producto</option>
                                @foreach($productos as $producto)
                                    @if($producto->id == $ajuste->movimiento->producto_id)
                                        <option selected value="{{ $producto->id }}" data-vu="{{ $producto->costo }}" data-ca="{{ $producto->cantidad }}">{{ $producto->codigo }} -- {{ $producto->nombre }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Tipo de movimiento --}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><b>Tipo movimiento</b></label>
                        <div class="col-sm-9">
                            <select disabled class="form-control select2" style="width: 100%" name="tipo_movimiento_id" id="tipo-movimiento-id" onchange="cambioTipoAjusteJS()">
                                @if($ajuste->tipo_ajuste->tipo == 'ENTRADA')
                                    <option selected value="1">Entrada</option>
                                @else
                                    <option selected value="2">Salida</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    {{-- Tipo de ajuste --}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label" id="tipo-ajuste-lbl-id"><b>Tipo ajuste</b></label>
                        <div class="col-sm-9">
                            <select disabled class="form-control select2" style="width: 100%" name="tipo_ajuste_id" id="tipo-ajuste-id">
                                <option value="{{ $ajuste->tipo_ajuste->id }}" selected>{{ $ajuste->tipo_ajuste->nombre }}</option>
                            </select>
                        </div>
                    </div>

                    {{-- Descripcion del ajuste --}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><b>Descripción del ajuste</b></label>
                        <div class="col-sm-9">
                            {{-- <input type="text" class="form-control" placeholder="Descripcion" name="descripcion"> --}}
                            <textarea readonly class="form-control" placeholder="Descripcion" name="detalle">{{ $ajuste->detalle }}</textarea>
                        </div>
                    </div>

                    {{-- Reralizado por --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label">Realizado por:</label>
                        <div class="col-md-9 ">
                            <input disabled type="text" class="form-control"  name="realizado_id" value="{{ $ajuste->realizado->nombre }} {{ $ajuste->realizado->apellido }}">
                        </div>
                    </div>

                </div>

                <div class="col-md-6 col-sm-12">
                    <h4>Cantidad a ajustar</h4>
                    <br>

                    {{-- Cantidad Actual --}}
                    <div class="form-group">
                        <label class="col-md-4 col-sm-2 control-label">Existencia anterior</label>
                        <div class="col-md-8 col-sm-10">
                            <div class="input-group">
                                <input type="number" min="0" class="form-control" placeholder="0" name="cantidad_anterior" id="cantidadID" disabled value="{{ $ajuste->cantidad_anterior }}">
                                <span class="input-group-addon">Kg</span>
                            </div>
                        </div>
                    </div>

                    {{-- Cantidad ajuste --}}
                    <div class="form-group">
                        <label class="col-md-4 col-sm-2 control-label"><b>Cantidad ajuste</b></label>
                        <div class="col-md-8 col-sm-10">
                            <div class="input-group">
                                <input readonly type="number" min="0" step="any" class="form-control" name="diferencia_ajuste" id="diferencia-ajuste-id" value="{{ $ajuste->diferencia_ajuste }}">
                            <span class="input-group-addon">Kg</span>
                            </div>
                        </div>
                    </div>

                    {{-- Cantidad total ajuste --}}
                    <div class="form-group">
                        <label class="col-md-4 col-sm-2 control-label">Existencia después de ajuste</label>
                        <div class="col-md-8 col-sm-10">
                            <div class="input-group">
                                <input readonly type="number" min="0" step="any" class="form-control" placeholder="0" name="cantidad_ajuste" id="cantidad-ajuste-id" value="{{ $ajuste->cantidad_ajuste }}">
                            <span class="input-group-addon">Kg</span>
                            </div>
                        </div>
                    </div>

                    {{-- Costo unitario Actual --}}
                    {{--<div class="form-group">--}}
                        {{--<label class="col-md-4 col-sm-2 control-label">Costo unitario</label>--}}
                        {{--<div class="col-md-8 col-sm-10">--}}
                            {{--<div class="input-group">--}}
                                {{--<span class="input-group-addon">$</span>--}}
                                {{--<input type="number" step="0.01" min="0" class="form-control" placeholder="0.00" name="costo" id="valorUnitarioID" disabled>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                </div>

            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('ajusteLista') }}" class="btn btn-lg btn-default"><span class="fa fa-mail-reply"></span> Regresar a lista</a>
                {{--<button type="submit" class="btn btn-lg btn-success pull-right"><span class="fa fa-floppy-o"></span>Guardar</button>--}}
            </div>
        </form>
    </div><!-- /.box -->

@endsection

@section('JSExtras')
    @include('comun.select2Jses')
    <script>

        $("#productoID").change(valorActual);
        $('#diferencia-ajuste-id').change(cambioCantidadAjuste);
        $('#diferencia-ajuste-id').keyup(cambioCantidadAjuste);

        function valorActual() {
            let producto_id = $(this).val();
            let costo_unitario = $(this).find(':selected').data('vu');
            let cantidad = $(this).find(':selected').data('ca');
            $("#cantidadID").val(cantidad);
            $("#valorUnitarioID").val(costo_unitario);
            $('#cantidad-ajuste-id').val(cantidad);
            // $("cantidadID").val();
        }

        /**
         * Estado: Verificada y funcionando
         */
        function cambioTipoAjusteJS() {
            // Se almacena el id del producto seleccionado en una variable
            let tipo_movimiento_select = $('#tipo-movimiento-id');
            let tipo_ajuste_select = $('#tipo-ajuste-id');
            // Poner en select los diferentes precios del producto
            $.ajax({
                url: '/api/tipoAjustes/' + tipo_movimiento_select.val(),
                type: 'GET',
                dataType: 'JSON',
                success: function (datos) {
                    tipo_ajuste_select.select2('destroy');
                    tipo_ajuste_select.empty();
                    for (var i = 0; i < datos.length; i++) {
                        if (i == 0) {
                            tipo_ajuste_select.append('<option selected value="' + datos[i].id + '">' + datos[i].nombre + '</option>');
                        } else {
                            tipo_ajuste_select.append('<option value="' + datos[i].id + '">' + datos[i].nombre + '</option>');
                        }
                    }
                    tipo_ajuste_select.select2();
                },
                async: false
            });
            $('#diferencia-ajuste-id').removeAttr('readonly');
        }

        function cambioCantidadAjuste() {
            let cantidad_ajuste = parseFloat($(this).val());
            let cantidad_actual = parseFloat($('#cantidadID').val());
            let tipo_ajuste = parseInt($('#tipo-movimiento-id').val());
            let cantidad_total_ajuste;
            if (tipo_ajuste === 1){
                cantidad_total_ajuste = cantidad_actual + cantidad_ajuste;
            } else {
                cantidad_total_ajuste = cantidad_actual - cantidad_ajuste;
                if (cantidad_total_ajuste < 0)
                {
                    cantidad_total_ajuste = 0.0;
                    alert('La cantidad de ajuste no puede ser mayor que la existencia');
                }
            }
            $('#cantidad-ajuste-id').val(cantidad_total_ajuste.toFixed(4));
        }

    </script>
@endsection