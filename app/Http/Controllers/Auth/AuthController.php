<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|confirmed|min:4|max:10'
        ], [
            'name.required' => "Please Enter User Name",
            'email.required' => "Please Enter User email",
            'email.unique' => "Already User Exists",
            'password.required' => "Please Enter User Password",
            'password.confirmed' => "Password and Confirm password must be the same"
        ]);

        if ($validator->fails()) {
            return response()->json([
                'messege' => "Validation Errors",
                'errors' => $validator->errors()
            ], 432);
        }

        $user = User::where('email', $request->email)->first();
        if ($user) {
            return response()->json([
                'status' => 'success',
                'message' => 'User Already Have a Account ..'
            ], 422);
        }


        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $user->save();

        $token = $user->createToken($request->email)->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'User Registration Successfully',
            'user' => $user,
            'token' => $token
        ], 201);
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ], [
            'email.required' => 'Please Enter User Email for Login',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => "validation Errors",
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();


        if (!$user) {
            return response()->json([
                "message" => "User Email not match with our Records"
            ]);
        }

        if (!Hash::check($request->password, $user->password)) {

            return response()->json([
                'message' => "Password mismatch.."
            ]);
        }

        $token = $user->createToken($request->email)->plainTextToken;

        return response()->json([
            'message' => "Login Successfully ",
            'user' => $user,
            'token' => $token
        ], 200);
    }
}
