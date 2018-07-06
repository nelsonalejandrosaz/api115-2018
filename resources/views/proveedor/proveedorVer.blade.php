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
            <h3 class="box-title">Datos del proveedor</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal">
            {{ csrf_field() }}
            {{ method_field('PUT') }}
            <div class="box-body">
                <div class="col-xs-6">
                    <h4>Datos generales</h4>
                    <br>

                    {{-- Nombre del proveedor --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Nombre</label>
                        <div class="col-sm-8">
                            <input readonly type="text" class="form-control" placeholder="Nombre del proveedor" name="nombre" value="{{ $proveedor->nombre }}" >
                        </div>
                    </div>


                    {{-- Direccion del proveedor --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Direccion</label>
                        <div class="col-sm-8">
                            <input readonly type="text" class="form-control" placeholder="Direccion" name="direccion" value="{{ $proveedor->direccion }}" >
                        </div>
                    </div>

                    {{-- Localidad --}}
                    <div class="form-group">
                        <label class="col-md-4  control-label"><b>Localidad</b></label>
                        <div class="col-md-8 ">
                            <select disabled class="form-control select2" style="width: 100%" name="nacional">
                                @if($proveedor->nacional)
                                    <option value="1">Nacional</option>
                                @else
                                    <option value="0">Internacional</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    {{-- Percepcion --}}
                    <div class="form-group">
                        <label class="col-md-4  control-label"><b>Percepcion</b></label>
                        <div class="col-md-8 ">
                            <select disabled class="form-control select2" style="width: 100%" name="percepcion">
                                @if($proveedor->percepcion)
                                    <option selected value="1">Con percepcion</option>
                                    <option value="0">Sin percepcion</option>
                                @else
                                    <option value="1">Con percepcion</option>
                                    <option selected value="0">Sin percepcion</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    {{-- NIT --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">NIT</label>
                        <div class="col-sm-8">
                            <input readonly type="text" class="form-control" value="{{ $proveedor->nit }}" name="nit">
                        </div>
                    </div>

                    {{-- NRC --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">NRC</label>
                        <div class="col-sm-8">
                            <input readonly type="text" class="form-control" value="{{ $proveedor->nrc }}" name="nrc">
                        </div>
                    </div>


                    {{-- ID Cuenta contable --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">ID Cuenta contable</label>
                        <div class="col-sm-8">
                            <input readonly type="text" class="form-control" value="{{ $proveedor->cuenta_contable }}" name="cuenta_contable">
                        </div>
                    </div>

                </div>
                <div class="col-xs-6">
                    <h4>Teléfonos</h4>
                    <br>

                    {{-- Contacto del proveedor --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Contacto</label>
                        <div class="col-sm-8">
                            <input disabled type="text" class="form-control" placeholder="Contacto" name="contacto" value="{{ $proveedor->nombre_contacto }}" >
                        </div>
                    </div>

                    {{-- Telefono principal del proveedor --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Telefono principal</label>
                        <div class="col-sm-8">
                            <input disabled type="text" class="form-control" placeholder="(503) 9999-9999" value="{{ $proveedor->telefono_1 }}" >
                        </div>
                    </div>
                    {{-- Telefono secundario del proveedor --}}
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Telefono secundario</label>
                        <div class="col-sm-8">
                            <input disabled type="text" class="form-control" placeholder="(503) 9999-9999" value="{{ $proveedor->telefono_2 }}" >
                        </div>
                    </div>
                </div>
            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('proveedorLista') }}" class="btn btn-lg btn-default"><span class="fa fa-mail-reply"></span> Regresar a lista proveedores</a>
                <a href="{{ route('proveedorEditar',['id' => $proveedor->id]) }}" class="btn btn-lg btn-warning pull-right"><span class="fa fa-edit"></span> Editar</a>
            </div>
        </form>
    </div><!-- /.box -->

@endsection

@section('JSExtras')

@endsection