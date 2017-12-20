@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Editar cliente
@endsection

@section('CSSExtras')

@endsection

@section('contentheader_title')
    Editar cliente: {{$cliente->nombre}}
@endsection

@section('contentheader_description')

@endsection

@section('main-content')

    @include('partials.alertas')

    <!-- Form de nuevo cliente -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Editar cliente</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" action="{{ route('clienteEditarPut', ['id' => $cliente->id]) }}" method="POST">
            {{ csrf_field() }}
            {{ method_field('PUT') }}
            <div class="box-body">
                <div class="col-xs-6">
                    <h4>Datos generales</h4>
                    <br>
                    {{-- Nombre del cliente --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b>Nombre cliente</b></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="Nombre del cliente" name="nombre" value="{{ $cliente->nombre }}" >
                        </div>
                    </div>
                    {{-- Contacto del cliente --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Contacto</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="Contacto" name="nombreContacto" value="{{ $cliente->nombreContacto }}" >
                        </div>
                    </div>
                    {{-- Direccion del cliente --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Direccion</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="Direccion" name="direccion" value="{{ $cliente->direccion }}" >
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <h4>Telefonos</h4>
                    <br>
                    {{-- Telefono principal del cliente --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Telefono principal</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="(503) 9999-9999" name="telefono1" data-inputmask='"mask": "(999) 9999-9999"' data-mask value="{{ $cliente->telefono1 }}" >
                        </div>
                    </div>
                    {{-- Telefono secundario del cliente --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Telefono secundario</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="(503) 9999-9999" name="telefono2" data-inputmask='"mask": "(999) 9999-9999"' data-mask value="{{ $cliente->telefono2 }}" >
                        </div>
                    </div>
                </div>
            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('clienteLista') }}" class="btn btn-lg btn-default">Cancelar</a>
                <button type="submit" class="btn btn-lg btn-success pull-right">Guardar</button>
            </div>
        </form>
    </div><!-- /.box -->

@endsection

@section('JSExtras')

@endsection