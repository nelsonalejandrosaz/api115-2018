@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Editar proveedor
@endsection

@section('CSSExtras')

@endsection

@section('contentheader_title')
    Editar proveedor: {{$proveedor->nombre}}
@endsection

@section('contentheader_description')

@endsection

@section('main-content')

    @include('partials.alertas')

    <!-- Form de nuevo proveedor -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Editar proveedor</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" action="{{ route('proveedorEditarPut', ['id' => $proveedor->id]) }}" method="POST">
            {{ csrf_field() }}
            {{ method_field('PUT') }}
            <div class="box-body">
                <div class="col-xs-6">
                    <h4>Datos generales</h4>
                    <br>
                    {{-- Nombre del proveedor --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b>Nombre proveedor</b></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="Nombre del proveedor" name="nombre" value="{{ $proveedor->nombre }}" >
                        </div>
                    </div>
                    {{-- Contacto del proveedor --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Contacto</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="Contacto" name="nombreContacto" value="{{ $proveedor->nombreContacto }}" >
                        </div>
                    </div>
                    {{-- Direccion del proveedor --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Direccion</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="Direccion" name="direccion" value="{{ $proveedor->direccion }}" >
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <h4>Telefonos</h4>
                    <br>
                    {{-- Telefono principal del proveedor --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Telefono principal</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="(503) 9999-9999" name="telefono1" data-inputmask='"mask": "(999) 9999-9999"' data-mask value="{{ $proveedor->telefono1 }}" >
                        </div>
                    </div>
                    {{-- Telefono secundario del proveedor --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Telefono secundario</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="(503) 9999-9999" name="telefono2" data-inputmask='"mask": "(999) 9999-9999"' data-mask value="{{ $proveedor->telefono2 }}" >
                        </div>
                    </div>
                </div>
            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('proveedorLista') }}" class="btn btn-lg btn-default">Cancelar</a>
                <button type="submit" class="btn btn-lg btn-success pull-right">Guardar</button>
            </div>
        </form>
    </div><!-- /.box -->

@endsection

@section('JSExtras')

@endsection