@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Detalle de la producción
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
@endsection

@section('contentheader_title')
    Detalle de la producción
@endsection

@section('contentheader_description')
    Realizar una nueva producción
@endsection

@section('main-content')

    @include('partials.alertas')

    <!-- Form de nuevo producto -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Datos del producto</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" action="{{ route('produccionConfirmarPost',['id' => $produccion->id]) }}" method="POST">
            {{ csrf_field() }}
            <div class="box-body">
                <div class="col-md-6 col-sm-12">
                    <h4>Formulas</h4>
                    <br>

                    {{-- Fecha --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label"><b>Fecha producción</b></label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input readonly type="date" class="form-control" name="fecha" value="{{ $produccion->fecha }}">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Producto --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b>Producto</b></label>
                        <div class="col-sm-8">
                            <select disabled class="form-control select2" name="formula_id" onchange="cambioProducto()"
                                    id="productoID">
                                <option selected value="{{ $formula->id }}"
                                        data-unidadmedida="{{$formula->producto->unidad_medida->nombre}}"
                                        data-factor="{{$formula->producto->factor_volumen}}">{{ $formula->producto->nombre }}</option>
                            </select>
                        </div>
                    </div>

                    {{-- Peso volumen --}}
                    {{--<div class="form-group">--}}
                        {{--<label class="col-sm-4 control-label">Peso referencia unidad de volumen</label>--}}
                        {{--<div class="col-sm-8">--}}
                            {{--<input readonly type="text" class="form-control" placeholder="Seleccione el producto" name=""--}}
                                   {{--value="{{ $producion }}" id="factorVolumenID">--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    {{-- Registrado por --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b>Registrado por</b></label>
                        <div class="col-sm-8">
                            <input readonly type="text" class="form-control" placeholder="Producto" name="nombre"
                                   value="{{ $produccion->bodeguero->nombre }} {{ $produccion->bodeguero->apellido }}">
                        </div>
                    </div>

                    {{-- Fabricado por --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b>Fabricado por</b></label>
                        <div class="col-sm-8">
                            <select disabled class="form-control select2" name="fabricado_id[]" multiple>
                                @foreach($produccion->detalle_producciones as $detalle)
                                    <option selected value="{{ $detalle->bodega->id }}">{{ $detalle->bodega->nombre }} {{ $detalle->bodega->apellido }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                </div>
                <div class="col-md-6 col-sm-12">
                    <h4>Cantidad y detalle</h4>
                    <br>

                    {{-- Cantidad produccion --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b>Cantidad a producir</b></label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input readonly type="number" min="0.00" step="any" class="form-control" placeholder="0"
                                       name="cantidad"
                                       value="{{ number_format($produccion->cantidad,2) }}">
                                <span class="input-group-addon">Kgs</span>
                            </div>
                        </div>
                    </div>

                    {{-- Lote --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Lote</label>
                        <div class="col-sm-8">
                            <input readonly type="number" class="form-control" placeholder="ej. 12345" name="lote"
                                   value="{{ $produccion->lote }}">
                        </div>
                    </div>

                    {{-- Fecha vencimiento --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Fecha vencimiento</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input readonly type="date" class="form-control" name="fecha_vencimiento"
                                       value="{{ $produccion->fecha_vencimiento }}">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Detalle --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Detalle</label>
                        <div class="col-sm-8">
                            <textarea readonly name="detalle" class="form-control" rows="5">{{ $produccion->detalle }}</textarea>
                        </div>
                    </div>

                </div>

                {{-- Fila --}}
                <div class="col-md-12">
                    {{-- Tabla de productos --}}
                    <table class="table table-bordered" id="tblProductos">
                        <tr>
                            <th style="width: 60%">Producto</th>
                            <th style="width: 35%">Cantidad</th>
                            </th>
                        </tr>
                        @foreach($formula->componentes as $componente)
                            <tr>
                                <td>
                                    {{--Productos select--}}
                                    <select readonly required class="form-control" style="width:100%" name="productos[]" id="productos-select-id">
                                        @foreach($productos as $producto)
                                            @if($producto->id == $componente->producto_id)
                                            <option selected value="{{ $producto->id }}">{{$producto->codigo}} -- {{ $producto->nombre }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input readonly type="number" step="any" class="form-control cantidades-input"
                                               value="{{$componente->cantidad}}" name="cantidades[]">
                                        <span class="input-group-addon">g</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </table>

                </div>
                {{-- Fin fila --}}

            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('produccionLista') }}" class="btn btn-lg btn-default"><span
                            class="fa fa-close"></span> Cancelar</a>
                @if($produccion->procesado == false)
                    <button type="submit" class="btn btn-lg btn-success pull-right"><span class="fa fa-gears"></span>
                        Confirmar producción
                    </button>
                @endif
                <button type="button" class="btn btn-lg btn-warning pull-right" style="margin-right: 5px" id="ajustar-buttom-id"><span class="fa fa-edit"></span>
                    Ajustar cantidades
                </button>
            </div>

        </form>
    </div><!-- /.box -->

@endsection

@section('JSExtras')
    <script>

        $(document).on('ready', Principal());

        function Principal() {
            console.log('DOR');
            $('#ajustar-buttom-id').click(AjustarCantidades);
        }

        function AjustarCantidades() {
            $('.cantidades-input').removeAttr('readonly');
        }

        function cambioProducto() {
            let productoId = $('#productoID').val();
            let unidadMedida = $('#productoID').find(':selected').data('unidadmedida');
            let factor = $('#productoID').find(':selected').data('factor');
            console.log(factor);
            $('#unidadMedidalbl').val(unidadMedida);
            let factor_unidad = 'No hay referencia de peso para este producto';
            if (factor !== 0) {
                factor_unidad = '1 Gl = ' + factor + ' Kg';
            }
            $('#factorVolumenID').val(factor_unidad);
        }
    </script>
    @include('comun.select2Jses')
@endsection
