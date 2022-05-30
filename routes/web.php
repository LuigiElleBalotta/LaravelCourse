<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AlbumsController, GalleryController, PhotosController, CategoryController};

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->prefix('dashboard')->group(function () {

    Route::resource('/albums', AlbumsController::class)->middleware('auth');
    Route::get('/albums/{album}/images', [AlbumsController::class,'getImages'])->name('albums.images')
        ->middleware('can:view,album');
    Route::resource('photos', PhotosController::class);
    Route::resource('categories', CategoryController::class);
});



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Gallery
Route::group(['prefix' => 'gallery'], function() {
    Route::get('/', [GalleryController::class, 'index'])->name('gallery.index');
    Route::get('albums', [GalleryController::class, 'index']);
    Route::get('album/{album}/images', [GalleryController::class, 'showAlbumImages'])->name('gallery.album.images');

    Route::get('categories/{category}/albums', [GalleryController::class, 'showCategoryAlbums'])->name('gallery.albums.categories');
});

Route::resource('categories', CategoryController::class);

// Route::view('testMail', 'mails.testemail', ['username' => 'Luigi']);
Route::get('testMail', function () {
    \Mail::to('luigi@thebbsway.com')->send(new \App\Mail\TestMd(Auth::user()));
});

Route::get('testEvent', function () {
    $album = \App\Models\Album::first();

    event(new \App\Events\NewAlbumCreated($album));
});

require __DIR__.'/auth.php';
