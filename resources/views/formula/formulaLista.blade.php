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

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Lista de formulas</h3>
                    <a href="{{ route('formulaNuevo') }}" class="btn btn-lg btn-primary pull-right"><span
                                class="fa fa-plus"></span> Nueva Formula</a>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="tablaDT" class="table table-hover">
                        <thead>
                        <tr>
                            <th>Código</th>
                            <th>Producto asociado</th>
                            <th>Unidad de medida</th>
                            <th>Acción</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($formulas as $formula)
                            <tr>
                                <td>{{$formula->producto->codigo}}</td>
                                <td>{{$formula->producto->nombre}}</td>
                                <td>{{$formula->producto->unidadMedida->nombre}}</td>
                                <td align="center">
                                    <a href="{{route('formulaVer', ['id' => $formula->id])}}" class="btn btn-info"><span
                                                class="fa fa-eye"></span></a>
                                    <a href="{{route('formulaEditar', ['id' => $formula->id])}}"
                                       class="btn btn-warning"><span class="fa fa-edit"></span></a>
                                    <button type="button" class="btn btn-danger" data-toggle="modal"
                                            data-target="#modalEliminar" data-producto="Producto 1" data-id="1">
                                        <span class="fa fa-trash"></span>
                                    </button>
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
@endsection