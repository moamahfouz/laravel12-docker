<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/node-data', function () {
    try {
        $response = Http::get('http://node:3000');
        return response()->json($response->json());
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});
