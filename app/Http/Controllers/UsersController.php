<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Illuminate\Support\Facades\Validator;
use Purifier;
use Hash;
use App\User;
use JWTAuth;

class UsersController extends Controller
{
    public function signUp(Request $request)
    {
      $rules = [
        'username' => 'required',
        'password' => 'required',
        'email' => 'required'
      ];

      $validator = Validator::make(Purifier::clean($request->all()), $rules);
      if($validator->fails())
      {
        return Response::json(['error'=>"Error. Please Fill Out All Fields!"]);
      }
      $check = User::where('email', '=', $request->input('email'))->first();
        if(!empty($check))
        {
          return Response::json(['error'=>"Email already exists"]);
        }
      $user = new User;
      $user->name = $request->input('username');
      $user->password = Hash::make($request->input('password'));
      $user->email = $request->input('email');
      $user->save();

      return Response::json(['success'=>'Thanks For Signing Up!']);

    }

    public function signIn(Request $request)
    {
      $rules = [
        'password' => 'required',
        'email' => 'required'
      ];

      $validator = Validator::make(Purifier::clean($request->all()), $rules);
      if($validator->fails())
      {
        return Response::json(['error'=>"Error. Please Fill Out All Fields!"]);
      }
      $email = $request->input('email');
      $password = $request->input('password');
      $cred = compact('email', 'password', ['email', 'password']);
      $token = JWTAuth::attempt($cred);
        return Response::json(compact('token'));
    }
}
