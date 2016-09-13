<?php

use App\Item;

use Illuminate\Support\Facades\Cache;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('c/items', function () {
    return response()->json(Item::c_all());
});

Route::get('c/items/orderedByName', function () {
    return response()->json(Item::c_orderedByName());
});

Route::get('c/items/orderedByDescription', function () {
    return response()->json(Item::c_orderedByDescription());
});

Route::get('items', function () {
    return response()->json(Item::all());
});

Route::get('items/orderedByName', function () {
    return response()->json(Item::orderBy("name")->get());
});

Route::get('items/orderedByDescription', function () {
    return response()->json(Item::orderBy("description")->get());
});

Route::get('/cache/{cacheName}/flush', function ($cacheName) {

    if(Cache::forget($cacheName)) {
        return response()->json([
            "code"    => "200",
            "status"  => "OK",
            "message" => "Cache {$cacheName} flushed!",
        ]);
    } else {
        return response()->json([
            "code"    => "404",
            "status"  => "NOT_FOUND",
            "message" => "Cache entry named {$cacheName} not found!",
        ]);
    }


});
