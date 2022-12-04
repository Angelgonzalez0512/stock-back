<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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

Route::get("clear",function(){

    //clear cache
    Artisan::call('cache:clear');
    //clear config
    Artisan::call('config:clear');
    //clear route
    Artisan::call('route:clear');
    //clear view
    Artisan::call('view:clear');
});
