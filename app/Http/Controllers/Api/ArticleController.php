<?php

namespace App\Http\Controllers\API;

use App\Models\Article;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Http\Controllers\API\ResponseController as ResponseController;
use App\Http\Resources\Article as ArticleResource;

class ArticleController extends ResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::all();

        return $this->sendResponse(ArticleResource::collection($articles), 'Articles Retrieved Successfully.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'name' => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $article = Article::create($input);
   
        return $this->sendResponse(new ArticleResource($article), 'Article Created Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = Article::find($id);
  
        if (is_null($article)) {
            return $this->sendError('Article not found.');
        }
   
        return $this->sendResponse(new ArticleResource($article), 'Article Retrieved Successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input =  $request->all();

        $validator = Validator::make($input,[
            'name' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error',$validator->errors());
        }

        $article = Article::find($id);

        if (is_null($article)) {
            return $this->sendError('Record does not exist!!.Not updated');
        }

        $article->name = $input['name'];
        $article->description = $input['description'];
        $article->save();

        return $this->sendResponse(new ArticleResource($article),'Article Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = Article::find($id);
        if(is_null($article)){
            return $this->sendError('This article does not exist!!');
        }

        $article->delete();
        return $this->sendResponse([],'Article Deleted Successfully');
    }
}
