<?php

use Illuminate\Support\Facades\Route;
use Rennokki\LaravelSnsEvents\Http\Controllers\SnsController;

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

Auth::routes([
    'register' => false,
    'reset' => false,
    'confirm' => false,
    'verify' => false,
]);

Route::post('/sns-endpoint', [SnsController::class, 'handle']);

Route::fallback(function () {
    return view('app');
});
