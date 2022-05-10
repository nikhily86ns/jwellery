<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Village;
use App\Models\Bill;
use App\Models\Purchase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DB;

class PurchaseController extends Controller
{

// Add Purchase 

    public function addPurchase(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'phone' => 'required',
            'address' => 'required',
            'branch_id' => 'required',
            'item_name' => 'required',
            'rate' => 'required',
            'quantity' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'data' => []
            ]);
        }
        $purchaseData = $request->except(['profile']);
        if($request->hasFile('profile')) {
            $imageName = time().'.'.$request->profile->extension();
            $request->profile->move(public_path('images'), $imageName);
            $purchaseData['photo'] = $imageName;
        }
        Purchase::create($purchaseData);
        return response()->json([
            'status' => true,
            'message' => 'purchase created successfully',
            'data' => Purchase::orderBy('id', 'desc')->get()
        ]);
    }

// View Purchase

    public function purchase($id)
    {
        $data = Purchase::where('branch_id',$id)->orderBy('id','desc')->get();
        if(count($data) == 0)
        {
            return response()->json([
                'status' => false,
                'message' => 'No Purchases Added',
                'data' => []
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'All Purchases',
            'data' => $data
        ], 201);
    }



}
