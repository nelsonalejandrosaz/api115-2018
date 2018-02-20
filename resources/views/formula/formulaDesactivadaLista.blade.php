@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Histórico de fórmulas
@endsection

@section('CSSExtras')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('/plugins/dataTables.bootstrap.css') }}">
@endsection

@section('contentheader_title')
    Histórico de fórmulas
@endsection

@section('contentheader_description')
    -- Lista de histórico de fórmulas
@endsection

@section('main-content')

    @include('partials.modalEliminar')
    @include('partials.alertas')

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Lista de histórico de formulas</h3>
                </div><!-- /.box-header -->
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
