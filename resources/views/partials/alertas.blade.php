{{-- Alerta de errores validacion laravel --}}
@if ($errors->any() || session()->has('message.content'))
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-ban"></i> Error!</h4>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        {{ session('message.content') }}
    </div>
@endif

{{-- Base para las alertas --}}
@if(session()->has('mensaje.contenido'))
    <div class="alert alert-{{session('mensaje.tipo')}} alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4>  <i class="icon fa {{session('mensaje.icono')}}"></i> Exito!</h4>
        {{ session('mensaje.contenido') }}
    </div>
@endif