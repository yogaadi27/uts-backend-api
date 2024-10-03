<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         //get all
         $categories = Category::latest()->paginate(5);

         //response
         $response = [
            'status'   => 'success',
             'message'   => 'List all categories',
             'data'      => $categories,
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
            'category' => 'required|unique:categories',
        ]);

        //jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'status'   => 'failed',
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ],422);
        }

        //jika validasi sukses masukan data level ke database
        $category = Category::create([
            'category' => $request->category,
        ]);

        //response
        $response = [
            'status'   => 'success',
            'message'   => 'Add category success',
            'data'      => $category,
        ];

        return response()->json($response, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //category Level by ID
        $category = Category::find($id);

        if ($category!=null) {
            //response if data found
            $response = [
                'status'   => 'success',
                'message'   => 'Detail category found',
                'data'      => $category,
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
        //define validation rules
        $validator = Validator::make($request->all(), [
            'category' => 'required|unique:categories',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status'   => 'failed',
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ],422);
        }

        //find level by ID
        $category = Category::find($id);

        $category->update([
            'category' => $request->category,
        ]);

        //response
        $response = [
            'status'   => 'success',
            'message'   => 'Update category success',
            'data'      => $category,
        ];

        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //find level by ID
        $category = Category::find($id);

        if (isset($category)) {

            //delete post
            $category->delete();

            $response = [
                'status'   => 'success',
                'success'   => 'Delete Category Success',
            ];
            return response()->json($response, 200);

        } else {
            //jika data tidak ditemukan
            $response = [
                'status'   => 'failed',
                'success'   => 'Data Category Not Found',
            ];

            return response()->json($response, 404);

        }

    }
}
