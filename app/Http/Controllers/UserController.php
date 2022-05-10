<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Branch;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DB;

class UserController extends Controller
{
    //

    public function checkToken() 
    {
        $x = new \stdClass();
        $headers = getallheaders();
        if(isset($headers['token']))
        {
            $check = DB::table('personal_access_tokens')->where('token',$headers['token'])->select('tokenable_id')->orderBy('id','desc')->first();
            if(!isset($check->tokenable_id))
            {
                return response()->json(['success'=>false,'data'=>$x,'message'=>'token mis matched'], 401);
                die();
            }else{
                $this->userId = $check->tokenable_id;
            }
        }else{
            return response()->json(['success'=>false,'data'=>array(),'message'=>'token blanked'], 401);
            die();
        }
    }

// Register User 

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|regex:/^[a-zA-Z]+$/u|max:255",
            "email" => 'required|email|unique:users,email|regex:/^.+@.+$/i', 
            "password" => "required",
            "phone" => "required|min:10|numeric"
        ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                    'data' => []
                ]);
            }
            else
            { 
                $destinationPath = public_path('/image');
                $filename = '';
                if($request->hasfile('profile'))
                {
                    $image = $request->file('profile');
                    $filename = time() . '.' . $image->getClientOriginalExtension();
                    $image->move($destinationPath, $filename);   
                    
                    $users = new User();
                    $users->name = $request->name;
                    $users->father_name = $request->father_name;
                    $users->email = $request->email;
                    $users->password = Hash::make($request->password);
                    $users->phone = $request->phone;
                    $users->address = $request->address;
                    $users->branch_id = $request->branch_id;
                    $users->aadhar = $request->aadhar;
                    $users->voter_id = $request->voter_id;
                    $users->pan_card =  $request->pan_card;
                    $users->profile = $filename;
                    $users->save();
                }
                else
                {
                    $users = new User();
                    $users->name = $request->name;
                    $users->father_name = $request->father_name;
                    $users->email = $request->email;
                    $users->password = Hash::make($request->password);
                    $users->phone = $request->phone;
                    $users->address = $request->address;
                    $users->branch_id = $request->branch_id;
                    $users->aadhar = $request->aadhar;
                    $users->voter_id = $request->voter_id;
                    $users->pan_card =  $request->pan_card;
                    $users->profile = '';
                    $users->save();
                }
                
            }

        $token = $users->createToken('my-app-token')->accessToken;

        $users->token = DB::table('personal_access_tokens')->select('token')->where('id', $token->id)->first()->token;

        return response()->json([
            'status' => true,
            'message' => 'User successfully Registered',
            'data' => [$users]
        ], 201);
    }

// Login User

    public function login(Request $request)
    { 
        $validator = Validator::make($request->all(), [
            'email' => 'email',
            'password' => 'required',
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        if ($validator->fails()) {  
       
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'data' => []
            ]);

        }
        else
        {
            $users = User::where('email', '=', $email)->first();
            if (!$users) {
               return response()->json(['status'=>false, 'message' => 'Login Fail, please check email id','data' => []]);
            }
            if (!( Hash::check($password, $users->password))) {
               return response()->json(['status'=>false, 'message' => 'Login Fail, Wrong password. Try again or click ‘Forgot password’ to reset it.','data' => []]);
            }
            $token = $users->createToken('my-app-token')->accessToken;
            $users->token = DB::table('personal_access_tokens')->select('token')->where('id', $token->id)->first()->token;
            return response()->json(['status'=>true,'message'=>'Login Successful', 'data' => [$users] ]);
        }
    }

// User Listing

    public function users($id)
    {
        $data = User::where('is_admin','=',0)->where('branch_id',$id)->orderBy('id', 'desc')->get();
        if(count($data) == 0)
        {
            return response()->json([
                'status' => false,
                'message' => 'No Users',
                'data' => []
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'user list get successfully',
            'data' => $data
        ], 201);
    }



}
