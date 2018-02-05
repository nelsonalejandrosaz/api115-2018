@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Compras
@endsection

@section('CSSExtras')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('/plugins/dataTables.bootstrap.css') }}">
@endsection

@section('contentheader_title')
    Compras
@endsection

@section('contentheader_description')
    -- Lista de compras realizadas
@endsection

@section('main-content')

    @include('partials.modalEliminar')
    @include('partials.alertas')

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header">
                    <h3 class="box-title">Lista de facturas</h3>
                    <a href="{{route('compraNueva')}}" class="btn btn-lg btn-primary pull-right"><span class="fa fa-plus"></span> Nuevo</a>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="tablaDT" class="table table-hover">
                        <thead>
                        <tr>
                            <th style="width:15%">Numero factura</th>
                            <th style="width:15%">Fecha ingreso</th>
                            <th style="width:40%">Proveedor</th>
                            <th style="width:15%">Estado</th>
                            <th style="width:15%">Acción</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($compras as $compra)
                            <tr>
                                <td>{{$compra->numero}}</td>
                                <td>{{ \Carbon\Carbon::parse($compra->fechaIngreso)->format('d/m/Y')}}</td>
                                <td>{{$compra->proveedor->nombre}}</td>
                                <td>{{$compra->estado_compra->nombre}}</td>
                                <td align="center">
                                    <a href="{{route('compraVer', ['id' => $compra->id])}}" class="btn btn-info"><span class="fa fa-eye"></span></a>
                                    @if($compra->estado_compra_id == \App\EstadoCompra::whereCodigo('INGRE')->first()->id)
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalEliminar" data-factura="{{ $compra->numero }}" data-id="{{ $compra->id }}">
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
    <script>
        $(function () {
            $('#modalEliminar').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget); // Button that triggered the modal
                let numero_compra = button.data('factura'); // Extract info from data-* attributes
                let id_compra = button.data('id');
                let ruta = '/compra/' + id_compra;
                // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                let modal = $(this);
                // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
                modal.find('#mensaje01').text('Al eliminar la compra no se podrá recuperar');
                modal.find('#mensaje02').text('Realmente desea eliminar la compra numero: ' + numero_compra);
                modal.find('#myform').attr("action", ruta);
            });
        })
    </script>
@endsection
