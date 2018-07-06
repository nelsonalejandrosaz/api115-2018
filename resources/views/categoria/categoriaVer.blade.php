@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Ver categoría
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
@endsection

@section('contentheader_title')
    Ver categoría: {{$categoria->nombre}}
@endsection

@section('contentheader_description')

@endsection

@section('main-content')

    @include('partials.alertas')

    <!-- Form de nuevo producto -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Datos de la categoría</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" action="" method="GET">
            {{ csrf_field() }}
            <div class="box-body">
                <div class="col-md-6 col-sm-12">
                    <h4>Datos generales</h4>
                    <br>

                    {{-- Codigo de la categoria --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Código categoría</label>
                        <div class="col-sm-8">
                            <input disabled type="text" class="form-control" placeholder="ACT" name="codigo"
                                   value="{{$categoria->codigo}}">
                        </div>
                    </div>

                    {{-- Nombre de la categoría --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Nombre categoría</label>
                        <div class="col-sm-8">
                            <input disabled type="text" class="form-control" placeholder="Categoría" name="nombre"
                                   value="{{$categoria->nombre}}">
                        </div>
                    </div>

                    {{-- Descripción de la categoría --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Descripción</label>
                        <div class="col-sm-8">
                            <textarea disabled class="form-control" name="nombre" placeholder="Descripción sobre la categoria">{{$categoria->descripcion}}</textarea>
                        </div>
                    </div>
                </div>

            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('categoriaLista') }}" class="btn btn-lg btn-default">Ver lista</a>
                <a href="{{ route('categoriaEditar',['id' => $categoria->id]) }}" class="btn btn-lg btn-warning pull-right">Editar</a>
            </div>
        </form>
    </div><!-- /.box -->

@endsection

@section('JSExtras')
    @include('comun.select2Jses')
@endsection
