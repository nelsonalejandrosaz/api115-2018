@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Nuevo cliente
@endsection

@section('CSSExtras')

@endsection

@section('contentheader_title')
    Nuevo cliente
@endsection

@section('contentheader_description')

@endsection

@section('main-content')

    @include('partials.alertas')

    <!-- Form de nuevo cliente -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Lista de clientees</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" action="{{ route('clienteNuevoPost') }}" method="POST">
            {{ csrf_field() }}
            <div class="box-body">
                <div class="col-xs-6">
                    <h4>Datos generales</h4>
                    <br>
                    {{-- Nombre del cliente --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b>Nombre cliente</b></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="Nombre del cliente" name="nombre">
                        </div>
                    </div>
                    {{-- Contacto del cliente --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Contacto</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="Contacto" name="contacto">
                        </div>
                    </div>
                    {{-- Direccion del cliente --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Direccion</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="Direccion" name="direccion">
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <h4>Teléfonos</h4>
                    <br>
                    {{-- Telefono principal del cliente --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Telefono principal</label>
                        <div class="col-sm-8">
                            <input type="tel" class="form-control" id="telefonoPrincipal" placeholder="7777-7777"
                                   name="telefonoPrincipal">
                        </div>
                    </div>
                    {{-- Telefono secundario del cliente --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Telefono secundario</label>
                        <div class="col-sm-8">
                            <input type="tel" class="form-control" id="telefonoSecundario" placeholder="7777-7777"
                                   name="telefonoSecundario">
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
