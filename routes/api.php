<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PassportAuthController;

Route::post('/register', [PassportAuthController::class, 'register']);
Route::post('/login', [PassportAuthController::class, 'login']);


//Protected Routesphp
Route::middleware('auth:api')->group(function () {
    Route::get('/profile',[PassportAuthController::class, 'profile']);
    Route::get('/logout',[PassportAuthController::class, 'logout']);
});
