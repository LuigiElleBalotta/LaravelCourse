<?php

use App\Http\Controllers\{PageController};
use Illuminate\Support\Facades\Request;

Route::get('/about', [PageController::class, 'about']);
Route::get('/blog', [PageController::class, 'blog']);
Route::get('/staff', [PageController::class, 'staff']);

// /pages/staff2?name=Luigi
Route::view('/staff2', 'staff2', [
    'title' => 'Our Staff',
    'name' => Request::input('name')
]);