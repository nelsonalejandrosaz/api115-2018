<?php

namespace App\Http\Controllers;

use App\Categoria;
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
        return view('producto.productoLista')->with(['productos' => $productos]);
    }

    public function ProductoVer(Request $request)
    {
        $tipoProductos = TipoProducto::all();
        $unidadMedidas = UnidadMedida::all();
        $categorias = Categoria::all();
        $producto = Producto::find($request->id);
        return view('producto.productoVer')
            ->with(['tipoProductos' => $tipoProductos])
            ->with(['unidadMedidas' => $unidadMedidas])
            ->with(['producto' => $producto])
            ->with(['categorias' => $categorias]);
    }

    public function ProductoNuevo()
    {
        $tipoProductos = TipoProducto::all();
        $unidadMedidas = UnidadMedida::all();
        $categorias = Categoria::all();
        return view('producto.productoNuevo')
            ->with(['tipoProductos' => $tipoProductos])
            ->with(['unidadMedidas' => $unidadMedidas])
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
            'existenciaMin' => 'numeric|nullable',
            'existenciaMax' => 'numeric|nullable',
            'costo' => 'numeric|nullable',
            'precio' => 'numeric|nullable',
            'margenGanancia' => 'numeric|nullable',
        ]);
        $existenciaMin = ($request->input('existenciaMin') == null) ? 0 : $existenciaMin = $request->input('existenciaMin');
        $existenciaMax = ($request->input('existenciaMax') == null) ? 1000 : $existenciaMax = $request->input('existenciaMax');
        $costo = ($request->input('costo') == null) ? 0.00 : $request->input('costo');
        $precio = ($request->input('precio') == null) ? 0.00 : $request->input('precio');
        $margenGanancia = ($request->input('margenGanancia') == null) ? 0 : $request->input('margenGanancia');
        $producto = Producto::create([
            'nombre' => $request->input('nombre'),
            'tipo_producto_id' => $request->input('tipo_producto_id'),
            'categoria_id' => $request->input('categoria_id'),
            'unidad_medida_id' => $request->input('unidad_medida_id'),
            'existenciaMin' => $existenciaMin,
            'existenciaMax' => $existenciaMax,
            'costo' => $costo,
            'precio' => $precio,
            'margenGanancia' => $margenGanancia,
        ]);
        if ($request->input('codigo') == null)
        {
//          Asignacion del codigo del producto
            $ids = $producto->id;
            $ids = str_pad($ids, 10, '0', STR_PAD_LEFT);
            $codigo = $producto->tipoProducto->codigo . $ids;
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
        $tipoProductos = TipoProducto::all();
        $unidadMedidas = UnidadMedida::all();
        $categorias = Categoria::all();
        $producto = Producto::find($request->id);
        return view('producto.productoEditar')
            ->with(['tipoProductos' => $tipoProductos])
            ->with(['unidadMedidas' => $unidadMedidas])
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
            'existenciaMin' => 'numeric|nullable',
            'existenciaMax' => 'numeric|nullable',
            'costo' => 'numeric|nullable',
            'precio' => 'numeric|nullable',
            'margenGanancia' => 'numeric|nullable',
        ]);

        $existenciaMin = ($request->input('existenciaMin') == null) ? 0 : $existenciaMin = $request->input('existenciaMin');
        $existenciaMax = ($request->input('existenciaMax') == null) ? 1000 : $existenciaMax = $request->input('existenciaMax');
        $costo = ($request->input('costo') == null) ? 0.00 : $request->input('costo');
        $precio = ($request->input('precio') == null) ? 0.00 : $request->input('precio');
        $margenGanancia = ($request->input('margenGanancia') == null) ? 0 : $request->input('margenGanancia');

        $producto->update([
            'nombre' => $request->input('nombre'),
            'tipo_producto_id' => $request->input('tipo_producto_id'),
            'unidad_medida_id' => $request->input('unidad_medida_id'),
            'categoria_id' => $request->input('categoria_id'),
            'existenciaMin' => $existenciaMin,
            'existenciaMax' => $existenciaMax,
            'precio' => $precio,
            'margenGanancia' => $margenGanancia,
        ]);

        if ($request->input('codigo') == null)
        {
//          Asignacion del codigo del producto
            $ids = $producto->id;
            $ids = str_pad($ids, 10, '0', STR_PAD_LEFT);
            $codigo = $producto->tipoProducto->codigo . $ids;
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

}
