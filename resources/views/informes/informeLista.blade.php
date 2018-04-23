@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Informes
@endsection

@section('CSSExtras')
    <style type="text/css">
        .small-box h3 {
            font-size: 30px;
        }
    </style>
@endsection

@section('contentheader_title')
    Listado de informes
@endsection

@section('contentheader_description')

@endsection

@section('main-content')

    <div class="container-fluid spark-screen">

        <div class="row">
            <div class="col-xs-12">

                {{--Box de informes diarios--}}
                <div class="box box-default box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Informes diarios</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">

                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-blue">
                                    <div class="inner">
                                        <h3 style="font-size: 25px">Ingreso</h3>
                                        <h3 style="font-size: 25px">diarios</h3>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-money"></i>
                                    </div>
                                    <a href="{{ route('ingresoVentas') }}" class="small-box-footer">Ver informe <i
                                                class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <!-- ./col -->

                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-blue">
                                    <div class="inner">
                                        <h3 style="font-size: 25px">Informe</h3>
                                        <h3 style="font-size: 25px">facturación</h3>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-shopping-bag"></i>
                                    </div>
                                    <a href="{{ route('facturacion', ['dia' => 'dia']) }}" class="small-box-footer">Ver
                                        informe <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <!-- ./col -->

                            {{--<div class="col-lg-3 col-xs-6">--}}
                            {{--<!-- small box -->--}}
                            {{--<div class="small-box bg-yellow">--}}
                            {{--<div class="inner">--}}
                            {{--<h3 style="font-size: 25px">Precios</h3>--}}
                            {{--<h3 style="font-size: 25px">productos</h3>--}}
                            {{--</div>--}}
                            {{--<div class="icon">--}}
                            {{--<i class="fa fa-book"></i>--}}
                            {{--</div>--}}
                            {{--<a href="{{ route('productoPreciosInforme') }}" class="small-box-footer">Ver informe <i class="fa fa-arrow-circle-right"></i></a>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--<!-- ./col -->--}}

                            {{--<div class="col-lg-3 col-xs-6">--}}
                            {{--<!-- small box -->--}}
                            {{--<div class="small-box bg-red">--}}
                            {{--<div class="inner">--}}
                            {{--<h3 style="font-size: 25px">Movimientos</h3>--}}
                            {{--<h3 style="font-size: 25px">producto</h3>--}}
                            {{--</div>--}}
                            {{--<div class="icon">--}}
                            {{--<i class="fa fa-book"></i>--}}
                            {{--</div>--}}
                            {{--<a href="{{ route('productoMovimiento') }}" class="small-box-footer">Ver informe <i class="fa fa-arrow-circle-right"></i></a>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--<!-- ./col -->--}}
                        </div><!-- /.row -->
                    </div><!-- ./box-body -->
                    <div class="box-footer">
                        <div class="row">

                        </div><!-- /.row -->
                    </div><!-- /.box-footer -->
                </div>
                {{--Fin box --}}

                {{--Box de informes cxc--}}
                <div class="box box-default box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Cuentas por cobrar</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">

                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-blue">
                                    <div class="inner">
                                        <h3 style="font-size: 25px">Por</h3>
                                        <h3 style="font-size: 25px">antigüedad</h3>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <a href="{{ route('cxcAntiguedad') }}" class="small-box-footer">Ver informe <i
                                                class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <!-- ./col -->

                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-gray">
                                    <div class="inner">
                                        <h3 style="font-size: 25px">Por</h3>
                                        <h3 style="font-size: 25px">cliente</h3>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-newspaper-o"></i>
                                    </div>
                                    <a href="" class="small-box-footer">Ver lista <i
                                                class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <!-- ./col -->

                            {{--<div class="col-lg-3 col-xs-6">--}}
                            {{--<!-- small box -->--}}
                            {{--<div class="small-box bg-yellow">--}}
                            {{--<div class="inner">--}}
                            {{--<h3 style="font-size: 25px">Precios</h3>--}}
                            {{--<h3 style="font-size: 25px">productos</h3>--}}
                            {{--</div>--}}
                            {{--<div class="icon">--}}
                            {{--<i class="fa fa-book"></i>--}}
                            {{--</div>--}}
                            {{--<a href="" class="small-box-footer">Ver lista <i class="fa fa-arrow-circle-right"></i></a>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--<!-- ./col -->--}}

                            {{--<div class="col-lg-3 col-xs-6">--}}
                            {{--<!-- small box -->--}}
                            {{--<div class="small-box bg-red">--}}
                            {{--<div class="inner">--}}
                            {{--<h3 style="font-size: 25px">qwerty</h3>--}}
                            {{--<h3 style="font-size: 25px">qwerty</h3>--}}
                            {{--</div>--}}
                            {{--<div class="icon">--}}
                            {{--<i class="fa fa-book"></i>--}}
                            {{--</div>--}}
                            {{--<a href="" class="small-box-footer">Ver lista <i class="fa fa-arrow-circle-right"></i></a>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--<!-- ./col -->--}}


                        </div><!-- /.row -->
                    </div><!-- ./box-body -->
                    <div class="box-footer">
                        <div class="row">

                        </div><!-- /.row -->
                    </div><!-- /.box-footer -->
                </div>
                {{--Fin box --}}

                {{--Box de informes de productos--}}
                <div class="box box-default box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Informe de productos</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">

                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-blue">
                                    <div class="inner">
                                        <h3 style="font-size: 25px">Inventario</h3>
                                        <h3 style="font-size: 25px">existencia</h3>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-th-list"></i>
                                    </div>
                                    <a href="{{ route('productoExistenciaInforme') }}" class="small-box-footer">Ver
                                        informe <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <!-- ./col -->

                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-blue">
                                    <div class="inner">
                                        <h3 style="font-size: 25px">Precios</h3>
                                        <h3 style="font-size: 25px">productos</h3>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-dollar"></i>
                                    </div>
                                    <a href="{{ route('productoPreciosInforme') }}" class="small-box-footer">Ver informe
                                        <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <!-- ./col -->

                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-blue">
                                    <div class="inner">
                                        <h3 style="font-size: 25px">Produccion</h3>
                                        <h3 style="font-size: 25px">por producto</h3>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-gears"></i>
                                    </div>
                                    <a href="{{ route('informeProducciones') }}" class="small-box-footer">Ver informe <i
                                                class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <!-- ./col -->

                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-blue">
                                    <div class="inner">
                                        <h3 style="font-size: 25px">Movimientos</h3>
                                        <h3 style="font-size: 25px">ajustes</h3>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-exchange"></i>
                                    </div>
                                    <a href="{{ route('movimientosAjuste') }}" class="small-box-footer">Ver informe <i
                                                class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <!-- ./col -->

                        </div><!-- /.row -->
                        <div class="row">

                            {{--<div class="col-lg-3 col-xs-6">--}}
                            {{--<!-- small box -->--}}
                            {{--<div class="small-box bg-gray">--}}
                            {{--<div class="inner">--}}
                            {{--<h3 style="font-size: 25px">Movimientos</h3>--}}
                            {{--<h3 style="font-size: 25px">producto</h3>--}}
                            {{--</div>--}}
                            {{--<div class="icon">--}}
                            {{--<i class="fa fa-exchange"></i>--}}
                            {{--</div>--}}
                            {{--<a href="{{ route('productoMovimiento') }}" class="small-box-footer">Ver informe <i--}}
                            {{--class="fa fa-arrow-circle-right"></i></a>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--<!-- ./col -->--}}

                            {{--<div class="col-lg-3 col-xs-6">--}}
                            {{--<!-- small box -->--}}
                            {{--<div class="small-box bg-gray">--}}
                            {{--<div class="inner">--}}
                            {{--<h3 style="font-size: 25px">Costos</h3>--}}
                            {{--<h3 style="font-size: 25px">productos</h3>--}}
                            {{--</div>--}}
                            {{--<div class="icon">--}}
                            {{--<i class="fa fa-dollar"></i>--}}
                            {{--</div>--}}
                            {{--<a href="" class="small-box-footer">Ver informe <i--}}
                            {{--class="fa fa-arrow-circle-right"></i></a>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--<!-- ./col -->--}}

                        </div>
                    </div><!-- ./box-body -->
                    <div class="box-footer">
                        <div class="row">

                        </div><!-- /.row -->
                    </div><!-- /.box-footer -->
                </div>
                {{--Fin box --}}

                {{--Box de informes ventas--}}
                <div class="box box-default box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Informe de ventas</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">

                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-blue">
                                    <div class="inner">
                                        <h3 style="font-size: 25px">Informe</h3>
                                        <h3 style="font-size: 25px">ventas</h3>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <a href="{{ route('informeVentas') }}" class="small-box-footer">Ver
                                        informe <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <!-- ./col -->

                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-blue">
                                    <div class="inner">
                                        <h3 style="font-size: 25px">Por</h3>
                                        <h3 style="font-size: 25px">clientes</h3>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-list-alt"></i>
                                    </div>
                                    <a href="{{ route('informeVentasPorCliente') }}" class="small-box-footer">Ver informe <i
                                                class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <!-- ./col -->

                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-blue">
                                    <div class="inner">
                                        <h3 style="font-size: 25px">Por</h3>
                                        <h3 style="font-size: 25px">vendedor</h3>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <a href="{{route('informeVentasPorVendedor')}}" class="small-box-footer">Ver informe <i
                                                class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <!-- ./col -->

                            {{--<div class="col-lg-3 col-xs-6">--}}
                                {{--<!-- small box -->--}}
                                {{--<div class="small-box bg-blue">--}}
                                    {{--<div class="inner">--}}
                                        {{--<h3 style="font-size: 25px">Libro</h3>--}}
                                        {{--<h3 style="font-size: 25px">ventas</h3>--}}
                                    {{--</div>--}}
                                    {{--<div class="icon">--}}
                                        {{--<i class="fa fa-cube"></i>--}}
                                    {{--</div>--}}
                                    {{--<a href="{{route('informeLibroCompras')}}" class="small-box-footer">Ver lista <i--}}
                                                {{--class="fa fa-arrow-circle-right"></i></a>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<!-- ./col -->--}}

                        </div><!-- /.row -->

                    </div><!-- ./box-body -->
                    <div class="box-footer">
                        <div class="row">

                        </div><!-- /.row -->
                    </div><!-- /.box-footer -->
                </div>
                {{--Fin box --}}

                {{--Box de informes compras--}}
                <div class="box box-default box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Informes de compras</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">

                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-blue">
                                    <div class="inner">
                                        <h3 style="font-size: 25px">Informe</h3>
                                        <h3 style="font-size: 25px">compras</h3>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <a href="{{route('informeCompras')}}" class="small-box-footer">Ver
                                        informe <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <!-- ./col -->

                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-blue">
                                    <div class="inner">
                                        <h3 style="font-size: 25px">Por</h3>
                                        <h3 style="font-size: 25px">proveedor</h3>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-truck"></i>
                                    </div>
                                    <a href="{{route('informeComprasProveedor')}}" class="small-box-footer">Ver
                                        informe <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <!-- ./col -->

                            <div class="col-lg-3 col-xs-6">

                                <!-- small box -->
                                {{--<div class="small-box bg-blue">--}}
                                    {{--<div class="inner">--}}
                                        {{--<h3 style="font-size: 25px">Libro</h3>--}}
                                        {{--<h3 style="font-size: 25px">compras</h3>--}}
                                    {{--</div>--}}
                                    {{--<div class="icon">--}}
                                        {{--<i class="fa fa-cube"></i>--}}
                                    {{--</div>--}}
                                    {{--<a href="{{route('informeLibroCompras')}}" class="small-box-footer">Ver lista <i--}}
                                                {{--class="fa fa-arrow-circle-right"></i></a>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<!-- ./col -->--}}

                            {{--<div class="col-lg-3 col-xs-6">--}}
                                {{--<!-- small box -->--}}
                                {{--<div class="small-box bg-gray">--}}
                                    {{--<div class="inner">--}}
                                        {{--<h3 style="font-size: 25px">Por</h3>--}}
                                        {{--<h3 style="font-size: 25px">producto</h3>--}}
                                    {{--</div>--}}
                                    {{--<div class="icon">--}}
                                        {{--<i class="fa fa-cube"></i>--}}
                                    {{--</div>--}}
                                    {{--<a href="" class="small-box-footer">Ver lista <i--}}
                                                {{--class="fa fa-arrow-circle-right"></i></a>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<!-- ./col -->--}}


                        </div><!-- /.row -->
                    </div><!-- ./box-body -->
                    <div class="box-footer">
                        <div class="row">

                        </div><!-- /.row -->
                    </div><!-- /.box-footer -->
                </div>
                {{--Fin box --}}

            </div>
        </div>
    </div>
@endsection
