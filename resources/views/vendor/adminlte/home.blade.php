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
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Productos</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <div class="btn-group">
                                <button class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown"><i class="fa fa-wrench"></i></button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#">Action</a></li>
                                    <li><a href="#">Another action</a></li>
                                    <li><a href="#">Something else here</a></li>
                                    <li class="divider"></li>
                                    <li><a href="#">Separated link</a></li>
                                </ul>
                            </div>
                            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">

                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-aqua">
                                    <div class="inner">
                                        <h3>{{$estadisticas->numeroAlumnos}}</h3>

                                        <p>Alumnos registrados</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-group"></i>
                                    </div>
                                    <a href="{{route('alumnoLista')}}" class="small-box-footer">Ver lista <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <!-- ./col -->
                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-green">
                                    <div class="inner">
                                        <h3>{{$estadisticas->numeroExpCurso}}</h3>

                                        <p>Expedientes en curso</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-play"></i>
                                    </div>
                                    <a href="{{route('expedienteLista')}}" class="small-box-footer">Ver lista <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <!-- ./col -->
                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-navy-active">
                                    <div class="inner">
                                        <h3>{{$estadisticas->numeroExpFinal}}</h3>

                                        <p>Expedientes finalizados</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-check-circle"></i>
                                    </div>
                                    <a href="{{route('expedienteLista')}}" class="small-box-footer">Ver lista <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <!-- ./col -->
                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-red">
                                    <div class="inner">
                                        <h3>{{$estadisticas->numeroExpNoAbierto}}</h3>

                                        <p>Expedientes no abiertos</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-stop"></i>
                                    </div>
                                    <a href="{{route('servicioSocialLista')}}" class="small-box-footer">Ver lista <i class="fa fa-arrow-circle-right"></i></a>
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
