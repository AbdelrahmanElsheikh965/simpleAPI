<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'status' => 200,
            'data'  => [
                'Tag_Name'   => Tag::paginate(10)
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = validator()->make($request->all(), ['name' => 'required|unique:tags']);

        if ($validation->fails())
            return $validation->errors();

        Tag::create(['name' => $request->name]);

        return response()->json([
            'status' => 200,
            'message'  => 'Done, Created'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        $request->validate(['name' => 'required|unique:tags']);
        $tag->update(['name' => $request->name]);
        return response()->json(['status' => 200, 'message'  => 'Done, Updated']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();
        return response()->json(['status' => 200, 'message'  => 'Done, Deleted']);
    }
}
