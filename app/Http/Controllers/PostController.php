<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Error;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = auth()->user()->posts()->orderBy('pinned', 'desc')->paginate(5);

        return response()->json([
            'status' => 200,
            'posts'  => $posts
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' =>  'required|max:255',  'cover_image' => 'required',
            'body'  =>  'required|string',   'pinned'      => 'required|boolean'
        ]);

        auth()->user()->posts()->create($validated);
        return response()->json(['status' => 200, 'message'  => 'Done, Stored']);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return response(Post::find($id)
            ? ['status' => 200,'post' => Post::find($id)]
            : ['status' => 404,'message' => 'Not Found!']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' =>  'required|max:255',
            'body'  =>  'required|string',
            'cover_image'  =>  Rule::excludeIf( fn() => $request->cover_image === NULL ),
            'pinned'      => 'required|boolean'
        ]);

        $thisUserPost = auth()->user()->posts()->where('id', $post->id);

        // Checking if the post is there but doesn't belong to this authenticated user.
        if (empty($thisUserPost->get()->toArray())) {
            return response()->json(['status' => 422, 'message'  => 'Error, Something wrong happened!']);
        }

        $thisUserPost->update($validated);
        return response()->json(['status' => 200, 'message' => 'Done, Updated']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        auth()->user()->posts()->where('id', $post->id)->delete();
        return response()->json(['status' => 200, 'message'  => 'Done, Deleted']);
    }

    /**
     *  View the deleted posts
     */
    public function viewDeleted()
    {
        $posts = auth()->user()->posts()->onlyTrashed()->paginate(5);
        return response()->json(['status' => 200, 'posts'  => $posts]);
    }

    /**
     *  Restore one of the deleted posts
     */
    public function restore($id)
    {
        try {
            auth()->user()->posts()->onlyTrashed()->find($id)->restore();
            return response()->json([ 'status' => 200, 'message'  => 'Post restored successfully' ]);
        }catch (Error){
            return response()->json([ 'status' => 404, 'message'  => 'Not Deleted or Not Found!' ]);
        }
    }
}
