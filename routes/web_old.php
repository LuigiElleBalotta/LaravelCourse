<?php

use Illuminate\Support\Facades\Route;
use \App\Models\{User, Album};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Route::get('/', function () {
//     return view('welcome');
// });

// La ROOT (e altre rotte) possiamo specificarle usando il namespace\NOME_CONTROLLER@nome_metodo
// Route::get('/', 'App\Http\Controllers\HomeController@index');

// Se non volessimo specificare il namespace: 
use App\Http\Controllers\{HomeController, WelcomeController, AlbumsController, PhotosController};
use Illuminate\Support\Facades\DB;

Route::get('/', [HomeController::class,'index']);  


// Rotta parametrizzata - le variabili non devono per forza avere lo stesso nome (ma è una convenzione), basta che siano ordinati
// Se metto "?" dopo il nome del parametro allora diventa opzionale
// Where serve per dire che, in questo caso, i parametri devono essere STRINGHE e non NUMERI
// Tuttavia, le rotte non dovrebbero avere logiche particolari, ci dovrebbero pensare i controller.
Route::get('/greet/{name?}/{lastname?}/{age?}', [WelcomeController::class, 'greet'])->where([
    'name' => '[A-Za-z]+', 
    'lastname' => '[A-Za-z]+',
    'age' => '[0-9]{1,3}' // Da 1 a 3 caratteri, solo numeri
]);

Route::get('/users', function () {
    // Returns a view
    // return view('users');

    // Returns an array
    // return ['John', 'David'];


    // Returns an array of objects
    // $users = [];

    // foreach( range(0, 10) as $index) {
    //     $user = new stdClass();
    //     $user->name = 'John '.$index;
    //     $user->lastName = 'Doe '.$index;
    //     $users[] = $user;
    // }

    // return $users;

    return User::with('albums')->get();

    // Se vogliamo paginare i risultati:
    // return User::paginate(5);
});

// Route::get('/albums', function () {
//     return Album::with('photos')->paginate(5);
// });
// ALTRIMENTI
// Route::get('/albums', [AlbumsController::class, 'index']);
// ALTRIMENTI
Route::resource('albums', AlbumsController::class);
// Route::get('/albums/{album}/delete', [AlbumsController::class, 'delete']);
Route::delete('/albums/{album}', [AlbumsController::class, 'delete']);
Route::get('/albums/{album}', [AlbumsController::class, 'show']);
Route::get('/albums/{album}/images', [AlbumsController::class, 'getImages'])->name('albums.images');

Route::get('/usersnoalbums', function() {
    $usersnoalbum = DB::table('users as u')->leftJoin('albums as a', 'u.id', '=', 'a.user_id')
        ->select('u.id', 'email', 'name', 'album_name')
        ->whereNull('album_name')
        ->whereRaw('album_name is NULL')->get();

    return $usersnoalbum;
});

// Images 
Route::resource('photos', PhotosController::class);