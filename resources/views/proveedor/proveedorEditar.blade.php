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
            <h3 class="box-title">Datos de proveedor</h3>
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

                    {{-- Direccion del proveedor --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Direccion</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="Direccion" name="direccion" value="{{ $proveedor->direccion }}" >
                        </div>
                    </div>

                    {{-- Localidad --}}
                    <div class="form-group">
                        <label class="col-md-4  control-label"><b>Localidad</b></label>
                        <div class="col-md-8 ">
                            <select class="form-control select2" style="width: 100%" name="nacional">
                                @if($proveedor->nacional)
                                    <option selected value="1">Nacional</option>
                                    <option value="0">Internacional</option>
                                @else
                                    <option value="1">Nacional</option>
                                    <option selected value="0">Internacional</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    {{-- NIT --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">NIT</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" value="{{ $proveedor->nit }}" name="nit">
                        </div>
                    </div>

                    {{-- NRC --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">NRC</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" value="{{ $proveedor->nrc }}" name="nrc">
                        </div>
                    </div>

                </div>
                <div class="col-xs-6">
                    <h4>Telefonos</h4>
                    <br>

                    {{-- Contacto del proveedor --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Contacto</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="Contacto" name="nombre_contacto" value="{{ $proveedor->nombre_contacto }}" >
                        </div>
                    </div>

                    {{-- Telefono principal del proveedor --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Telefono principal</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="(503) 9999-9999" name="telefono_1" data-inputmask='"mask": "(999) 9999-9999"' data-mask value="{{ $proveedor->telefono_1 }}" >
                        </div>
                    </div>

                    {{-- Telefono secundario del proveedor --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Telefono secundario</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="(503) 9999-9999" name="telefono_2" data-inputmask='"mask": "(999) 9999-9999"' data-mask value="{{ $proveedor->telefono_2 }}" >
                        </div>
                    </div>

                </div>
            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('proveedorLista') }}" class="btn btn-lg btn-default"><span class="fa fa-close"></span> Cancelar</a>
                <button type="submit" class="btn btn-lg btn-success pull-right"><span class="fa fa-save"></span> Guardar</button>
            </div>
        </form>
    </div><!-- /.box -->

@endsection

@section('JSExtras')

@endsection