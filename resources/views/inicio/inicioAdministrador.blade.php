@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Inicio
@endsection

@section('contentheader_title')
    Inicio del sistema de facturación e inventario
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
                        <h3 class="box-title">Informacion de los productos en inventario</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            {{--<div class="btn-group">--}}
                                {{--<button class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown"><i class="fa fa-wrench"></i></button>--}}
                                {{--<ul class="dropdown-menu" role="menu">--}}
                                    {{--<li><a href="#">Action</a></li>--}}
                                    {{--<li><a href="#">Another action</a></li>--}}
                                    {{--<li><a href="#">Something else here</a></li>--}}
                                    {{--<li class="divider"></li>--}}
                                    {{--<li><a href="#">Separated link</a></li>--}}
                                {{--</ul>--}}
                            {{--</div>--}}
                            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">

                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-blue">
                                    <div class="inner">
                                        <h3>{{ $reportes['productos_total'] }}</h3>

                                        <p>Productos registrados</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-barcode"></i>
                                    </div>
                                    <a href="" class="small-box-footer">Ver lista <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <!-- ./col -->

                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-green">
                                    <div class="inner">
                                        <h3>{{$reportes['productos_existencia_alta']}}</h3>

                                        <p>Con existencia alta</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-check-square"></i>
                                    </div>
                                    <a href="" class="small-box-footer">Ver lista <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <!-- ./col -->

                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-yellow">
                                    <div class="inner">
                                        <h3>{{$reportes['productos_existencia_media']}}</h3>

                                        <p>Con existencia media</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-exclamation-triangle"></i>
                                    </div>
                                    <a href="" class="small-box-footer">Ver lista <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <!-- ./col -->

                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-red">
                                    <div class="inner">
                                        <h3>{{$reportes['productos_existencia_baja']}}</h3>

                                        <p>Con existencia baja</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-close"></i>
                                    </div>
                                    <a href="" class="small-box-footer">Ver lista <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <!-- ./col -->


                        </div><!-- /.row -->
                    </div><!-- ./box-body -->
                    <div class="box-footer">
                        <div class="row">

                        </div><!-- /.row -->
                    </div><!-- /.box-footer -->
                </div><!-- /.box -->

                {{--Box de clientes--}}
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Información de los clientes</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            {{--<div class="btn-group">--}}
                            {{--<button class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown"><i class="fa fa-wrench"></i></button>--}}
                            {{--<ul class="dropdown-menu" role="menu">--}}
                            {{--<li><a href="#">Action</a></li>--}}
                            {{--<li><a href="#">Another action</a></li>--}}
                            {{--<li><a href="#">Something else here</a></li>--}}
                            {{--<li class="divider"></li>--}}
                            {{--<li><a href="#">Separated link</a></li>--}}
                            {{--</ul>--}}
                            {{--</div>--}}
                            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">

                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-blue">
                                    <div class="inner">
                                        <h3>{{ $reportes['clientes_total'] }}</h3>

                                        <p>Clientes registrados</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-users"></i>
                                    </div>
                                    <a href="" class="small-box-footer">Ver lista <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <!-- ./col -->

                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-aqua">
                                    <div class="inner">
                                        <h3>{{$reportes['clientes_con_saldo']}}</h3>

                                        <p>Con saldos activos</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-money"></i>
                                    </div>
                                    <a href="" class="small-box-footer">Ver lista <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <!-- ./col -->

                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-green">
                                    <div class="inner">
                                        <h3>{{$reportes['clientes_con_saldo'] - 15}}</h3>

                                        <p>Crédito en tiempo</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-check-square"></i>
                                    </div>
                                    <a href="" class="small-box-footer">Ver lista <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <!-- ./col -->

                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-yellow">
                                    <div class="inner">
                                        <h3>{{15}}</h3>

                                        <p>Crédito vencido</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-exclamation-triangle"></i>
                                    </div>
                                    <a href="" class="small-box-footer">Ver lista <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <!-- ./col -->


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
