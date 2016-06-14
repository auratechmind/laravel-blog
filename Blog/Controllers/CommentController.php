<?php

namespace App\Modules\Blog\Controllers;

use App\Modules\Blog\Models\Category;
use App\Modules\Blog\Models\Comments;
use App\Modules\Blog\Models\Posts;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {

        $input['from_user'] = $request->user()->id;
        $input['on_post']   = $request->input('on_post');
        $input['body']      = $request->input('body');
        $slug               = $request->input('slug');
        Comments::create($input);

        return redirect($slug)->with('message', 'Comment published');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * delete comment by ronak
     */
    public function dlt($id)
    {
		$comment     = comments::find($id);
        $postid      = $comment->on_post;
        $slug        = posts::find($postid);
        $slugcontent = $slug->slug;
        $comment->delete();
        return redirect('/posts'.$slugcontent);
    }
}
