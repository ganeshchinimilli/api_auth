<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class PassportAuthController extends Controller
{
    /**
     * Registration
     */
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Generate an access token for the user
        $token = $user->createToken('AuthToken')->accessToken;

        // Return success response with token
        return response()->json([
            'token' => $token,
            'message' => 'User registered successfully'
        ], 200);
    }

    /**
     * Login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ])) {
           $user = Auth::user();
           $token = $user->createToken('myToken')->accessToken;
           return response()->json([
            'status' => true,
            'token' => $token,
            'message' => 'User logged in  successfully'
        ], 200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Inavlid login details'
            ], 405);
        }

    }

    public function profile(Request $request){
        $user  = Auth::user();
        return response()->json([
            'status' => true,
            'message' => 'Profile Information successfully',
            'data' => $user,
        ], 200);
     }

    public function logout(){
        auth()->user()->token()->revoke();
        return response()->json([
            'status' => true,
            'message' => 'Profile  Logout successfully',
        ], 200);
    }
}
