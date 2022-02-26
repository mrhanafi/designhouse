<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerifyEmailController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route group for authenticated user only 
Route::group(['middleware' => ['auth:api']], function(){
    
});

// public route

// Route for guest 
Route::group(['middleware' => ['guest:api']], function(){
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('verification/verify/{user}', [VerifyEmailController::class, 'verify'])->name('verification.verify');
    Route::post('verification/resend', [VerifyEmailController::class, 'resend'])->name('verification.resend');
    // Route::post('verification/verify', [VerifyEmailController::class, '__invoke'])->middleware(['signed', 'throttle:6,1'])
    // ->name('verification.verify');
    // Route::post('verification/resend', function (Request $request) {
    //     $request->user()->sendEmailVerificationNotification();
    //     return back()->with('message', 'Verification link sent!');
    // })->middleware(['auth:api', 'throttle:6,1'])->name('verification.send');
});

// Route::get('/', function(){
//     return response()->json(['message' => 'Hello world'],200);
// });
