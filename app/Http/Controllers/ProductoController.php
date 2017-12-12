<?php

namespace App\Http\Controllers;

use App\Categoria;
use App\Producto;
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
            'nombre' => 'required|unique:productos',
            'categoria_id' => 'required',
            'tipo_producto_id' => 'required',
            'unidad_medida_id' => 'required',
            'existenciaMin' => 'numeric|nullable',
            'existenciaMax' => 'numeric|nullable',
        ]);

//        if (Producto::where('nombre',$request->nombre)->first() != null) {
//            $request->flash();
//            session()->flash('mensaje.tipo', 'danger');
//            session()->flash('mensaje.icono', 'fa-ban');
//            session()->flash('mensaje.contenido', 'El producto con ese nombre ya existe!');
//            return redirect()->route('productoNuevo');
//        }

        $producto = Producto::create($request->only(
            'nombre',
            'tipo_producto_id',
            'unidad_medida_id',
            'categoria_id',
            'existenciaMin',
            'existenciaMax'
        ));
        // Asignacion del codigo del producto
        $ids = $producto->id;
        $ids = str_pad($ids, 10, '0', STR_PAD_LEFT);
        $codigo = $producto->tipoProducto->codigo . $ids;
        $producto->codigo = $codigo;
        $producto->update();
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
            'nombre' => [
                'required',
                Rule::unique('productos')->ignore($producto->id),
            ],
            'tipo_producto_id' => 'required',
            'unidad_medida_id' => 'required',
            'existenciaMin' => 'numeric|nullable',
            'existenciaMax' => 'numeric|nullable',
        ]);

        $producto->update($request->only(
            'nombre',
            'tipo_producto_id',
            'unidad_medida_id',
            'categoria_id',
            'existenciaMin',
            'existenciaMax'));

        // Asignacion del codigo del producto
        $ids = $producto->id;
        $ids = str_pad($ids, 10, '0', STR_PAD_LEFT);
        $codigo = $producto->TipoProducto->codigo . $ids;
        $producto->codigo = $codigo;
        $producto->update();
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
