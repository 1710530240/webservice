<?php

use App\Http\Controllers\wisataController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
if(!session_id()) session_start();

Route::resource(['user' => 'usersController', 'ulasan' => 'ulasanController', 'wisata' =>' wisataController']);
Route::get('user/login', ['uses' => 'usersController@login']);
Route::get('user/logout', ['uses' => 'usersController@logout']);
