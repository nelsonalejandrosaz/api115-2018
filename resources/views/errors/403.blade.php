@extends('adminlte::layouts.errors')

@section('htmlheader_title')
    {{ trans('adminlte_lang::message.serviceunavailable') }}
@endsection

@section('main-content')

    <div class="error-page">
        <h2 class="headline text-red">403</h2>
        <div class="error-content">
            <h3><i class="fa fa-warning text-red"></i> Upss! No tienes permiso para ver esto</h3>
            <p>
                Contacta al administrador si crees que es un problema. <br>
                <br>
                {{ trans('adminlte_lang::message.mainwhile') }} <a href='{{ url('/home') }}'>{{ trans('adminlte_lang::message.returndashboard') }}</a>
            </p>
        </div>
    </div><!-- /.error-page -->
@endsection
