<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\User;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    // response with token
    protected function respondWithToken($token)
    {
        $user = auth('api')->user()->id;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => $this->guard()->user(),
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    //guard
    public function guard(){
        return Auth::Guard('api');
    }

    //authenticated user
    public function AuthUser()
    {
        return response()->json(auth('api')->user());
    }

    // log in function
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 441);
        }

        if(auth('api')->user()->status == 0){
            return response()->json(['error' => 'Unauthorized'], 551);
        }

        $user = auth('api')->user();
        if($user->status == 1){
            $user->update([
                $user->is_loggedin = true
            ]);
            return $this->respondWithToken($token);
        }
    }

    public function me()
    {
        return response()->json(auth('api')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $user = auth('api')->user();
        $user->update([
            $user->is_loggedin = false
        ]);

        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    // register function
    public function register(Request $request){
        // validate incoming request
        $this->validate($request, [
            'user.first_name' => 'required|min:2|max:40',
            'user.last_name' => 'required|min:2|max:40',
            'user.email' => 'required|email|unique:users,email',
            'user.phone' => 'required|max:14',
            'user.password' => 'required|min:5|max:30|confirmed',
            'user.password_confirmation' => 'required'
        ]);

        // create a new user
        $user = new User;
        $user->first_name = $request->user['first_name'];
        $user->last_name = $request->user['last_name'];
        $user->email = $request->user['email'];
        $user->phone = $request->user['phone'];
        $user->status = true;
        $user->password = Hash::make($request->user['password']);
        $user->save();

        return response()->json($user, 200);
    }
}
