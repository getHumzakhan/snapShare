<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User;

Route::post('/signup', [User::class, 'signup']);
