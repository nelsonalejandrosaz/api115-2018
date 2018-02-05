<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        @if (! Auth::guest())
            <div class="user-panel">
                <div class="pull-left image">
                    {{--<img src="{{ Gravatar::get($user->email) }}" class="img-circle" alt="User Image"/>--}}
                    <img src="{{ Storage::url(Auth::user()->ruta_imagen) }}" class="img-circle" alt="User Image"/>
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
            <li><a href="{{route('inventarioLista')}}"><i class='glyphicon glyphicon-list'></i> <span>Inventario</span></a>
            </li>

            @if(Auth::user()->rol->nombre == 'Administrador' || Auth::user()->rol->nombre == 'Vendedor')
            {{-- Productos --}}
            <li class="treeview">
                <a href="#"><i class='fa fa-barcode'></i> <span>Productos</span> <i
                            class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="{{route('productoLista')}}"><i class="fa fa-bars"></i>Productos</a></li>
                    @if(Auth::user()->rol->nombre == 'Administrador')
                        <li><a href="{{route('productoDesactivadoLista')}}"><i class="fa fa-bars"></i>Productos
                                desactivados</a></li>
                        <li><a href="{{route('productoNuevo')}}"><i class="fa fa-plus"></i>Nuevo producto</a></li>
                    @endif
                </ul>
            </li>
            @endif

            @if(Auth::user()->rol->nombre == 'Administrador')
                {{-- Compras --}}
                <li class="treeview">
                    <a href="#"><i class='fa fa-shopping-cart'></i> <span>Compras</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="{{route('compraLista')}}"><i class="fa fa-bars"></i>Compras</a></li>
                        <li><a href="{{route('compraNueva')}}"><i class="fa fa-plus"></i>Ingresar compra</a></li>
                    </ul>
                </li>
            @endif

            {{-- Orden de pedido --}}
            <li class="treeview">
                <a href="#"><i class='fa fa-clone'></i> <span>Orden de pedido</span> <i
                            class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    @if(Auth::user()->rol->nombre == 'Administrador' || Auth::user()->rol->nombre == 'Vendedor')
                    <li><a href="{{route('ordenPedidoLista')}}"><i class="fa fa-bars"></i>Ordenes de pedidos</a></li>
                    <li><a href="{{route('ordenPedidoNueva')}}"><i class="fa fa-plus"></i>Nueva orden de pedido</a></li>
                    @endif
                    @if(Auth::user()->rol->nombre == 'Administrador' || Auth::user()->rol->nombre == 'Bodeguero')
                        <li><a href="{{route('ordenPedidoListaBodega')}}"><i class="fa fa-bars"></i>Ordenes en
                                proceso</a></li>
                    @endif
                </ul>
            </li>

            {{-- Ventas --}}
            <li class="treeview">
                <a href="#"><i class='fa fa-truck'></i> <span>Ventas</span> <i
                            class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="{{route('ventaOrdenesLista')}}"><i class="fa fa-bars"></i>Ordenes sin facturar</a></li>
                    <li><a href="{{route('ventaLista',['filtro' => 'todo'])}}"><i class="fa fa-bars"></i>Ventas
                            facturadas</a></li>
                    <li><a href="{{route('ventaLista',['filtro' => 'factura'])}}"><i class="fa fa-bars"></i>Facturas</a>
                    </li>
                    <li><a href="{{route('ventaLista',['filtro' => 'ccf'])}}"><i class="fa fa-bars"></i>Créditos
                            fiscales</a></li>
                    <li><a href="{{route('ventaLista',['filtro' => 'anulada'])}}"><i class="fa fa-window-close"></i>Ventas
                            anuladas</a></li>
                </ul>
            </li>

            @if(Auth::user()->rol->nombre == 'Administrador')
                {{-- Abonos --}}
                <li class="treeview">
                    <a href="#"><i class='fa fa-money'></i> <span>Abonos</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="{{route('abonoLista')}}"><i class="fa fa-bars"></i>Abonos</a></li>
                        <li><a href="{{route('abonoNuevoSinVenta')}}"><i class="fa fa-plus"></i>Nuevo abono</a></li>
                    </ul>
                </li>
            @endif

            {{-- Ajustes --}}
            @if(Auth::user()->rol->nombre == 'Administrador')
                <li class="treeview">
                    <a href="#"><i class='fa fa-exchange'></i> <span>Ajustes de inventario</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="{{route('ajusteLista')}}"><i class="fa fa-bars"></i>Ajustes realizados</a></li>
                        <li><a href="{{route('ajusteNuevo')}}"><i class="fa fa-plus"></i>Nuevo ajuste existencia</a>
                        </li>
                        <li><a href="{{route('ajusteCostoNuevo')}}"><i class="fa fa-plus"></i>Nuevo ajuste costo</a>
                        </li>
                    </ul>
                </li>
            @endif

            @if(Auth::user()->rol->nombre == 'Administrador')
                {{-- Proveedores --}}
                <li class="treeview">
                    <a href="#"><i class='fa fa-link'></i> <span>Proveedores</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="{{route('proveedorLista')}}"><i class="fa fa-bars"></i>Proveedores</a></li>
                        <li><a href="{{route('proveedorLista')}}"><i class="fa fa-money"></i>Saldo proveedores</a></li>
                        <li><a href="{{route('proveedorNuevo')}}"><i class="fa fa-plus"></i>Nuevo proveedor</a></li>
                    </ul>
                </li>
            @endif

            @if(Auth::user()->rol->nombre == 'Administrador' || Auth::user()->rol->nombre == 'Vendedor')
            {{-- Clientes --}}
            <li class="treeview">
                <a href="#"><i class='fa fa-user'></i> <span>Clientes</span> <i
                            class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="{{route('clienteLista')}}"><i class="fa fa-bars"></i>Clientes</a></li>
                    <li><a href="{{route('clienteSaldoLista')}}"><i class="fa fa-money"></i>Saldo clientes</a></li>
                    <li><a href="{{route('clienteNuevo')}}"><i class="fa fa-plus"></i>Nuevo cliente</a></li>
                </ul>
            </li>
            @endif

            @if(Auth::user()->rol->nombre == 'Administrador')
                {{-- Categorias --}}
                <li class="treeview">
                    <a href="#"><i class='fa fa-tags'></i> <span>Categorías</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="{{route('categoriaLista')}}"><i class="fa fa-bars"></i>Categorías</a></li>
                        <li><a href="{{route('categoriaNuevo')}}"><i class="fa fa-plus"></i>Nueva categoría</a></li>
                    </ul>
                </li>
            @endif

            @if(Auth::user()->rol->nombre == 'Administrador')
                {{-- Formulas --}}
                <li class="treeview">
                    <a href="#"><i class='fa fa-puzzle-piece'></i> <span>Fórmulas</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="{{route('formulaLista')}}"><i class="fa fa-bars"></i>Fórmulas</a></li>
                        <li><a href="{{route('formulaNuevo')}}"><i class="fa fa-plus"></i>Nueva fórmula</a></li>
                    </ul>
                </li>
            @endif

            @if(Auth::user()->rol->nombre == 'Administrador' || Auth::user()->rol->nombre == 'Bodeguero')
                {{-- Produccion --}}
                <li class="treeview">
                    <a href="#"><i class='fa fa-industry'></i> <span>Produccion</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="{{route('produccionLista')}}"><i class="fa fa-bars"></i>Producciones</a></li>
                        <li><a href="{{route('produccionNuevo')}}"><i class="fa fa-plus"></i>Nueva producción</a></li>
                    </ul>
                </li>
            @endif

            {{-- Reportes --}}
            <li class="treeview">
                <a href="#"><i class='fa fa-bar-chart'></i> <span>Reportes</span> <i
                            class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="#"><i class=""></i> Reportes compras</a></li>
                    <li><a href="#">Reportes ventas</a></li>
                    <li><a href="#">Reportes inventarios</a></li>
                </ul>
            </li>

            @if(Auth::user()->rol->nombre == 'Administrador')
                {{-- Usuarios --}}
                <li class="treeview">
                    <a href="#"><i class='fa fa-users'></i> <span>Usuarios</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="">Usuarios</a></li>
                        <li><a href="">Nuevo usuario</a></li>
                    </ul>
                </li>
            @endif

            @if(Auth::user()->rol->nombre == 'Administrador')
                {{-- Configuracion --}}
                <li class="treeview">
                    <a href="#"><i class='fa fa-cog'></i> <span>Configuración</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="">Configuración usuarios</a></li>
                        <li><a href="{{route('importarDatos')}}">Configuración inicial</a></li>
                        <li><a href="{{route('importarOrdenes')}}">Configuración ordenes</a></li>
                        <li><a href="{{route('conversionUnidadesLista')}}">Conversión de unidades</a></li>
                    </ul>
                </li>
            @endif
        </ul><!-- /.sidebar-menu -->

    </section>
    <!-- /.sidebar -->
</aside>
