<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Illuminate\Support\Facades\Validator;
use Purifier;
use Hash;
use App\User;
use JWTAuth;
use File;
use Mail;

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
        return Response::json(['error'=>"Error. Please fill out all fields!"]);
      }
      $email = $request->input('email');
      $password = $request->input('password');
      $cred = compact('email', 'password', ['email', 'password']);
      $token = JWTAuth::attempt($cred);
        return Response::json(compact('token'));
    }

    public function contact(Request $request)
    {
      $rules = [
        'email' => 'required',
        'message' => 'required'
      ];

      $validator = Validator::make(Purifier::clean($request->all()), $rules);
      if($validator->fails())
      {
        return Response::json(['error'=>"Error. Please fill out all fields!"]);
      }

      $email = $request->input('email');
      $message = $request->input('message');

      Mail::send('emails.contact', array('email'=>$email,'message'=>$message), function($message)
      {
        $message->to('suzette.verbeck@gmail.com', 'Suzette Verbeck')->subject('Contact From mrsverbeck.com');
      });
        return Response::json(['success'=>"Success! Thanks for contacting us."]);
    }
    public function index()
    {
      return File::get('index.html');
    }
}
