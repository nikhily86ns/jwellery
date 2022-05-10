<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ProductController extends Controller
{

// Function to Add Product

    public function addProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'product_type' => 'required',
            'product_weight' => 'required',
            'product_rate' => 'required',
            'product_gst' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'data' => []
            ]);
        }
        $productData = $request->except(['profile']);
        if($request->hasFile('profile')) {
            $imageName = time().'.'.$request->profile->extension();
            $request->profile->move(public_path('images'), $imageName);
            $productData['profile'] = $imageName;
        }
        Product::create($productData);
        return response()->json([
            'status' => true,
            'message' => 'product created successfully',
            'data' => Product::orderBy('id', 'desc')->get()
        ]);
    }

// Function to List Product

    public function productList($id)
    {
        $data = Product::where('branch_id',$id)->orderBy('id','desc')->get();
        if(count($data) == 0)
        {
            return response()->json([
                'status' => false,
                'message' => 'No Products Added',
                'data' => []
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'All Products',
            'data' => $data
        ], 201);
    }

}
