<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Village;
use App\Models\Bill;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DB;

class BillController extends Controller
{

// Create GST Bill

    public function addGstBill(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'father_name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'pan_no' => 'required',
            'branch_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'data' => []
            ]);
        }
        $gstBillData = $request->except(['profile']);
        if($request->hasFile('profile')) {
            $imageName = time().'.'.$request->profile->extension();
            $request->profile->move(public_path('images'), $imageName);
            $gstBillData['profile'] = $imageName;
        }
        $bill = Bill::create($gstBillData);
        $data = Bill::where('id',$bill->id)->get();
        foreach($data as $row)
        {
            $product = array();
            foreach(json_decode($row->product_id) as $res)
            {
                $pro = Product::where('id',$res)->first();
                array_push($product, $pro);
            }
            $row->product = $product;
        }
        return response()->json([
            'status' => true,
            'message' => 'GST Bill created successfully',
            'data' => $data
        ]);
    }

// Sales Report GST

    public function salesGst($id)
    {
        $data = Bill::where('gst_no','!=',NULL)->where('branch_id',$id)->orderBy('id','desc')->get();
        foreach($data as $row)
        {
            $product = array();
            foreach(json_decode($row->product_id) as $res)
            {
                $pro = Product::where('id',$res)->first();
                array_push($product, $pro);
            }
            $row->product = $product;
        }
        if(count($data) == 0)
        {
            return response()->json([
                'status' => false,
                'message' => 'No Bills Added',
                'data' => []
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'All GST Bills',
            'data' => $data
        ], 201);
    }

// Create Estimate Bill

    public function addEstBill(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'father_name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'pan_no' => 'required',
            'branch_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'data' => []
            ]);
        }
        $gstBillData = $request->except(['profile']);
        if($request->hasFile('profile')) {
            $imageName = time().'.'.$request->profile->extension();
            $request->profile->move(public_path('images'), $imageName);
            $gstBillData['profile'] = $imageName;
        }
        $bill = Bill::create($gstBillData);
        $data = Bill::where('id',$bill->id)->get();
        foreach($data as $row)
        {
            $product = array();
            foreach(json_decode($row->product_id) as $res)
            {
                $pro = Product::where('id',$res)->first();
                array_push($product, $pro);
            }
            $row->product = $product;
        }
        return response()->json([
            'status' => true,
            'message' => 'Estimate Bill created successfully',
            'data' => $data
        ]);
    }

// Sales Report Estimate

    public function salesEst($id)
    {
        $data = Bill::where('gst_no','=',NULL)->where('branch_id',$id)->orderBy('id','desc')->get();
        foreach($data as $row)
        {
            $product = array();
            foreach(json_decode($row->product_id) as $res)
            {
                $pro = Product::where('id',$res)->first();
                array_push($product, $pro);
            }
            $row->product = $product;
        }
        if(count($data) == 0)
        {
            return response()->json([
                'status' => false,
                'message' => 'No Bills Added',
                'data' => []
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'All Estimate Bills',
            'data' => $data
        ], 201);
    }


}
