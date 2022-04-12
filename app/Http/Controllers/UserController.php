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
}
