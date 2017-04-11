<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
use Response;

class ArticlesController extends Controller
{
    //will get list of Articles
    public function index()
    {

    }

    //store article - takes request param from frontend
    public function store(Request $request)
    {
      $article = new Article;
      $article->title = $request->input('title');
      $article->body = $request->input('body');

      $image = $request->file('image');
      $imageName = $image->getClientOriginalName();
      //move image to public-storage
      $image->move("storage/", $imageName);
      //storing link on server...request root is equal to whatever the domain name is
      $article->image = $request->root()."/storage/".$imageName;

      $article->save();

      //return a response from server to the frontend. Will get either a success or Error
      return Response::json(["success" => "Congratulations You Did It!"]);
    }

    //update function - 2 params id & request
    public function update($id, Request $request)
    {

    }

    //shows individal article
    public function show($id)
    {

    }

    //delete function to delete a single article
    public function destroy($id)
    {

    }


}
