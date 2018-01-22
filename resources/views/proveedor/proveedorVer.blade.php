@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Ver proveedor
@endsection

@section('CSSExtras')

@endsection

@section('contentheader_title')
    Ver proveedor: {{$proveedor->nombre}}
@endsection

@section('contentheader_description')

@endsection

@section('main-content')

    @include('partials.alertas')

    <!-- Form de nuevo proveedor -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Proveedor</h3>
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
                        <label class="col-sm-2 control-label">Nombre</label>
                        <div class="col-sm-10">
                            <input disabled type="text" class="form-control" placeholder="Nombre del proveedor" name="nombre" value="{{ $proveedor->nombre }}" >
                        </div>
                    </div>
                    {{-- Contacto del proveedor --}}
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Contacto</label>
                        <div class="col-sm-10">
                            <input disabled type="text" class="form-control" placeholder="Contacto" name="contacto" value="{{ $proveedor->nombre_contacto }}" >
                        </div>
                    </div>
                    {{-- Direccion del proveedor --}}
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Direccion</label>
                        <div class="col-sm-10">
                            <input disabled type="text" class="form-control" placeholder="Direccion" name="direccion" value="{{ $proveedor->direccion }}" >
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <h4>Teléfonos</h4>
                    <br>
                    {{-- Telefono principal del proveedor --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Telefono principal</label>
                        <div class="col-sm-8">
                            <input disabled type="text" class="form-control" placeholder="(503) 9999-9999" name="telefonoPrincipal" data-inputmask='"mask": "(999) 9999-9999"' data-mask value="{{ $proveedor->telefono_1 }}" >
                        </div>
                    </div>
                    {{-- Telefono secundario del proveedor --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Telefono secundario</label>
                        <div class="col-sm-8">
                            <input disabled type="text" class="form-control" placeholder="(503) 9999-9999" name="telefonoSecundario" data-inputmask='"mask": "(999) 9999-9999"' data-mask value="{{ $proveedor->telefono_2 }}" >
                        </div>
                    </div>
                </div>
            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('proveedorLista') }}" class="btn btn-lg btn-default">Ver lista</a>
                <a href="{{ route('proveedorEditar',['id' => $proveedor->id]) }}" class="btn btn-lg btn-warning pull-right">Editar</a>
            </div>
        </form>
    </div><!-- /.box -->

@endsection

@section('JSExtras')

@endsection