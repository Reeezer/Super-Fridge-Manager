<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Favorite;
use App\Models\UserHas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use OpenFoodFacts\Laravel\Facades\OpenFoodFacts;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
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
     * shows the creation page but filled with correct infos from OFF
     */
    public function productInfoFromEAN(Request $request)
    {
        $product_info = OpenFoodFacts::barcode($request->ean_code);

        // only add elements to the final name if their index is defined (using a php compact notation, haters gonna hate)
        $final_name = "";

        if (($brand = $product_info["brands"] ?? null) != null)
            $final_name .= "$brand - ";

        if (($product_name = $product_info["product_name"] ?? null) != null)
            $final_name .= "$product_name";

        if (($quantity = $product_info["quantity"] ?? null) != null)
            $final_name .= " ($quantity)";

        return response()->json(["final_name" => $final_name]);
    }

    /**
     * Show the form for creating a new resource
     */
    public function create()
    {
        $categories = Category::all();
        return inertia('Products/Create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:5|max:50',
            'ean_code' => 'required|integer|gt:0',
            'category_id' => 'required|integer|exists:categories,id',
            'created_at' => 'required'
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')->with('success','Product created successfully.');
    }

    /**
     * Stores a product using the N-N relation table UserHas. It first checks if a product with the exact
     * name already exists, and if yes it just uses it (that means once a product has been added it cannot
     * be modified). If not, it creates it according to the given info.
     */
    public function storeUserProduct(Request $request)
    {
        $product_request = Product::where(
            ['name' => $request->name]
        )->get();

        if ($product_request->isEmpty())
        {
            $request->validate([
                'name' => 'required|min:5|max:50',
                'ean_code' => 'required|integer|gt:0',
                'category_id' => 'required|integer|exists:categories,id',
                'created_at' => 'required'
            ]);
            Product::create($request->all());
        }

        $product = Product::where(
            ['name' => $request->name]
        )->firstOrFail();

        UserHas::create([
            'user_id' => auth()->user()->id,
            'product_id' => $product->id,
            'added_date' => date("Y-m-d", strtotime($request->created_at))
        ]);

        return redirect()->route('products.index');
    }

    /**
     * Deletes a product from the UserHas relation table for that user, if it is present.
     */
    public function deleteUserProduct(Request $request)
    {
        $user_has = UserHas::where([
            'product_id' => $request->id,
            'user_id' => auth()->user()->id
        ])->firstOrFail();

        $user_has->delete();

        return redirect()->back();
    }

    /**
     * Displays the product details from the UserHas table
     *
     * @param  int  $id
     */
    public function show(Product $product)
    {
        $product = auth()->user()->products()->with('category')->where('product_id', $product->id)->firstOrFail();
        return inertia('Products/Show', compact('product'));
    }

    /**
     * Show the form for editing the specified product in the UserHas table.
     *
     * @param  int  $id
     */
    public function edit($id)
    {
        $categories = Category::all();
        $product = auth()->user()->products()->with('category')->where('product_id', $id)->firstOrFail();
        return inertia('Products/Edit', ['product' => $product, 'categories' => $categories]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function update(Request $request, Product $product)
    {
        // TODO we should check if user has privileges to edit products,
        // there's no UI element that allows this but the endpoint still exists

        $request->validate([
            'name' => 'required|min:5|max:25',
            'ean_code' => 'required|integer|gt:0',
            'category_id' => 'required|integer|exists:categories,id',
            'created_at' => 'required'
        ]);

        $product->update($request->all());
        return redirect()->back();
    }
    /**
     * Updates the product in the UserHas table
     */
    public function updateUserProduct(Request $request)
    {
        $request->validate([
            'added_date' => 'required'
        ]);

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

    /** 
     * Returns a list of products that match the name as json (API endpoint)
     */ 
    public function searchByName(Request $request)
    {
        $products = Product::where('name', 'like', '%' . $request->name . '%')->limit(10)->get();
        return response()->json(["products" => $products]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($id)
    {
        // TODO we should check if user has privileges to remove products,
        // there's no UI element that allows this but the endpoint still exists
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->back();
    }
}
