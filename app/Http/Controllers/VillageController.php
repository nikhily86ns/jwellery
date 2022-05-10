<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Village;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DB;

class VillageController extends Controller
{

// Function to Add Product

    public function addVillage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'data' => []
            ]);
        }
        $villageData = $request->except(['profile']);
        Village::create($villageData);
        return response()->json([
            'status' => true,
            'message' => 'Village created successfully',
            'data' => Village::orderBy('id', 'desc')->get()
        ]);
    }

// Function to get Branches

    public function village()
    {
        $data = Village::orderBy('id', 'desc')->get();
        if(count($data) == 0)
        {
            return response()->json([
                'status' => false,
                'message' => 'No Village',
                'data' => []
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'Village',
            'data' => $data
        ], 201);
    }


}
