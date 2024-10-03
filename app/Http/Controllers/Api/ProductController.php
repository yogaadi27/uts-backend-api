<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //get all
        $products = Product::with(['category'])->latest()->paginate(5);

        //response
        $response = [
        'status'   => 'success',
            'message'   => 'List all products',
            'data'      => $products,
        ];

        return response()->json($response, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validasi data
        $validator = Validator::make($request->all(),[
            'category_id' => 'required',
            'product' => 'required|unique:products',
            'description' => 'required',
            'price' => 'required|integer',
            'stok' => 'required|integer',
            'image' => 'required',
        ]);

        //jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'status'   => 'failed',
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ],422);
        }

        //upload image products to storage
        $image = $request->file('image');
        $image->storeAs('public/products', $image->hashName());

        //insert products to database
        $character = Product::create([
            'category_id' => $request->category_id,
            'product' => $request->product,
            'price' => $request->price,
            'stok' => $request->stok,
            'image'     => $image->hashName(),
        ]);

        //response
        $response = [
            'status'   => 'success',
            'message'   => 'Add product success',
            'data'      => $character,
        ];

        return response()->json($response, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //category products by ID
        $product = Product::with(['category'])->find($id);

        if ($product!=null) {
            //response if data found
            $response = [
                'status'   => 'success',
                'message'   => 'Detail product found',
                'data'      => $product,
            ];

            return response()->json($response, 200);
        }else{
            //response if data found
            $response = [
                'status'   => 'failed',
                'message'   => 'Detail category not found',
            ];

            return response()->json($response, 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //validasi data
       $validator = Validator::make($request->all(),[
            'category_id' => 'required',
            'product' => 'required|unique:products',
            'description' => 'required',
            'price' => 'required|integer',
            'stok' => 'required|integer',
        ]);

        //jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'status'   => 'failed',
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ],422);
        }

        //find product by ID
        $product = Product::find($id);

        //check if image is not empty
        if ($request->hasFile('image')) {

            //upload image
            $image = $request->file('image');
            $image->storeAs('public/products', $image->hashName());

            //delete old image
            Storage::delete('public/products/' . basename($product->image));

            //update post with new image
            $product->update([
                'category_id' => $request->category_id,
                'product' => $request->product,
                'description' =>$request->description,
                'price' => $request->price,
                'stok' => $request->stok,
                'image'     => $image->hashName(),
            ]);
        } else {

                //update post without image
                $product->update([
                    'category_id' => $request->category_id,
                    'product' => $request->product,
                    'description' =>$request->description,
                    'price' => $request->price,
                    'stok' => $request->stok,
                ]);
        }

        //response
        $response = [
            'status'   => 'success',
            'message'   => 'Update product success',
            'data'      => $product,
        ];

        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //find character by ID
        $product = Product::find($id);

        if (isset($product)) {
            //jika data ditemukan delete image from storage
            Storage::delete('public/products/'.basename($product->image));

            //delete post
            $product->delete();

            $response = [
                'success'   => 'Delete products success',
            ];
            return response()->json($response, 200);

        } else {
            //jika data tidak ditemukan
            $response = [
                'success'   => 'Data products not found',
            ];

            return response()->json($response, 404);

        }
    }
}
