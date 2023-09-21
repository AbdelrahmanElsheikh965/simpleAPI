<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'         =>  'required',
            'phone_number' =>  'required|min:11|unique:users',
            'password'     =>  'required|confirmed|min:8',
        ]);

        // Password is automatically hashed in User Model.
        $user = User::create($validated);
        $token = $user->createToken('token')->plainTextToken;

        return response()->json([
            'status' => 200,
            'token' => $token,
            'data'  => [
                'Name'   => $request->name,
                'Phone'  => $request->phone_number
            ]
        ]);
    }

    public function login(Request $request)
    {
        // Input validation is better than making requests to database server just to validate.
        $validated = $request->validate([
            'phone_number' =>  'required|min:11',
            'password'     =>  'required|min:8',
        ]);

        $user = User::where('phone_number', $request->phone_number)->first();

        if ($user && Hash::check($request->password, $user->password)) {

            $token = $user->createToken('token')->plainTextToken;
            return response()->json([
                'status' => 200,
                'token' => $token,
                'data' => [
                    'Name' => $user->name,
                    'Phone' => $request->phone_number
                ]
            ]);

        }

        else
            return response()->json(['status' => 422,'message' => 'Incorrect Data']);
    }

    public function stats()
    {
        $allUsers           = User::all()->count();
        $posts              = Post::withTrashed()->count();

        $usersWithNoPosts   = User::select('id')
            ->leftJoin('posts', 'users.id', '=', 'posts.user_id')
            ->whereRaw('users.id NOT IN (SELECT user_id FROM posts)')
            ->count();

        return response()->json([
            'status' => 200,
            'data' =>
                [
                    'Number of all users'           => $allUsers,
                    'Number of all posts'           => $posts,
                    'Number of users with 0 posts'  => $usersWithNoPosts
                ]
        ]);
    }
}
