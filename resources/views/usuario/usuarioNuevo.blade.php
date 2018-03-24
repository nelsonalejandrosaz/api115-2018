@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Nuevo usuario
@endsection

@section('CSSExtras')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('/plugins/select2.min.css')}}">
@endsection

@section('contentheader_title')
    Nuevo usuario
@endsection

@section('contentheader_description')

@endsection

@section('main-content')

    @include('partials.alertas')

    <!-- Form de nuevo cliente -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Datos de usuario</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" action="" method="POST" id="nuevo-usuario-form">
            {{ csrf_field() }}
            <div class="box-body">
                <div class="col-xs-6">
                    <h4>Datos generales</h4>
                    <br>

                    {{-- Nombre del uuario --}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><b>Nombre</b></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="ej. Juan" name="nombre" >
                        </div>
                    </div>

                    {{-- Apellido --}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><b>Apellido</b></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="ej. Quintanilla"
                                   name="apellido" value="{{ old('nombre_alternativo ')}}">
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><b>Email</b></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="ej. empleado@lgl.com" name="email" value="{{ old('nombre_contacto') }}" >
                        </div>
                    </div>

                    {{-- Username --}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><b>Username</b></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="ej. juanquintanilla" name="username" value="{{ old('nombre_contacto') }}" >
                        </div>
                    </div>

                    {{-- Rol --}}
                    <div class="form-group">
                        <label class="col-md-3  control-label"><b>Puesto</b></label>
                        <div class="col-md-9 ">
                            <select class="form-control select2" style="width: 100%" name="rol_id">
                                <option value="" selected disabled>Selecciona un rol</option>
                                @foreach($roles as $rol)
                                    <option value="{{ $rol->id }}">{{ $rol->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>
                <div class="col-xs-6">
                    <h4>Teléfono</h4>
                    <br>

                    {{-- Telefono principal del cliente --}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Teléfono</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="(503) 9999-9999" name="telefono_1">
                        </div>
                    </div>

                    <br>
                    <h4>Contraseña</h4>
                    <br>

                    {{-- Telefono principal del cliente --}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><b>Contraseña</b></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="ej. password" name="password">
                        </div>
                    </div>

                </div>
            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="{{ route('clienteLista') }}" class="btn btn-lg btn-default"><span class="fa fa-close"></span> Cancelar</a>
                <button type="submit" class="btn btn-lg btn-success pull-right" id="enviar-buttom"><span class="fa fa-save"></span> Guardar</button>
            </div>
        </form>
    </div><!-- /.box -->

@endsection

@section('JSExtras')
    {{--Validacion--}}
    <script src="{{asset('/plugins/jquery-validation/dist/jquery.validate.js')}}"></script>
    <script src="{{asset('/plugins/jquery-validation/dist/additional-methods.min.js')}}"></script>
    @include('comun.select2Jses')
    <script>
        $(document).ready(Principal);

        function Principal() {
            $(":input").click(function () {
                $(this).select();
            });
            Validacion();
        }

        function Validacion() {
            $('#nuevo-usuario-form').validate({
                ignore: [],
                onfocusout: false,
                onkeyup: false,
                rules: {
                    "nombre": {
                        required: true,
                    },
                    "apellido": {
                        required: true,
                    },
                    "email": {
                        required: true,
                        email: true,
                    },
                    "username": {
                        required: true,
                    },
                    "rol_id": {
                        required: true,
                    },
                    "password": {
                        required: true,
                    },
                },
                messages: {
                    "nombre": {
                        required: function () {
                            toastr.error('Por favor complete el nombre', 'Ups!');
                        },
                    },
                    "apellido": {
                        required: function () {
                            toastr.error('Por favor complete el apellido', 'Ups!');
                        },
                    },
                    "email": {
                        required: function () {
                            toastr.error('Por favor complete el email', 'Ups!');
                        },
                        email: function () {
                            toastr.error('El campo email debe contener un email valido', 'Ups!');
                        },
                    },
                    "username": {
                        required: function () {
                            toastr.error('Por favor complete el username', 'Ups!');
                        },
                    },
                    "rol_id": {
                        required: function () {
                            toastr.error('Por favor seleccione el rol a desempeñar', 'Ups!');
                        },
                    },
                    "password": {
                        required: function () {
                            toastr.error('Por favor complete la contraseña', 'Ups!');
                        },
                    },
                },
                submitHandler: function (form) {
                    $('#enviar-buttom').attr('disabled', 'true');
                    toastr.success('Por favor espere a que se procese', 'Excelente');
                    form.submit();
                }
            });
        }
    </script>
@endsection

