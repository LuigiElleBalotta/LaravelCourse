<?php

use \App\Http\Controllers\Admin\{AdminUsersController};

Route::resource('users', AdminUsersController::class);

Route::view('/', 'templates/admin')->name('admin.home');

Route::get('getUsers', [AdminUsersController::class, 'getUsers'])->name('admin.getUsers');
Route::patch('restore/{user}', [AdminUsersController::class, 'restore'])->name('admin.userrestore');

Route::get('dashboard', function () {
    return 'Admin Dashboard';
});
