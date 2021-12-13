<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User;

Route::post('/signup', [User::class, 'signup']);
Route::post('/verifyAccount', [User::class, 'verify_account']);
Route::get('/verifyAccount/{token}', [User::class, 'verify_account_via_url']);
Route::post('/login',[User::class,'login'])->middleware('login');
