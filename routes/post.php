<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Post;

Route::post('/create',[Post::class,'create'])->middleware('jwt_auth');
Route::delete('/delete',[Post::class,'delete'])->middleware('delete_post');
Route::get('/view',[Post::class,'view'])->middleware('jwt_auth');
Route::get('/search',[Post::class,'search'])->middleware('search_post');
Route::patch('/update/privacy',[Post::class,'update_privacy'])->middleware('update_privacy');
Route::get('/share',[Post::class,'share'])->middleware('share_post');
Route::post('/access/allow',[Post::class,'allow_access'])->middleware('allow_access');
Route::post('/access/remove',[Post::class,'remove_access'])->middleware('remove_access');

