<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')
    ->get('/user', function (Request $request) {
    return $request->user();
});


Route::controller('UserController')->group(function(){
        Route::post('/register', 'register');
        Route::post('/login', 'login');
});

Route::controller('TagController')->prefix('tags')
    ->middleware('auth:sanctum')->group(function(){
    Route::get('/', 'index');
    Route::post('/store', 'store');

    Route::put('/{tag:id}/update', 'update')->missing( function (){
        return response()->json('Not Found!');
    });

    Route::delete('/{tag:id}/delete', 'destroy')->missing( function (){
        return response()->json('Not Found!');
    });
});

Route::controller('PostController')->prefix('posts')
    ->middleware('auth:sanctum')->group(function(){
    Route::get('/', 'index');
    Route::post('/store', 'store');
    Route::get('/{post}/show', 'show');
    Route::put('/{post}/update', 'update');
    Route::delete('/{post}/delete', 'destroy');
    Route::get('/deleted', 'viewDeleted');
    Route::post('/{post}/restore', 'restore');
});

Route::get('/stats', [UserController::class, 'stats']);
