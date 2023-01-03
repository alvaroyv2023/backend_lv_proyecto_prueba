<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //miempresa.com/api/producto?page=2&q=
        $orden = $request->orden!="null"?$request->orden:'id';
        $rows = $request->rows?$request->rows:3;
        if($request->q){
            // buscar
            $productos = Producto::orWhere("nombre", "like", "%".$request->q."%")
                                    ->orWhere("precio", "like", "%".$request->q."%")
                                    ->orderBy($orden, "desc")
                                    ->paginate($rows);
        }else{
            $productos = Producto::with('categoria')
                                    ->orderBy($orden, "desc")
                                    ->paginate($rows);
        }

        return response()->json($productos, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validacion
        $request->validate([
            "nombre" => "required|min:2|max:200",
            "categoria_id" => "required",
            "imagen" => "file|mimes:png,jpg,jpeg"
        ]);

        // subida de archivos
        $direccion_imagen = "";
        if($file = $request->file("imagen")){
            $direccion_imagen= time() . "-".$file->getClientOriginalName();
            
            $file->move("imagenes", $direccion_imagen);

            $direccion_imagen = "imagenes/". $direccion_imagen;
        }

        // guardamos
        $producto = new Producto();
        $producto->nombre = $request->nombre;
        $producto->precio = $request->precio;
        $producto->stock = $request->stock;
        $producto->imagen = $direccion_imagen;
        $producto->descripcion = $request->descripcion;
        $producto->categoria_id = $request->categoria_id;
        $producto->save();

        return response()->json(["mensaje" => "Producto registrado"], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
