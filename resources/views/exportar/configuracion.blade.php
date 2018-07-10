@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Configuración exportación para SAC
@endsection

@section('contentheader_title')
    Configuración exportación para SAC
@endsection

@section('contentheader_description')

@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-xs-12">

                {{--Box de productos--}}
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Cuentas a utilizar</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">

                            <div class="col-md-12">


                                <form action="{{route('exportar.configuracion.store')}}" method="POST">
                                    {{ csrf_field() }}
                                    <h4>Exportar datos a SAC Resumen</h4><br>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Fecha</label>
                                        <div class="col-sm-4">
                                            <input required type="date" class="form-control" name="fecha" value="{{\Carbon\Carbon::now()->format('Y-m-d')}}">
                                        </div>
                                    </div>
                                    <br><br><br>
                                    <button type="submit" class="btn btn-lg btn-default">Exportar a CSV</button>
                                </form>

                                <br><br>

                                <form action="{{route('exportar.configuracion.store2')}}" method="POST">
                                    {{ csrf_field() }}
                                    <h4>Exportar datos a SAC Detalle</h4><br>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Fecha</label>
                                        <div class="col-sm-4">
                                            <input required type="date" class="form-control" name="fecha" value="{{\Carbon\Carbon::now()->format('Y-m-d')}}">
                                        </div>
                                    </div>
                                    <br><br><br>
                                    <button type="submit" class="btn btn-lg btn-default">Exportar a CSV</button>
                                </form>

                                <br>
                                <h4>Configuracion de cuentas</h4>
                                <table id="precios-table" class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th style="width: 20%;">ID Cuenta</th>
                                        <th style="width: 70%;">Concepto</th>
                                        <th style="width: 10%;">Accion</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($exportacion_sacs as $exportacion_sac)
                                        <tr>
                                            <td>{{$exportacion_sac->id_cuenta}}</td>
                                            <td>{{$exportacion_sac->concepto}}</td>
                                            <td><a href="{{ route('exportar.configuracion.edit', ['id' => $exportacion_sac->id]) }}"
                                                   class="btn btn-warning"><span class="fa fa-edit"></span></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            </div>

                        </div><!-- /.row -->
                    </div><!-- ./box-body -->
                    <div class="box-footer">
                        <div class="row">

                        </div><!-- /.row -->
                    </div><!-- /.box-footer -->
                </div><!-- /.box -->


            </div>
        </div>
    </div>
@endsection
