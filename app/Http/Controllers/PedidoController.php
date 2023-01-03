<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pedidos = Pedido::orderBy('id', 'desc')
                            ->with('cliente', 'productos', 'user')
                            ->paginate(15);

        return response()->json($pedidos);
    }

    public function fitrar(Request $request)
    {
        $gestion = $request->gestion;
        $modalidad = isset($request->modalidad)?$request->modalidad:'';
        $ci_nit = $request->ci_nit;
        if(isset($gestion)){
            if($modalidad){
                // DB::select("select * from pedidos where gestion=$gestion ")
                return Pedido::where("fecha", "like", "%".$gestion."%")
                        ->where("estado", "=", $modalidad)
                        ->with('cliente')
                        ->paginate(15);
            }
        }
        return Pedido::paginate(15);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "cliente_id" => "required",
            "productos" => "required"
        ]);
        DB::beginTransaction();

        try {
            /*
             {
                cliente_id: 4,
                productos: [
                    {producto_id: 5, cantidad: 2},
                    {producto_id: 2, cantidad: 1},
                    {producto_id: 6, cantidad: 1}
                ]
             }
             */
            // registrar o buscar el cliente
            $cliente_id = $request->cliente_id;

            $user = Auth::user();
            // registrar el pedido (estado: 1)
            $pedido = new Pedido();
            $pedido->fecha = date("Y-m-d H:i:s");
            $pedido->user_id = $user->id;
            // asignamos el cliente al pedido
            $pedido->cliente_id = $cliente_id;
            $pedido->save();

            // asignar los productos al pedido
            $productos = $request->productos;
            foreach ($productos as $prod) {
                $pedido->productos()->attach($prod["producto_id"], ["cantidad" => $prod["cantidad"]]);
            }
            // actualizar el pedido (estado: 2)
            $pedido->estado = 2;
            $pedido->update();
        
            DB::commit();
            // all good
            return response()->json(["mensaje" => "Pedido Registrado"]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(["mensaje" => "Ocurrió un problema al registrar el pedido", "error" => $e]);
            // something went wrong
        }        
        
        // ----------------------------------

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pedido = Pedido::with('cliente', 'productos')->findOrFail($id);

        return response()->json($pedido);
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
