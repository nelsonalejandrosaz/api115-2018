@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Fórmulas
@endsection

@section('CSSExtras')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('/plugins/dataTables.bootstrap.css') }}">
@endsection

@section('contentheader_title')
    Fórmulas
@endsection

@section('contentheader_description')
    -- Lista de fórmulas ingresadas
@endsection

@section('main-content')

    @include('partials.modalEliminar')
    @include('partials.alertas')

    @if(Auth::user()->rol->nombre == 'Administrador')
    {{--Cuadro de herramientas // para colapsarlo --> collapsed-box --}}
    <div class="box box-default box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">Opciones</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
        </div><!-- /.box-header -->

        <div class="box-footer">
            <a href="{{ route('formulaNuevo') }}" class="btn btn-lg btn-primary"><span
            class="fa fa-plus"></span> Nueva Formula</a>
        </div>
    </div>
    {{--Fin cuadro de herramientas--}}
    @endif

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                {{--<div class="box-header">--}}
                    {{--<h3 class="box-title">Lista de formulas</h3>--}}
                    {{--<a href="{{ route('formulaNuevo') }}" class="btn btn-lg btn-primary pull-right"><span--}}
                                {{--class="fa fa-plus"></span> Nueva Formula</a>--}}
                {{--</div><!-- /.box-header -->--}}
                <div class="box-body table-responsive">
                    <table id="tablaDT" class="table table-hover">
                        <thead>
                        <tr>
                            <th>Código</th>
                            <th>Producto asociado</th>
                            <th>Unidad de medida</th>
                            <th>Versión</th>
                            <th>Acción</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($formulas as $formula)
                            <tr>
                                {{--{{ dd($formula) }}--}}
                                <td>{{$formula->producto->codigo}}</td>
                                <td>{{$formula->producto->nombre}}</td>
                                <td>{{$formula->producto->unidad_medida->nombre}}</td>
                                <td>{{$formula->version}}</td>
                                <td align="center">
                                    <a href="{{route('formulaVer', ['id' => $formula->id])}}" class="btn btn-info"><span
                                                class="fa fa-eye"></span></a>
                                    @if(Auth::user()->rol->nombre == 'Administrador')
                                        <a href="{{route('formulaEditar', ['id' => $formula->id])}}"
                                           class="btn btn-warning"><span class="fa fa-edit"></span></a>
                                        <button type="button" class="btn btn-danger" data-toggle="modal"
                                                data-target="#modalEliminar" data-nombre="{{ $formula->producto->nombre }}" data-id="{{ $formula->id }}">
                                            <span class="fa fa-trash"></span>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>

@endsection

@section('JSExtras')
    @include('comun.dataTablesJSes')
    <script !src="">
        $(document).ready(Principal);

        function Principal() {
            $('#modalEliminar').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget); // Button that triggered the modal
                let formula_nombre = button.data('nombre'); // Extract info from data-* attributes
                let formula_id = button.data('id');
                let ruta = '/formula/' + formula_id;
                // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                let modal = $(this);
                // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
                modal.find('#mensaje01').text('Esta punto de eliminar la formula');
                modal.find('#mensaje02').text('Realmente desea eliminar la formula de: ' + formula_nombre);
                modal.find('#myform').attr("action", ruta);
            });
        }
    </script>
@endsection
