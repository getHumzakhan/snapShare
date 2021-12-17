<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Post;

Route::post('/create',[Post::class,'create'])->middleware('jwt_auth');
Route::delete('/delete',[Post::class,'delete'])->middleware('delete_post');
Route::get('/view',[Post::class,'view'])->middleware('jwt_auth');
Route::get('/search',[Post::class,'search'])->middleware('search_post');