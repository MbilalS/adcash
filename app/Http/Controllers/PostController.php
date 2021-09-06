<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::with('category')->get();

        return $posts;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreatePostRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePostRequest $request)
    {
        $input = $request->all();

        $postTitle = $input['title'];
        if($input['category'] != null) {
            $categoryid = $input['category'][0]['id'];
        } else {
            $categoryid = null;
        }
        
        $newPost = Post::create([
            'title' => $postTitle,
            'category_id' => $categoryid
        ]);

        return $newPost;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdatePostRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request)
    {
        $input = $request->all();

        if($input['category'] != null) {
            $categoryid = $input['category'][0]['id'];
        } else {
            $categoryid = null;
        }

        Post::where('id', $input['id'])
        ->update([
            'title' => $input['title'],
            'category_id' => $categoryid
        ]);

        return $categoryid;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);

        if (empty($post)) {
            return $this->sendError('Post not found');
        }

        $post->delete();

        return $post['id'];
    }
}
