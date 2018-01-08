@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Ver cliente
@endsection

@section('CSSExtras')

@endsection

@section('contentheader_title')
    Ver cliente: {{$cliente->nombre}}
@endsection

@section('contentheader_description')

@endsection

@section('main-content')

    @include('partials.alertas')

    <!-- Form de nuevo cliente -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Proveedor</h3>
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
                        <label class="col-sm-2 control-label">Nombre</label>
                        <div class="col-sm-10">
                            <input disabled type="text" class="form-control" placeholder="Nombre del cliente"
                                   name="nombre" value="{{ $cliente->nombre }}">
                        </div>
                    </div>
                    {{-- Contacto del cliente --}}
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Contacto</label>
                        <div class="col-sm-10">
                            <input disabled type="text" class="form-control" placeholder="Contacto" name="contacto"
                                   value="{{ $cliente->nombreContacto }}">
                        </div>
                    </div>
                    {{-- Direccion del cliente --}}
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Direccion</label>
                        <div class="col-sm-10">
                            <input disabled type="text" class="form-control" placeholder="Direccion" name="direccion"
                                   value="{{ $cliente->direccion }}">
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
                            <input disabled type="text" class="form-control" placeholder="(503) 9999-9999"
                                   name="telefonoPrincipal" data-inputmask='"mask": "(999) 9999-9999"' data-mask
                                   value="{{ $cliente->telefono1 }}">
                        </div>
                    </div>

                    {{-- Telefono secundario del cliente --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Telefono secundario</label>
                        <div class="col-sm-8">
                            <input disabled type="text" class="form-control" placeholder="(503) 9999-9999"
                                   name="telefonoSecundario" data-inputmask='"mask": "(999) 9999-9999"' data-mask
                                   value="{{ $cliente->telefono2 }}">
                        </div>
                    </div>

                    {{-- NIT del cliente --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">NIT</label>
                        <div class="col-sm-8">
                            <input disabled type="text" class="form-control" placeholder="NIT" name="nit"
                                   value="{{$cliente->nit}}">
                        </div>
                    </div>

                    {{-- NRC del cliente --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">NRC</label>
                        <div class="col-sm-8">
                            <input disabled type="text" class="form-control" placeholder="Número Registro de Comercio"
                                   name="nrc" value="{{$cliente->nrc}}">
                        </div>
                    </div>

                    {{--Vendedor--}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Vendedor</label>
                        <div class="col-sm-8">
                            <select disabled class="form-control select2" name="unidad_medida_id">
                                <option value="" selected disabled>Sin vendedor especificado</option>
                                @foreach($vendedores as $vendedor)
                                    @if($vendedor->id == $cliente->vendedor_id)
                                        <option selected value="{{ $vendedor->id }}">{{ $vendedor->nombre }} {{ $vendedor->apellido }}</option>
                                    @else
                                        <option value="{{ $vendedor->id }}">{{ $vendedor->nombre }} {{ $vendedor->apellido }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>
            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('clienteLista') }}" class="btn btn-lg btn-default">Ver lista</a>
                <a href="{{ route('clienteEditar',['id' => $cliente->id]) }}" class="btn btn-lg btn-warning pull-right">Editar</a>
            </div>
        </form>
    </div><!-- /.box -->

@endsection

@section('JSExtras')

@endsection