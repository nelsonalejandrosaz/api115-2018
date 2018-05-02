@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Editar cliente
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
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
                        <label class="col-sm-3 control-label"><b>Nombre cliente</b></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="Nombre del cliente" name="nombre" value="{{ $cliente->nombre }}" >
                        </div>
                    </div>

                    {{-- Nombre alternativo del cliente --}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nombre alternativo</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="ej. Panadería de José por la quita"
                                   name="nombre_alternativo" value="{{ $cliente->nombre_alternativo }}">
                        </div>
                    </div>

                    {{-- Contacto del cliente --}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Contacto</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="Contacto" name="nombre_contacto" value="{{ $cliente->nombre_contacto }}" >
                        </div>
                    </div>

                    {{-- Direccion del cliente --}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Direccion</label>
                        <div class="col-sm-9">
                            <textarea name="direccion" class="form-control" id=""
                                      cols="30">{{ $cliente->direccion }}</textarea>
                        </div>
                    </div>

                    {{-- Municipio --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label">Municipio</label>
                        <div class="col-md-9 ">
                            <select class="form-control select2" style="width: 100%" name="municipio_id">
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
                            <input type="text" class="form-control" placeholder="Giro comercial"
                                   name="giro" value="{{ $cliente->giro }}">
                        </div>
                    </div>

                </div>
                <div class="col-xs-6">
                    <h4>Telefonos</h4>
                    <br>

                    {{-- Telefono principal del cliente --}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Principal</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="(503) 9999-9999" name="telefono_1" data-inputmask='"mask": "(999) 9999-9999"' data-mask value="{{ $cliente->telefono_1 }}" >
                        </div>
                    </div>

                    {{-- Telefono secundario del cliente --}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Secundario</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="(503) 9999-9999" name="telefono_2" data-inputmask='"mask": "(999) 9999-9999"' data-mask value="{{ $cliente->telefono_2 }}" >
                        </div>
                    </div>

                    {{-- Email cliente --}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Email</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" placeholder="ej. cliente@algo.com"
                                   name="correo" value="{{ $cliente->correo }}">
                        </div>
                    </div>

                    {{-- NIT del cliente --}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label">NIT</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="NIT" name="nit"
                                   value="{{$cliente->nit}}">
                        </div>
                    </div>

                    {{-- NRC del cliente --}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label">NRC</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="Número Registro de Comercio"
                                   name="nrc" value="{{$cliente->nrc}}">
                        </div>
                    </div>

                    {{--Vendedor--}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Vendedor</label>
                        <div class="col-sm-9">
                            <select class="form-control select2" name="vendedor_id">
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

                    {{--Retencion--}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Retención</label>
                        <div class="col-sm-9">
                            <select class="form-control select2" name="retencion">
                                <option value="0" selected >Sin retención</option>
                                <option value="1" >Con retención</option>
                            </select>
                        </div>
                    </div>

                </div>
            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('clienteLista') }}" class="btn btn-lg btn-default"><span class="fa fa-close"></span> Cancelar</a>
                <button type="submit" class="btn btn-lg btn-success pull-right"><span class="fa fa-floppy-o"></span> Guardar</button>
            </div>
        </form>
    </div><!-- /.box -->

@endsection

@section('JSExtras')
    @include('comun.select2Jses')
@endsection