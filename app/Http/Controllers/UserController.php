<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required|confirmed',
        ]);

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=> Hash::make($request->password),
        ]);
        // generate the token for each user going to use my api

        $token =$user->createToken('myToken')->plainTextToken;

        return response([
            'user'=>$user,
            'token'=>$token,
        ],201);
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return response([
            'message' => 'Successfully logged out!!'
        ],200);
    }

    // yaha where clause me email jo hai o column hai or $request->email jo hai o user dalega o hai

    public function login(Request $request){
        $request->validate([
            'email'=>'required|email',
            'password'=>'required',
        ]);
           $user = User::where('email',$request->email)->first();

           if(!$user || !Hash::check($request->password,$user->password)){
               return response([
                   'message' => 'The provided credentials are incorrect.'
               ],401);
           }

           else {
            $token =$user->createToken('myToken')->plainTextToken;

            return response([
                'user'=>$user,
                'token'=>$token,
            ],200);
           }
    }
}
