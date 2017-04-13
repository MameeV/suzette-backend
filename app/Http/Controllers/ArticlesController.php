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
      //create a query to get a list of articles and receive them on the Frontend
      $articles = Article::all();

      return Response::json($articles);
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

      //return a response from server to the frontend. Will get either a success or Error. I Prefer this command on backend
      return Response::json(["success" => "Congratulations You Did It!"]);
    }

    //update function - 2 params id & request
    public function update($id, Request $request)
    {
      $article = Article::find($id);

      $article->title = $request->input('title');
      $article->body = $request->input('body');

      $image = $request->file('image');
      $imageName = $image->getClientOriginalName();
      $image->move("storage/", $imageName);
      $article->image = $request->root()."/storage/".$imageName;
      $article->save();

      return Response::json(["success" => "Article Has Been Updated!"]);
    }

    //shows individal article
    public function show($id)
    {
      $article = Article::find($id);

      return Response::json($article);
    }

    //delete function to delete a single article
    public function destroy($id)
    {
      $article = Article::find($id);

      $article->delete();

      return Response::json(["success" => "Article Deleted."]);
    }


}
