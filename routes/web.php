<?php

    use Illuminate\Support\Facades\Route;

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

    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/connection_counts', [App\Http\Controllers\ConnectionController::class, 'connectionCounts']);
    Route::get('/connections/sent_req', [App\Http\Controllers\ConnectionController::class, 'sentRequest']);
    Route::get('/connections/received_req', [App\Http\Controllers\ConnectionController::class, 'receivedRequest']);
    Route::get('/connections/confirmed', [App\Http\Controllers\ConnectionController::class, 'confirmedConnections']);

    Route::resource('connections', App\Http\Controllers\ConnectionController::class);
