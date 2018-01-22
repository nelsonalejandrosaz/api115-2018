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
                        <label class="col-sm-3 control-label">Nombre</label>
                        <div class="col-sm-9">
                            <input disabled type="text" class="form-control" placeholder="Nombre del cliente"
                                   name="nombre" value="{{ $cliente->nombre }}">
                        </div>
                    </div>
                    {{-- Contacto del cliente --}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Contacto</label>
                        <div class="col-sm-9">
                            <input disabled type="text" class="form-control" placeholder="Contacto"
                                   name="nombre_contacto"
                                   value="{{ $cliente->nombre_contacto }}">
                        </div>
                    </div>

                    {{-- Direccion del cliente --}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Direccion</label>
                        <div class="col-sm-9">
                            <textarea disabled name="direccion" class="form-control" id=""
                                      cols="30">{{ $cliente->direccion }}</textarea>
                        </div>
                    </div>

                    {{-- Municipio --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label"><b>Municipio</b></label>
                        <div class="col-md-9 ">
                            <select disabled class="form-control select2" style="width: 100%" name="municipio_id">
                                <option value="" selected disabled>Selecciona un municipio</option>
                                @foreach($municipios as $municipio)
                                    @if ($municipio->id == $cliente->municipio_id)
                                        <option selected value="{{ $municipio->id }}">{{ $municipio->nombre }}</option>
                                    @else
                                        <option value="{{ $municipio->id }}">{{ $municipio->nombre }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Giro --}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Giro</label>
                        <div class="col-sm-9">
                            <input disabled type="text" class="form-control" placeholder="Giro comercial"
                                   name="giro" value="{{ $cliente->giro }}">
                        </div>
                    </div>

                </div>
                <div class="col-xs-6">
                    <h4>Teléfonos</h4>
                    <br>

                    {{-- Telefono principal del cliente --}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Principal</label>
                        <div class="col-sm-9">
                            <input disabled type="text" class="form-control" placeholder="(503) 9999-9999"
                                   name="telefonoPrincipal" data-inputmask='"mask": "(999) 9999-9999"' data-mask
                                   value="{{ $cliente->telefono_1 }}">
                        </div>
                    </div>

                    {{-- Telefono secundario del cliente --}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Secundario</label>
                        <div class="col-sm-9">
                            <input disabled type="text" class="form-control" placeholder="(503) 9999-9999"
                                   name="telefonoSecundario" data-inputmask='"mask": "(999) 9999-9999"' data-mask
                                   value="{{ $cliente->telefono_2 }}">
                        </div>
                    </div>

                    {{-- NIT del cliente --}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label">NIT</label>
                        <div class="col-sm-9">
                            <input disabled type="text" class="form-control" placeholder="NIT" name="nit"
                                   value="{{$cliente->nit}}">
                        </div>
                    </div>

                    {{-- NRC del cliente --}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label">NRC</label>
                        <div class="col-sm-9">
                            <input disabled type="text" class="form-control" placeholder="Número Registro de Comercio"
                                   name="nrc" value="{{$cliente->nrc}}">
                        </div>
                    </div>

                    {{--Vendedor--}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Vendedor</label>
                        <div class="col-sm-9">
                            <select disabled class="form-control select2" name="unidad_medida_id">
                                <option value="" selected disabled>Sin vendedor especificado</option>
                                @foreach($vendedores as $vendedor)
                                    @if($vendedor->id == $cliente->vendedor_id)
                                        <option selected
                                                value="{{ $vendedor->id }}">{{ $vendedor->nombre }} {{ $vendedor->apellido }}</option>
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
                <a href="{{ route('clienteLista') }}" class="btn btn-lg btn-default"><span
                            class="fa fa-mail-reply"></span> Regresar a lista</a>
                <a href="{{ route('clienteEditar',['id' => $cliente->id]) }}" class="btn btn-lg btn-warning pull-right"><span
                            class="fa fa-edit"></span> Editar</a>
            </div>
        </form>
    </div><!-- /.box -->

@endsection

@section('JSExtras')

@endsection