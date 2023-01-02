<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResourceDashboard;
use App\Models\Photo;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductsForDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $products = Product::withCount(['orderCounts'])->when(request('keyword'),function($q){
            $keyword = request('keyword');
            $q->where('title', 'like', "%$keyword%")->orWhere('description', 'like', "%$keyword%");
        })->latest()->paginate(5)->withQueryString();
        return response()->json([
            'products' => ProductResourceDashboard::collection($products)->response()->getData(true),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();
        return response()->json([
            'message' => 'The Product is deleted!',
        ]);
    }

    public function customUpdate(Request $request)
    {
        $product = Product::find($request->id);
        $request->validate([
            'title' => 'nullable|string|min:3|unique:products,title,' . $product->id,
            'description' => 'nullable|string|min:5',
            'price' => 'nullable|numeric',
            'featured_image' => 'nullable',
            'category_id' => 'nullable',
            "photos" => "nullable",
            "photos.*" => "file|mimes:jpeg,jpg,webp,png|max:2048"
        ]);
        if ($request->has('title')) {
            $product->title = $request->title;
        }
        if ($request->has('description')) {
            $product->description = $request->description;
        }
        if ($request->has('category_id')) {
            $product->category_id = $request->category_id;
        }
        if ($request->has('price')) {
            $product->price = $request->price;
        }
        if ($request->has('featured_image')) {
            Storage::delete($product->featured_image);
            $Name = $request->featured_image->store('public/featured_image');
            $product->featured_image = $Name;
        }
        $product->update();

        if ($request->has('photos')) {
            // deleting old photos
            foreach ($product->photos as $photo) {
                Storage::delete($photo->name);
                $photo->delete();
            }
            $photos = [];
            foreach ($request->file('photos') as $key=>$photo) {
                $name = $photo->store('public/products');
                $photos[$key] = new Photo(['name' => $name]);
            }
            $product->photos()->saveMany($photos);
        }


        return response()->json([
            'message' => 'The product is updated!',
        ]);
    }
}
