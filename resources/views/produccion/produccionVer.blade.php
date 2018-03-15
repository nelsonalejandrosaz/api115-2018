@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Ver producción
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
@endsection

@section('contentheader_title')
    Ver producción
@endsection

@section('contentheader_description')
    -- Detalles de la producción
@endsection

@section('main-content')

    @include('partials.alertas')

    <!-- Form de nuevo producto -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Datos del producto</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal">
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
                                <input readonly type="date" class="form-control" name="fecha" value="{{ $produccion->fecha->format('Y-m-d') }}">
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
                                <option selected value="{{ $produccion->producto->id }}"
                                        data-unidadmedida="{{$produccion->producto->unidad_medida->nombre}}"
                                        data-factor="{{$produccion->producto->factor_volumen}}">{{ $produccion->producto->nombre }}</option>
                            </select>
                        </div>
                    </div>

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
                            <th style="width:80%">Producto </th>
                            <th style="width:20%">Cantidad</th>
                        </tr>
                        @foreach($produccion->salidas as $salida)
                            <tr>
                                {{--Productos--}}
                                <td>
                                    <select style="width:100%" class="form-control select2 selProd" name="productos_id[]"
                                            id="selectProductos" disabled>
                                        @foreach($productos as $producto)
                                            @if($producto->id == $salida->movimiento->producto_id)
                                                <option selected value="{{ $producto->id }}"
                                                        data-cu="{{ $producto->precio }}"
                                                        data-um="{{$producto->unidad_medida->abreviatura}}">{{$producto->codigo}} -- {{ $producto->nombre }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </td>

                                {{--Cantidad--}}
                                <td>
                                    <input readonly type="text" class="form-control cantidadCls" name="cantidades[]"
                                           id="cantidad" value="{{number_format($salida->cantidad,4)}} {{$salida->unidad_medida->abreviatura}}">
                                </td>
                            </tr>
                        @endforeach
                    </table>

                </div>

            </div><!-- /.box-body -->
        </form>
        <div class="box-footer">
            <a href="{{ route('produccionLista') }}" class="btn btn-lg btn-default"><span class="fa fa-mail-reply"></span> Regresar a producciones</a>
            <a href="" style="margin-left: 10px" class="btn btn-lg btn-success pull-right"><span class="fa fa-print"></span> Imprimir producción</a>
            @if($produccion->trashed() == false)
                <button type="button" class="btn btn-lg btn-danger pull-right" id="eliminar-buttom-id"><span class="fa fa-print"></span> Revertir producción</button>
            @endif
        </div>
    </div><!-- /.box -->

    <div hidden>
        <form action="{{ route('produccionRevertir',['id' => $produccion->id]) }}" method="post" id="eliminar-frm-id">
            {{ csrf_field() }}
            {{method_field('DELETE')}}
        </form>
    </div>

@endsection

@section('JSExtras')
    <script>

        $(document).on('ready', Principal());

        function Principal() {
            $('#eliminar-buttom-id').click(SubmitEliminar)
        }

        function SubmitEliminar() {
            $('#eliminar-frm-id').submit();

        }

    </script>
    @include('comun.select2Jses')
@endsection
