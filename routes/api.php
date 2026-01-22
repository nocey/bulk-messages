<?php

use App\Http\Controllers\MessageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/sent-messages', [MessageController::class, 'index']);

Route::get('/message/{id}/cache', [MessageController::class, 'cachedMessages']);
