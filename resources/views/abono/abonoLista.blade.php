@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Abonos realizados
@endsection

@section('CSSExtras')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('/plugins/dataTables.bootstrap.css') }}">
@endsection

@section('contentheader_title')
    Abonos realizados
@endsection

@section('contentheader_description')
    -- Lista de clientes
@endsection

@section('main-content')

    @include('partials.modalEliminar')

    @include('partials.alertas')

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header">
                    <h3 class="box-title">Abonos</h3>
                    <a href="{{ route('abonoNuevoSinVenta') }}" class="btn btn-lg btn-primary pull-right"><span
                                class="fa fa-plus"></span> Nuevo abono</a>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="tablaDT" class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width:10%">Fecha</th>
                                <th style="width:10%">N° Documento</th>
                                <th style="width:40%">Nombre cliente</th>
                                <th style="width:20%">Cantidad abono</th>
                                <th style="width:10%">Tipo abono</th>
                                <th style="width:10%">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($abonos as $abono)
                            <tr>
                                <td>{{ $abono->fecha->format('d/m/Y') }}</td>
                                <td>{{$abono->venta->numero}}</td>
                                <td>{{$abono->cliente->nombre}}</td>
                                <td>$ {{number_format($abono->cantidad,2)}}</td>
                                <td>
                                    @if($abono->forma_pago->codigo == 'EFECT')
                                        <span class="label label-success">{{ $abono->forma_pago->nombre }}</span>
                                    @elseif($abono->forma_pago->codigo == 'CHEQU')
                                        <span class="label label-default">{{ $abono->forma_pago->nombre }}</span>
                                    @elseif($abono->forma_pago->codigo == 'DEPOS')
                                        <span class="label label-primary">{{ $abono->forma_pago->nombre }}</span>
                                    @elseif($abono->forma_pago->codigo == 'RETEN')
                                        <span class="label label-info">{{ $abono->forma_pago->nombre }}</span>
                                    @endif
                                </td>
                                {{--<td>{{$cliente->telefono_1}}</td>--}}
                                <td align="center">
                                    <a href="{{ route('abonoVer', ['id' => $abono->id]) }}" class="btn btn-info"><span class="fa fa-eye"></span></a>
                                    {{--<a href="{{ route('clienteEditar', ['id' => $abono->id]) }}"--}}
                                       {{--class="btn btn-warning"><span class="fa fa-edit"></span></a>--}}
                                    {{--<button type="button" class="btn btn-danger" data-toggle="modal"--}}
                                            {{--data-target="#modalEliminar" data-objeto="{{ $abono->nombre }}"--}}
                                            {{--data-id="{{ $abono->id }}" data-ruta="cliente">--}}
                                        {{--<span class="fa fa-trash"></span>--}}
                                    {{--</button>--}}
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
    <script src="{{ asset('/js/modal-eliminar.js') }}"></script>
    @include('comun.dataTablesJSes')
@endsection