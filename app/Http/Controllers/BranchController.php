<?php

namespace App\Http\Controllers;
use App\Models\Branch;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DB;

use Illuminate\Http\Request;

class BranchController extends Controller
{
    //

// Function to create Branch

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'firm_gst' => 'required',
            'bis_no' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'profile' => 'mimes:jpg,bmp,png'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'data' => []
            ]);
        }
        $branchData = $request->except(['profile']);
        if($request->hasFile('profile')) {
            $imageName = time().'.'.$request->profile->extension();
            $request->profile->move(public_path('images'), $imageName);
            $branchData['profile'] = $imageName;
        }
        Branch::create($branchData);
        return response()->json([
            'status' => true,
            'message' => 'branch created successfully',
            'data' => Branch::orderBy('id', 'desc')->get()
        ]);
    }

// Function to get Branches

    public function branch()
    {
        $data = Branch::orderBy('id', 'desc')->get();
        if(count($data) == 0)
        {
            return response()->json([
                'status' => false,
                'message' => 'No Branches',
                'data' => []
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'Branches',
            'data' => $data
        ], 201);
    }



}
