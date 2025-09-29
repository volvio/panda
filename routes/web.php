<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OlxController;
use App\Http\Controllers\OlxSubscriptionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/olxid',  [OlxController::class, 'getId'])->name('olx.getid');

Route::get('/olx/subscribe', [OlxSubscriptionController::class, 'showForm'])->name('olx.subscribe');
Route::post('/olx/subscribe', [OlxSubscriptionController::class, 'subscribe'])->name('olx.subscribe.post');
Route::get('/olx/confirm/{token}', [OlxSubscriptionController::class, 'confirm'])->name('olx.confirm');



