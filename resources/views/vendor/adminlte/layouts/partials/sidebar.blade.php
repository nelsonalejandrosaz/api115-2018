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

    <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header">Actividades</li>
            <!-- Optionally, you can add icons to the links -->

            {{--Menu para administrador--}}
            @if(Auth::user()->rol->nombre == 'Administrador')
                {{--Inicio--}}
                <li class="active"><a href="{{ url('home') }}"><i class='fa fa-home'></i> <span>Inicio</span></a></li>

                {{-- Inventario general --}}
                <li><a href="{{route('inventarioLista')}}"><i class='glyphicon glyphicon-list'></i>
                        <span>Inventario</span></a>
                </li>

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

                {{-- Compras --}}
                <li class="treeview">
                    <a href="#"><i class='fa fa-shopping-cart'></i> <span>Compras</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="{{route('compraLista')}}"><i class="fa fa-bars"></i>Compras</a></li>
                        <li><a href="{{route('compraNueva')}}"><i class="fa fa-plus"></i>Ingresar compra</a></li>
                    </ul>
                </li>

                {{-- Orden de pedido --}}
                <li class="treeview">
                    <a href="#"><i class='fa fa-clone'></i> <span>Orden de pedido</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="{{route('ordenPedidoLista')}}"><i class="fa fa-bars"></i>Ordenes de pedidos</a>
                        </li>
                        <li><a href="{{route('ordenPedidoNueva')}}"><i class="fa fa-plus"></i>Nueva orden de pedido</a>
                        </li>
                        <li><a href="{{route('ordenPedidoListaBodega')}}"><i class="fa fa-bars"></i>Ordenes en
                                proceso</a></li>
                        <li><a href="{{route('ordenPedidoListaProcesadoBodega')}}"><i class="fa fa-bars"></i>Ordenes
                                procesadas</a></li>

                    </ul>
                </li>

                {{-- Ventas --}}
                <li class="treeview">
                    <a href="#"><i class='fa fa-truck'></i> <span>Ventas</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="{{route('ventaOrdenesLista')}}"><i class="fa fa-bars"></i>Ordenes sin facturar</a>
                        </li>
                        <li><a href="{{route('ventaLista',['tipo' => 'todo'])}}"><i class="fa fa-bars"></i>Ventas
                                facturadas</a></li>
                        <li><a href="{{route('ventaLista',['tipo' => 'factura'])}}"><i
                                        class="fa fa-bars"></i>Facturas</a>
                        </li>
                        <li><a href="{{route('ventaLista',['tipo' => 'ccf'])}}"><i class="fa fa-bars"></i>Créditos
                                fiscales</a></li>
                        <li><a href="{{route('ventaLista',['tipo' => 'anulada'])}}"><i class="fa fa-window-close"></i>Ventas
                                anuladas</a></li>
                        <li><a href="{{route('ventaSinOrdenNueva')}}"><i class="fa fa-plus"></i>Nueva venta comisión</a>
                        <li><a href="{{route('ventaSinOrdenAnuladaNueva')}}"><i class="fa fa-plus"></i>Nuevo documento
                                anulado</a>
                    </ul>
                </li>

                {{-- Orden de pedido --}}
                {{--<li class="treeview">--}}
                    {{--<a href="#"><i class='fa fa-clone'></i> <span>Orden de muestra</span> <i--}}
                                {{--class="fa fa-angle-left pull-right"></i></a>--}}
                    {{--<ul class="treeview-menu">--}}
                        {{--<li><a href="{{route('ordenMuestraLista')}}"><i class="fa fa-bars"></i>Ordenes de muestra</a>--}}
                        {{--</li>--}}
                        {{--<li><a href="{{route('ordenMuestraNueva')}}"><i class="fa fa-plus"></i>Nueva orden de muestra</a>--}}
                        {{--</li>--}}
                        {{--<li><a href="{{route('ordenPedidoListaBodega')}}"><i class="fa fa-bars"></i>Ordenes en--}}
                        {{--proceso</a></li>--}}
                        {{--<li><a href="{{route('ordenPedidoListaProcesadoBodega')}}"><i class="fa fa-bars"></i>Ordenes--}}
                        {{--procesadas</a></li>--}}

                    {{--</ul>--}}
                {{--</li>--}}

                {{-- Abonos --}}
                <li class="treeview">
                    <a href="#"><i class='fa fa-money'></i> <span>Abonos</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="{{route('abonoLista')}}"><i class="fa fa-bars"></i>Abonos</a></li>
                        <li><a href="{{route('abonoNuevoSinVenta')}}"><i class="fa fa-plus"></i>Nuevo abono</a></li>
                    </ul>
                </li>

                {{--Cuentas por cobrar--}}
                <li class="treeview">
                    <a href="#"><i class='fa fa-money'></i> <span>Cuentas por cobrar</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="{{route('cuentasPorCobrar')}}"><i class="fa fa-money"></i>Cobros pendientes</a>
                        </li>
                        <li><a href="{{route('clienteVentaLista')}}"><i class="fa fa-bars"></i>Ventas clientes</a></li>
                    </ul>
                </li>

                {{--Cuentas por pagar--}}
                <li class="treeview">
                    <a href="#"><i class='fa fa-money'></i> <span>Cuentas por pagar</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        {{--<li><a href="{{route('abonoLista')}}"><i class="fa fa-bars"></i>Abonos</a></li>--}}
                        {{--<li><a href="{{route('abonoNuevoSinVenta')}}"><i class="fa fa-plus"></i>Nuevo abono</a></li>--}}
                    </ul>
                </li>

                {{-- Ajustes --}}
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

                {{-- Proveedores --}}
                <li class="treeview">
                    <a href="#"><i class='fa fa-link'></i> <span>Proveedores</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="{{route('proveedorLista')}}"><i class="fa fa-bars"></i>Proveedores</a></li>
                        <li><a href="{{route('proveedorNuevo')}}"><i class="fa fa-plus"></i>Nuevo proveedor</a></li>
                    </ul>
                </li>

                {{-- Clientes --}}
                <li class="treeview">
                    <a href="#"><i class='fa fa-user'></i> <span>Clientes</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="{{route('clienteLista')}}"><i class="fa fa-bars"></i>Clientes</a></li>
                        <li><a href="{{route('clienteNuevo')}}"><i class="fa fa-plus"></i>Nuevo cliente</a></li>
                    </ul>
                </li>


                {{-- Categorias --}}
                <li class="treeview">
                    <a href="#"><i class='fa fa-tags'></i> <span>Categorías</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="{{route('categoriaLista')}}"><i class="fa fa-bars"></i>Categorías</a></li>
                        <li><a href="{{route('categoriaNuevo')}}"><i class="fa fa-plus"></i>Nueva categoría</a></li>
                    </ul>
                </li>

                {{-- Formulas --}}
                <li class="treeview">
                    <a href="#"><i class='fa fa-puzzle-piece'></i> <span>Fórmulas</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="{{route('formulaLista')}}"><i class="fa fa-bars"></i>Fórmulas</a></li>
                        <li><a href="{{route('formulaDesactivadasLista')}}"><i class="fa fa-bars"></i>Histórico fórmulas</a>
                        </li>
                        <li><a href="{{route('formulaNuevo')}}"><i class="fa fa-plus"></i>Nueva fórmula</a></li>
                    </ul>
                </li>

                {{-- Produccion --}}
                <li class="treeview">
                    <a href="#"><i class='fa fa-industry'></i> <span>Produccion</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="{{route('produccionLista')}}"><i class="fa fa-bars"></i>Producciones</a></li>
                        <li><a href="{{route('produccionRevertidaLista')}}"><i class="fa fa-bars"></i>Producciones
                                revertidas</a></li>
                        <li><a href="{{route('produccionNuevo')}}"><i class="fa fa-plus"></i>Nueva producción</a></li>
                    </ul>
                </li>

                {{-- Informes --}}
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-share"></i> <span>Informes</span>
                        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                    </a>
                    <ul class="treeview-menu">
                        {{--Nivel 1--}}
                        <li><a href="{{ route('informeLista') }}"><i class="fa fa-circle-o"></i> Informes</a></li>
                    </ul>
                </li>


                {{-- Usuarios --}}
                <li class="treeview">
                    <a href="#"><i class='fa fa-users'></i> <span>Usuarios</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="{{route('usuarioLista')}}">Usuarios</a></li>
                        <li><a href="{{route('usuarioNuevo')}}">Nuevo usuario</a></li>
                    </ul>
                </li>


                {{-- Exportar SAC --}}
                {{--<li class="treeview">--}}
                    {{--<a href="#"><i class='fa fa-arrow-circle-o-up'></i> <span>Exportacion SAC</span> <i--}}
                                {{--class="fa fa-angle-left pull-right"></i></a>--}}
                    {{--<ul class="treeview-menu">--}}
{{--                        <li><a href="{{route('usuarioNuevo')}}">Exportar</a></li>--}}
                        {{--<li><a href="{{route('exportar.configuracion')}}">Exportar</a></li>--}}
                        {{--<li><a href="{{route('cierre.index')}}">Cierre mensual</a></li>--}}
                    {{--</ul>--}}
                {{--</li>--}}


                {{-- Configuracion --}}
                <li class="treeview">
                    <a href="#"><i class='fa fa-cog'></i> <span>Configuración</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="">Configuración usuarios</a></li>
                        {{--<li><a href="{{route('importarDatos')}}">Configuración inicial</a></li>--}}
                        <li><a href="{{route('importarOrdenes')}}">Configuración ordenes</a></li>
                        <li><a href="{{route('conversionUnidadesLista')}}">Conversión de unidades</a></li>
                        <li><a href="#">Cierre inventario mensual</a></li>
                    </ul>
                </li>


            @elseif(Auth::user()->rol->nombre == 'Vendedor')

                {{--Inicio--}}
                <li class="active"><a href="{{ url('home') }}"><i class='fa fa-home'></i> <span>Inicio</span></a></li>

                {{-- Inventario general --}}
                <li><a href="{{route('productoLista')}}"><i class='glyphicon glyphicon-list'></i> <span>Productos y precios</span></a>
                </li>

                {{-- Orden de pedido --}}
                <li class="treeview">
                    <a href="#"><i class='fa fa-clone'></i> <span>Orden de pedido</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="{{route('ordenPedidoLista')}}"><i class="fa fa-bars"></i>Ordenes de pedidos</a>
                        </li>
                        <li><a href="{{route('ordenPedidoNueva')}}"><i class="fa fa-plus"></i>Nueva orden de pedido</a>
                        </li>
                    </ul>
                </li>

                {{-- Ventas --}}
                <li class="treeview">
                    <a href="#"><i class='fa fa-truck'></i> <span>Ventas</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="{{route('ventaOrdenesLista')}}"><i class="fa fa-bars"></i>Ordenes sin facturar</a>
                        </li>
                        <li><a href="{{route('ventaLista',['tipo' => 'todo'])}}"><i class="fa fa-bars"></i>Ventas
                                facturadas</a></li>
                        <li><a href="{{route('ventaLista',['tipo' => 'factura'])}}"><i
                                        class="fa fa-bars"></i>Facturas</a>
                        </li>
                        <li><a href="{{route('ventaLista',['tipo' => 'ccf'])}}"><i class="fa fa-bars"></i>Créditos
                                fiscales</a></li>
                        <li><a href="{{route('ventaLista',['tipo' => 'anulada'])}}"><i class="fa fa-window-close"></i>Ventas
                                anuladas</a></li>
                    </ul>
                </li>

                {{-- Clientes --}}
                <li class="treeview">
                    <a href="#"><i class='fa fa-user'></i> <span>Clientes</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="{{route('clienteLista')}}"><i class="fa fa-bars"></i>Clientes</a></li>
                        <li><a href="{{route('clienteVentaLista')}}"><i class="fa fa-bars"></i>Clientes Ventas</a></li>
                        <li><a href="{{route('cuentasPorCobrar')}}"><i class="fa fa-money"></i>Saldo clientes</a></li>
                        <li><a href="{{route('clienteNuevo')}}"><i class="fa fa-plus"></i>Nuevo cliente</a></li>
                    </ul>
                </li>

            @elseif(Auth::user()->rol->nombre == 'Bodeguero')
                {{--Inicio--}}
                <li class="active"><a href="{{ url('home') }}"><i class='fa fa-home'></i> <span>Inicio</span></a></li>

                {{-- Inventario general --}}
                <li><a href="{{route('inventarioLista')}}"><i class='glyphicon glyphicon-list'></i>
                        <span>Inventario</span></a>
                </li>

                {{-- Orden de pedido --}}
                <li class="treeview">
                    <a href="#"><i class='fa fa-clone'></i> <span>Orden de pedido</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="{{route('ordenPedidoListaBodega')}}"><i class="fa fa-bars"></i>Ordenes en
                                proceso</a></li>
                        <li><a href="{{route('ordenPedidoListaProcesadoBodega')}}"><i class="fa fa-bars"></i>Ordenes
                                procesadas</a></li>
                    </ul>
                </li>

                {{-- Produccion --}}
                <li class="treeview">
                    <a href="#"><i class='fa fa-industry'></i> <span>Produccion</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="{{route('formulaLista')}}"><i class="fa fa-bars"></i>Formulas</a></li>
                        <li><a href="{{route('produccionLista')}}"><i class="fa fa-bars"></i>Producciones</a></li>
                        <li><a href="{{route('produccionNuevo')}}"><i class="fa fa-plus"></i>Nueva producción</a></li>
                    </ul>
                </li>

                {{-- Ajustes --}}
                <li class="treeview">
                    <a href="#"><i class='fa fa-exchange'></i> <span>Ajustes de inventario</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="{{route('ajusteLista')}}"><i class="fa fa-bars"></i>Ajustes realizados</a></li>
                        <li><a href="{{route('ajusteNuevo')}}"><i class="fa fa-plus"></i>Nuevo ajuste existencia</a>
                        </li>
                    </ul>
                </li>


            @endif

        </ul><!-- /.sidebar-menu -->

    </section>
    <!-- /.sidebar -->
</aside>
