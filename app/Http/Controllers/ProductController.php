<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\UserHas;
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
        $products = auth()->user()->products()->with('category')->latest()->paginate(20);
        return inertia('Products/Index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $product = auth()->user()->products()->with('category')->where('product_id', $product->id)->firstOrFail();
        return inertia('Products/Show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = Category::all();
        $product = auth()->user()->products()->with('category')->where('product_id', $id)->firstOrFail();
        return inertia('Products/Edit', ['product' => $product, 'categories' => $categories]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|min:5|max:25',
            'ean_code' => 'required|integer|gt:0',
            'category_id' => 'required|integer|exists:categories,id',
            'created_at' => 'required'
        ]);

        $product->update($request->all());
        return redirect()->route('products.index')->with('success','Product updated successfully'); // TODO Remove with
    }

    // TODO ne pas oublier d'ajouter la route, utiliser POST
    public function updateUserProduct(Request $request, Product $product)
    {
        $user_has = UserHas::where([
            ['user_id', auth()->user()->id],
            ['product_id', $product->id]
        ])->first();

        // $user_has->update();
        var_dump($product);
        return "";
        //return redirect()->route('products.index')->with('product', $product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success','Product deleted successfully'); // TODO Remove with
    }
}
