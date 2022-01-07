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


    // FIXME add proper comment lol, shows the creation page but filled with correct infos from OFF
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
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
        return redirect()->back();
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
        return redirect()->back();
    }

    // returns a list of products that match the name as json (API endpoint)
    public function searchByName(Request $request)
    {
        $products = Product::where('name', 'like', '%' . $request->name . '%')->limit(10)->get();
        return response()->json(["products" => $products]);
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

        return redirect()->back();
    }
}
