<?php

use Illuminate\Support\Facades\Route;
use Webkul\CustomInpostPaczkomatyShipping\Http\Controllers\InpostPaczkomatyController;

Route::group(['middleware' => ['web']], function () {
    Route::post('inpost/save-locker', [InpostPaczkomatyController::class, 'store'])
        ->name('inpost.save_locker');
});