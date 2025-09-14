<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailsController;

Route::prefix('emails')->group(function(){
    Route::get("/",[EmailsController::class,"index"]);
    Route::post("/",[EmailsController::class,"store"]);
    Route::get("/{id}",[EmailsController::class,"show"]);
    Route::put("/{id}",[EmailsController::class,"update"]);
    Route::delete("/{id}",[EmailsController::class,"destroy"]);
});