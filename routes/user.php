<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User;

Route::post('/signup', [User::class, 'signup']);
Route::post('/verifyAccount', [User::class, 'verify_account']);
Route::get('/verifyAccount/{token}', [User::class, 'verify_account_via_url']);

Route::post('/login',[User::class,'login'])->middleware('login');
Route::post('/logout',[User::class,'logout'])->middleware('jwt_auth');

Route::post('/forgotPassToken',[User::class, 'forgot_pass_token'])->middleware('forgot_pass_token');
Route::post('/verifyForgotPassToken',[User::class, 'verify_forgot_pass_token']);
Route::post('/resetPassword',[User::class, 'reset_pass'])->middleware('reset_password');

Route::get('/viewProfile',[User::class, 'view_profile'])->middleware('jwt_auth');