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
                    <a href="#"><i class="fa fa-circle text-success"></i> {{ trans('adminlte_lang::message.online') }}
                    </a>
                </div>
            </div>
    @endif

    <!-- search form (Optional) -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control"
                       placeholder="{{ trans('adminlte_lang::message.search') }}..."/>
                <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i
                            class="fa fa-search"></i></button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header">OPCIONES</li>
            <!-- Optionally, you can add icons to the links -->

            {{--Inicio--}}
            <li class="active"><a href="{{ url('home') }}"><i class='fa fa-home'></i> <span>Inicio</span></a></li>

            {{-- Inventario general --}}
            <li><a href=""><i class='glyphicon glyphicon-list'></i> <span>Inventario</span></a></li>

            {{-- Productos --}}
            <li class="treeview">
                <a href="#"><i class='fa fa-industry'></i> <span>Productos</span> <i
                            class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="">Lista de productos</a></li>
                    <li><a href="">Nuevo producto</a></li>
                </ul>
            </li>

            {{-- Proveedores --}}
            <li class="treeview">
                <a href="#"><i class='fa fa-link'></i> <span>Proveedores</span> <i
                            class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="">Lista de proveedores</a></li>
                    <li><a href="">Nuevo proveedor</a></li>
                </ul>
            </li>

            {{-- Categorias --}}
            <li class="treeview">
                <a href="#"><i class='fa fa-tags'></i> <span>Categorías</span> <i
                            class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="#">Lista de categorías</a></li>
                    <li><a href="#">Nueva categoría</a></li>
                </ul>
            </li>

            {{-- Formulas --}}
            <li class="treeview">
                <a href="#"><i class='fa fa-puzzle-piece'></i> <span>Fórmulas</span> <i
                            class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="">Lista de fórmulas</a></li>
                    <li><a href="">Nueva fórmula</a></li>
                </ul>
            </li>

            {{-- Entradas --}}
            <li class="treeview">
                <a href="#"><i class='fa fa-arrow-down'></i> <span>Entrada de producto</span> <i
                            class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="">Lista de facturas</a></li>
                    <li><a href="">Ingresar factura</a></li>
                    <li><a href="">Ajustes de entrada</a></li>
                    <li><a href="">Lista de ajustes de entrada</a></li>
                </ul>
            </li>

            {{-- Salidas --}}
            <li class="treeview">
                <a href="#"><i class='fa fa-arrow-up'></i> <span>Salida de productos</span> <i
                            class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="">Lista de ordenes de pedidos</a></li>
                    <li><a href="">Nueva orden de pedido</a></li>
                    <li><a href="">Ajustes de salida</a></li>
                    <li><a href="">Lista de ajustes de salida</a>
                </ul>
            </li>

            {{-- Produccion --}}
            <li class="treeview">
                <a href="#"><i class='fa fa-gears'></i> <span>Produccion</span> <i
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
