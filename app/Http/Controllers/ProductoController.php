<?php

namespace App\Http\Controllers;

use App\Categoria;
use App\Precio;
use App\Producto;
use App\ProductoUnidadMedida;
use App\TipoProducto;
use App\UnidadMedida;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductoController extends Controller
{
    public function ProductoLista()
    {
        $productos = Producto::all();
        foreach ($productos as $producto)
        {
            $entradas = $producto->movimientos()->where('entrada_id','!=',null)->get();
            $salidas = $producto->movimientos()->where('salida_id','!=',null)->get();
            $entradas = sizeof($entradas);
            $salidas = sizeof($salidas);
            if ($entradas == 0 && $salidas == 0)
            {
                $producto->eliminar = true;
            } else
            {
                $producto->eliminar = false;
            }
        }
        return view('producto.productoLista')->with(['productos' => $productos]);
    }

    public function ProductoVer(Request $request)
    {
        $tipo_productos = TipoProducto::all();
        $unidad_medidas = UnidadMedida::all();
        $categorias = Categoria::all();
        $producto = Producto::find($request->id);
        return view('producto.productoVer')
            ->with(['tipo_productos' => $tipo_productos])
            ->with(['unidad_medidas' => $unidad_medidas])
            ->with(['producto' => $producto])
            ->with(['categorias' => $categorias]);
    }

    public function ProductoNuevo()
    {
        $tipo_productos = TipoProducto::all();
        $unidad_medidas = UnidadMedida::all();
        $categorias = Categoria::all();
        return view('producto.productoNuevo')
            ->with(['tipo_productos' => $tipo_productos])
            ->with(['unidad_medidas' => $unidad_medidas])
            ->with(['categorias' => $categorias]);
    }

    public function ProductoNuevoPost(Request $request)
    {
        $this->validate($request, [
            'codigo' => 'unique:productos|nullable',
            'nombre' => 'required|unique:productos',
            'categoria_id' => 'required',
            'tipo_producto_id' => 'required',
            'unidad_medida_id' => 'required',
            'existencia_min' => 'numeric|nullable',
            'existencia_max' => 'numeric|nullable',
            'costo' => 'numeric|nullable',
        ]);
        $existencia_min = ($request->input('existencia_min') == null) ? 0 : $existencia_min = $request->input('existencia_min');
        $existencia_max = ($request->input('existencia_max') == null) ? 1000 : $existencia_max = $request->input('existencia_max');
        $costo = ($request->input('costo') == null) ? 0.00 : $request->input('costo');
        $producto = Producto::create([
            'nombre' => $request->input('nombre'),
            'nombre_alternativo' => $request->input('nombre_alternativo'),
            'tipo_producto_id' => $request->input('tipo_producto_id'),
            'categoria_id' => $request->input('categoria_id'),
            'unidad_medida_id' => $request->input('unidad_medida_id'),
            'existencia_min' => $existencia_min,
            'existencia_max' => $existencia_max,
            'costo' => $costo,
        ]);
        if ($request->input('codigo') == null)
        {
//          Asignacion del codigo del producto
            $ids = $producto->id;
            $ids = str_pad($ids, 5, '0', STR_PAD_LEFT);
            $codigo = $producto->tipo_producto->codigo. $producto->categoria->codigo . $ids;
            $producto->codigo = $codigo;
            $producto->update();
        } else
        {
//            Asignacion del codigo del producto
            $producto->codigo = $request->input('codigo');
            $producto->update();
        }
//        Mensaje de exito al guardar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'El producto fue ingresado correctamente!');
        return redirect()->route('productoVer',['id' => $producto->id]);
    }

    public function ProductoEditar(Request $request)
    {
        $tipo_productos = TipoProducto::all();
        $unidad_medidas = UnidadMedida::all();
        $categorias = Categoria::all();
        $producto = Producto::find($request->id);
        return view('producto.productoEditar')
            ->with(['tipo_productos' => $tipo_productos])
            ->with(['unidad_medidas' => $unidad_medidas])
            ->with(['producto' => $producto])
            ->with(['categorias' => $categorias]);
    }

    public function ProductoEditarPut(Request $request)
    {
        $producto = Producto::find($request->id);

        $this->validate($request, [
            'codigo' => [
                'required',
                Rule::unique('productos')->ignore($producto->id),
            ],
            'nombre' => [
                'required',
                Rule::unique('productos')->ignore($producto->id),
            ],
            'tipo_producto_id' => 'required',
            'unidad_medida_id' => 'required',
            'existencia_min' => 'numeric|nullable',
            'existencia_max' => 'numeric|nullable',
            'costo' => 'numeric|nullable',
            'precio' => 'numeric|nullable',
            'margen_ganancia' => 'numeric|nullable',
        ]);

        $existencia_min = ($request->input('existencia_min') == null) ? 0 : $existencia_min = $request->input('existencia_min');
        $existencia_max = ($request->input('existencia_max') == null) ? 1000 : $existencia_max = $request->input('existencia_max');
        $costo = ($request->input('costo') == null) ? 0.00 : $request->input('costo');
        $precio = ($request->input('precio') == null) ? 0.00 : $request->input('precio');
        $margen_ganancia = ($request->input('margen_ganancia') == null) ? 0 : $request->input('margen_ganancia');

        $producto->update([
            'nombre' => $request->input('nombre'),
            'nombre_alternativo' => $request->input('nombre_alternativo'),
            'tipo_producto_id' => $request->input('tipo_producto_id'),
            'unidad_medida_id' => $request->input('unidad_medida_id'),
            'categoria_id' => $request->input('categoria_id'),
            'existencia_min' => $existencia_min,
            'existencia_max' => $existencia_max,
            'precio' => $precio,
            'margen_ganancia' => $margen_ganancia,
        ]);

        if ($request->input('codigo') == null)
        {
//          Asignacion del codigo del producto
            $ids = $producto->id;
            $ids = str_pad($ids, 10, '0', STR_PAD_LEFT);
            $codigo = $producto->tipo_producto->codigo . $ids;
            $producto->codigo = $codigo;
            $producto->update();
        } else
        {
//            Asignacion del codigo del producto
            $producto->codigo = $request->input('codigo');
            $producto->update();
        }
//        Mensaje de exito al editar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'El producto fue modificado correctamente!');
        return redirect()->route('productoVer',['id' => $producto->id]);
    }

    public function ProductoEliminar(Request $request)
    {
        $producto = Producto::find($request->id);
        $producto->delete();
//        Mensaje de exito al eliminar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'El producto fue eliminado correctamente!');
        return redirect()->route('productoLista');
    }

    public function ProductoPrecioNuevoPost(Request $request, $id)
    {
        /**
         * Validacion de datos
         */
        $this->validate($request,[
            'precio_nuevo' => 'required'
        ]);

        $producto = Producto::find($id);
        $producto->precio = $request->input('precio_nuevo');
        $producto->saveOrFail();
        // Mensaje de exito
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'El precio del producto fue actualizado correctamente!');
        return redirect()->route('productoLista');

    }

    public function ProductoPrecio($id)
    {
        $producto = Producto::find($id);
        $unidad_medidas = UnidadMedida::all();
        return view('producto.productoPrecio')
            ->with(['producto' => $producto])
            ->with(['unidad_medidas' => $unidad_medidas]);
    }

    public function ProductoPrecioPost(Request $request, $id)
    {
        // Se carga el producto
        $producto = Producto::find($id);
        // Se guardan las variables del request
        $presentaciones = $request->input('presentacion');
        $unidades_medidas_id = $request->input('unidad_medida_id');
        $precios = $request->input('precio');
        $factores = $request->input('factor');
        // Se calcula el tamaño del array
        $max = sizeof($presentaciones);
        // Se recorre el array
        for ($i = 0;$i < $max; $i++)
        {
            // Se busca si el precio ya existe
            $precio = Precio::updateOrCreate([
                'producto_id' => $producto->id,
                'presentacion' => $presentaciones[$i],
            ],[
                'unidad_medida_id' => $unidades_medidas_id[$i],
                'precio' => $precios[$i],
                'factor' => $factores[$i],
            ]);
        }
        // Mensaje de exito
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'El precio del producto fue actualizado correctamente!');
        return redirect()->route('productoPrecio',['id' => $producto->id]);
    }

}
