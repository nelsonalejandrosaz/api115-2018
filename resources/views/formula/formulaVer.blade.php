@extends('adminlte::layouts.app')

@section('htmlheader_title')
    {{-- {{ trans('message.tituloProveedorNuevo') }} --}}
    Detalle de formula
@endsection

@section('CSSx')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
    {{-- DataPicker --}}
    <link rel="stylesheet" href="{{asset('/css/datapicker/bootstrap-datepicker3.css')}}">
@endsection

@section('contentheader_title')
    {{-- {{ trans('message.tituloProveedorNuevo') }} --}}
    Detalle de formula
@endsection

@section('contentheader_description')

@endsection

@section('main-content')

    @include('partials.alertas')

    <!-- Form de nuevo proveedor -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Detalle de formula</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form id="formDatos" class="form-horizontal">
            {{ csrf_field() }}
            <div class="box-body">
                {{-- Fila  --}}
                <div class="col-md-6">
                    {{-- Producto --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label"><b>Producto asociado:</b></label>
                        <div class="col-md-8">
                            <select class="form-control select2" style="width:100%" name="producto_id" disabled>
                                @foreach($productos as $producto)
                                    @if($formula->producto_id == $producto->id)
                                        <option selected value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                                    @else
                                        <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    {{-- Fecha --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label"><b>Fecha ingreso:</b></label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control datepicker" name="fechaIngreso" value="{{$formula->fechaIngreso}}" disabled>
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Fin fila --}}

                {{-- Fila  --}}
                <div class="col-md-6">
                    {{-- Descripcion --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label">Descripción:</label>
                        <div class="col-md-8">
                            <textarea class="form-control" name="descripcion" disabled>{{$formula->descripcion}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    {{-- Ingresado por --}}
                    <div class="form-group">
                        <label class="col-md-4 control-label">Ingresado por:</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" value="{{$formula->ingresadoPor}}" name="ingresadoPor" disabled>
                        </div>
                    </div>
                </div>
                {{-- Fin fila --}}


                {{-- Fila --}}
                <div class="col-md-12">
                    {{-- Tabla de productos --}}
                    <table class="table table-bordered" id="tblProductos">
                        <tr>
                            <th style="width: 5%">#</th>
                            <th style="width: 50%">Producto</th>
                            <th style="width: 30%">Porcentaje</th>
                            <th style="width: 15%">
                            </th>
                        </tr>
                        @php( $i = 1 )
                        @foreach($formula->componentes as $componente)
                            <tr>
                                <td>
                                    {{$i}}
                                </td>
                                <td>
                                    <select class="form-control select2 selProd" style="width:100%" name="productos[]" id="selectProductos" disabled>
                                        @foreach($productos as $producto)
                                            @if($componente->producto_id == $producto->id)
                                                <option selected value="{{ $producto->id }}" data-um="{{ $producto->unidadMedida->abreviatura }}">{{ $producto->nombre }}</option>
                                            @else
                                                <option value="{{ $producto->id }}" data-um="{{ $producto->unidadMedida->abreviatura }}">{{ $producto->nombre }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="number" class="form-control cant" value="{{$componente->porcentaje}}" name="porcentajes[]" disabled>
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </td>
                                <td align="center">
                                    {{-- <div id="a1" class="btn btn-danger">
                                        <span class="fa fa-remove"></span>
                                    </div> --}}
                                </td>
                            </tr>
                            @php( $i++ )
                        @endforeach
                    </table>
                    <table class="table table-bordered">
                        <th style="width: 5%"></th>
                        <th style="width: 50%; text-align: right; vertical-align: middle;">Total:</th>
                        <th style="width: 30%">
                            <div class="input-group">
                                <input type="number" class="form-control" placeholder="0" id="totalPorcentajeInput" min="100" max="100" value="0" disabled>
                                <span class="input-group-addon">%</span>
                            </div>
                        </th>
                        <th style="width: 15%"></th>
                    </table>
                </div>
                {{-- Fin fila --}}

            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('formulaLista') }}" class="btn btn-lg btn-default">Lista de formulas</a>
            </div>
        </form>
    </div><!-- /.box -->

@endsection

@section('JSx')
    {{-- Funcion para cargar mas filas de productos --}}
    <script>
        $(document).on('ready', funcionPrincipal());

        function funcionPrincipal() {
            $("body").on( "click", ".btn-danger",funcionEliminarProducto);
            calcularTotal();
        }
        var numero = 2;

        function funcionNuevoProducto() {
            copia = $('#selectProductos').clone(false);
            $('#tblProductos')
                .append
                (
                    $('<tr>').attr('id','rowProducto'+numero)
                        .append
                        (
                            $('<td>')
                                .append
                                (
                                    numero
                                )
                        )
                        .append
                        (
                            $('<td>')
                                .append
                                (
                                    copia
                                )
                        )
                        .append
                        (
                            $('<td>')
                                .append
                                (
                                    '<div class="input-group"><input type="number" class="form-control cant" value="1" min="1" max="100" name="porcentajes[]" onchange="calcularTotal()"><span class="input-group-addon">%</span></div>'
                                )
                        )
                        .append
                        (
                            $('<td>').attr('align','center')
                                .append
                                (
                                    '<button type="button" class="btn btn-danger" click="funcionEliminarProducto()" type="button"><span class="fa fa-remove"></span></button>'
                                )
                        )
                );
            //Initialize Select2 Elements
            $(".select2").select2();
            $(".select2").select2();
            numero++;
            calcularTotal();
        }

        function funcionEliminarProducto() {
            // $(this).remove().end();
            // $(this).closest('tr').remove();
            // console.log($(this).parent().parent());
            $(this).parent().parent().remove();
            calcularTotal();
        }

        function verificarSuma() {
            var total = $('#totalPorcentajeInput').val();
            if (total != 100) {
                $('#divErrorSuma').show();
                setTimeout(function(){
                    $('#divErrorSuma').hide();
                }, 3000);
            } else {
                $('#formDatos').submit();
            }
        }

        function calcularTotal() {
            var totalPorcentaje = 0;
            var porcentajes = $('.cant');
            for (var i = 0; i < porcentajes.length; i++) {
                porcentaje = parseFloat(porcentajes[i].value);
                totalPorcentaje = totalPorcentaje + porcentaje;
            }
            $('#totalPorcentajeInput').val(totalPorcentaje);
            // console.log(totalPorcentaje);
        }

    </script>
    {{-- Fin de funcion para cargar mas filas de productos --}}

    <!-- Select2 -->
    <script src="{{asset('/plugins/select2.full.min.js')}}"></script>
    {{-- Data Picker --}}
    <script src="{{asset('/js/datapicker/bootstrap-datepicker.js')}}"></script>
    <script>
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
            // Inicializar el datapicker
            $('.datepicker').datepicker(
                {
                    format: "yyyy/mm/dd",
                    todayBtn: "linked",
                    language: "es",
                    autoclose: true
                });

        });
    </script>
@endsection

