<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::where('active', true)
                  ->where('qty_active', '>=', 1)
                  ->get();

        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();

        if ($user->admin) {

            $validatedData = $request->validate([
                'name' => 'required|string|max:100',
                'description' => 'required|string|max:255',
                'price' => 'required|numeric',
                'qty_available' => 'required|numeric|min:1',
                'qty_active' => 'required|numeric',
            ]);

            $product = Product::create($validatedData);

            return response()->json($product, 201);

        } else {

            $message = [
                'message' => 'Unauthorize resource',
            ];

            return response()->json($message, 403);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        /*
         * TODO
         * Es necesaria una vista para los detalles del producto?
         * en esta vista se deben mostrar
         * detalles del producto que
         * no estan disponibles en la
         * vista de lista
         */
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        /*
         * TODO
         * Actualizar las validaciones para los campos nuevos
         * - Cantidades en inventario
         * - Cantidades activas /disponibles para la venta
         * - Precios
         */

        $user = $request->user();

        if ($user->admin) {

            $validatedData = $request->validate([
                'id' => 'required|numeric',
                'name' => 'string|max:100',
                'description' => 'string|max:255',
                'price' => 'numeric',
            ]);

            $product = Product::find($validatedData['id']);
            $product->fill($validatedData);
            $product->save();

            return response()->json($product);

        } else {

            $message = [
                'message' => 'Unauthorize resource',
            ];

            return response()->json($message, 403);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user = $request->user();

        if ($user->admin) {

            $validatedData = $request->validate([
                'id' => 'required|numeric',
            ]);

            $product = Product::find($validatedData['id']);
            $product->delete($validatedData);

            return response()->json($product);

        } else {

            $message = [
                'message' => 'Unauthorize resource',
            ];

            return response()->json($message, 403);

        }
    }
}
