<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        @if (! Auth::guest())
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="{{ Gravatar::get($user->email) }}" class="img-circle" alt="User Image"/>
                </div>
                <div class="pull-left info">
                    <p>{{ Auth::user()->nombre }}</p>
                    <!-- Status -->
                    <a href="#"><i class="fa fa-circle text-success"></i> {{ Auth::user()->rol->nombre }}
                    </a>
                </div>
            </div>
    @endif

    {{--<!-- search form (Optional) -->--}}
        {{--<form action="#" method="get" class="sidebar-form">--}}
            {{--<div class="input-group">--}}
                {{--<input type="text" name="q" class="form-control"--}}
                       {{--placeholder="{{ trans('adminlte_lang::message.search') }}..."/>--}}
                {{--<span class="input-group-btn">--}}
                {{--<button type='submit' name='search' id='search-btn' class="btn btn-flat"><i--}}
                            {{--class="fa fa-search"></i></button>--}}
              {{--</span>--}}
            {{--</div>--}}
        {{--</form>--}}
        {{--<!-- /.search form -->--}}

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header">Actividades</li>
            <!-- Optionally, you can add icons to the links -->

            {{--Inicio--}}
            <li class="active"><a href="{{ url('home') }}"><i class='fa fa-home'></i> <span>Inicio</span></a></li>

            {{-- Inventario general --}}
            <li><a href="{{route('inventarioLista')}}"><i class='glyphicon glyphicon-list'></i> <span>Inventario</span></a></li>

            {{-- Productos --}}
            <li class="treeview">
                <a href="#"><i class='fa fa-ticket'></i> <span>Productos</span> <i
                            class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="{{route('productoLista')}}">Lista de productos</a></li>
                    <li><a href="{{route('productoNuevo')}}">Nuevo producto</a></li>
                </ul>
            </li>

            {{-- Compras --}}
            <li class="treeview">
                <a href="#"><i class='fa fa-toggle-left'></i> <span>Compras</span> <i
                            class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="{{route('compraLista')}}">Lista de compras</a></li>
                    <li><a href="{{route('compraNueva')}}">Ingresar compra</a></li>
                </ul>
            </li>

            {{-- Orden de pedido --}}
            <li class="treeview">
                <a href="#"><i class='fa fa-toggle-right'></i> <span>Orden de pedido</span> <i
                            class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="{{route('ordenPedidoLista')}}">Lista de ordenes de pedidos</a></li>
                    <li><a href="{{route('ordenPedidoNueva')}}">Nueva orden de pedido</a></li>
                </ul>
            </li>

            {{-- Ventas --}}
            <li class="treeview">
                <a href="#"><i class='fa fa-truck'></i> <span>Ventas</span> <i
                            class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="{{route('ordenPedidoLista')}}">Lista de ventas</a></li>
                    <li><a href="{{route('ordenPedidoNueva')}}">Nueva venta</a></li>
                </ul>
            </li>

            {{-- Ajustes --}}
            <li class="treeview">
                <a href="#"><i class='fa fa-exclamation-circle'></i> <span>Ajustes de inventario</span> <i
                            class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="{{route('ajusteLista')}}">Lista de ajustes</a></li>
                    <li><a href="{{route('ajusteNuevo')}}">Nuevo ajuste</a></li>
                </ul>
            </li>

            {{-- Proveedores --}}
            <li class="treeview">
                <a href="#"><i class='fa fa-link'></i> <span>Proveedores</span> <i
                            class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="{{route('proveedorLista')}}">Lista de proveedores</a></li>
                    <li><a href="{{route('proveedorNuevo')}}">Nuevo proveedor</a></li>
                </ul>
            </li>

            {{-- Clientes --}}
            <li class="treeview">
                <a href="#"><i class='fa fa-link'></i> <span>Clientes</span> <i
                            class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="{{route('clienteLista')}}">Lista de clientes</a></li>
                    <li><a href="{{route('clienteNuevo')}}">Nuevo cliente</a></li>
                </ul>
            </li>

            {{-- Categorias --}}
            <li class="treeview">
                <a href="#"><i class='fa fa-tags'></i> <span>Categorías</span> <i
                            class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="{{route('categoriaLista')}}">Lista de categorías</a></li>
                    <li><a href="{{route('categoriaNuevo')}}">Nueva categoría</a></li>
                </ul>
            </li>

            {{-- Formulas --}}
            <li class="treeview">
                <a href="#"><i class='fa fa-puzzle-piece'></i> <span>Fórmulas</span> <i
                            class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="{{route('formulaLista')}}">Lista de fórmulas</a></li>
                    <li><a href="{{route('formulaNuevo')}}">Nueva fórmula</a></li>
                </ul>
            </li>

            {{-- Produccion --}}
            <li class="treeview">
                <a href="#"><i class='fa fa-industry'></i> <span>Produccion</span> <i
                            class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="#">Lista de producciones</a></li>
                    <li><a href="#">Nueva producción</a></li>
                </ul>
            </li>
            {{-- Reportes --}}
            <li class="treeview">
                <a href="#"><i class='fa fa-bar-chart'></i> <span>Reportes</span> <i
                            class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="#">Reportes compras</a></li>
                    <li><a href="#">Reportes ventas</a></li>
                    <li><a href="#">Reportes inventarios</a></li>
                </ul>
            </li>

            {{-- Usuarios --}}
            <li class="treeview">
                <a href="#"><i class='fa fa-users'></i> <span>Usuarios</span> <i
                            class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="">Lista de usuarios</a></li>
                    <li><a href="">Nuevo usuario</a></li>
                </ul>
            </li>
        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
