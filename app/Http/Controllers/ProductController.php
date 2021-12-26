<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Favorite;
use App\Models\UserHas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // les produits que l'utilisateur a enregistré (via user_has)
        $products = auth()
            ->user()
            ->products()
            ->with('category')
            ->latest()
            ->paginate(20);

        // les favoris de l'utilisateur (via favorites)
        $favorites = auth()
            ->user()
            ->favorites()
            ->get();

        return inertia('Products/Index', compact('products', 'favorites'));
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
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        // FIXME check if user has privileges to add or remove products

        $request->validate([
            'name' => 'required|min:5|max:25',
            'ean_code' => 'required|integer|gt:0',
            'category_id' => 'required|integer|exists:categories,id',
            'created_at' => 'required'
        ]);

        $product->update($request->all());
        return redirect()->route('products.index');
    }

    // TODO ne pas oublier d'ajouter la route, utiliser POST
    public function updateUserProduct(Request $request)
    {
        $user_has = UserHas::where([
            ['user_id', auth()->user()->id],
            ['product_id', $request->id]
        ])->firstOrFail();
        
        $user_has->update(array(
            //'quantity' => $request->quantity,
            'added_date' => $request->added_date
        ));
        return redirect()->route('products.index');
    }

    public function deleteFavoriteProduct(Request $request)
    {
        $favorite = Favorite::where([
            ['user_id', auth()->user()->id],
            ['product_id', $request->id]
        ])->delete();

        return redirect()->route('products.index');
    }

    public function addFavoriteProduct(Request $request)
    {
        $user_id = auth()->user()->id;
        $product_id = $request->id;

        Favorite::create(compact('user_id', 'product_id'));

        return redirect()->route('products.index');
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

        return redirect()->route('products.index');
    }
}
