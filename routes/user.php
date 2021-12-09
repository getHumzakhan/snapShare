<?php

use Illuminate\Support\Facades\Route;

Route::post('/signup', [User::class, 'signup']);
