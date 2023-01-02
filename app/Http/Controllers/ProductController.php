<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Photo;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()

    {
        $products = Product::when(request('category'), function ($query) {
            $query->where('category_id', request('category'));
        })->
        when(request('keyword'), function ($query) {
            $keyword = request('keyword');
            $query->where('title', 'like', "%$keyword%");
        })
            ->latest()->paginate(9);
        return response()->json([
            'products' => ProductResource::collection($products)->response()->getData(true),
            'success' => true,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|min:3|unique:products',
            'description' => 'required|string|min:5',
            'price' => 'required|numeric',
            'featured_image' => 'required|image',
            'category_id' => 'required',
            "photos" => "required",
            "photos.*" => "file|mimes:jpeg,jpg,webp,png|max:2048"
        ]);
        $img_name = $request->file('featured_image')->store('public/featured_image');
        $product = Product::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'featured_image' => $img_name,
        ]);
        $photos = [];
        foreach ($request->file('photos') as $key => $photo) {
            $photoName = $photo->store('public/products');
            $photos[$key] = new Photo(['name' => $photoName]);
        }

        $product->photos()->saveMany($photos);
        return response()->json(['success' => true]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return abort(404, 'the product is not found!');
        }
        return response()->json([
            'product' => new ProductResource($product),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
