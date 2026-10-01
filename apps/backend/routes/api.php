<?php

use Illuminate\Support\Facades\Route;

Route::get("/", function () {
    return response()->json([
        "hello" => "world",
    ]);
});

Route::get("/something", function () {
    return response()->json([
        "hello" => "world",
    ]);
});
