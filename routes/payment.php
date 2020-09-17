<?php

use Illuminate\Support\Facades\Route;
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS, post, get');
header("Access-Control-Max-Age", "3600");
header('Access-Control-Allow-Headers: *');
Route::post('paymob/processed/callback', ['uses' => 'CartController@processedCallback', 'as' => 'cart.paymob.processedCallback']);
Route::get('paymob/response/callback', ['uses' => 'CartController@responseCallback', 'as' => 'cart.paymob.responseCallback']);
