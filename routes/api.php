<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\DeliveryRequestController;

Route::post('/send', [DeliveryController::class, 'sendPackage'])->name('package.send');
Route::post('/test', function() {
    return response()->json(['message' => 'I am the placeholder'], 200);
})->name('package.test');

//For the future API
// Route::middleware('auth:api')->group(function () {
//     Route::get('/deliveries', 'DeliveryRequestController@index')->name('deliveries.index');
//     Route::get('/deliveries/{id}', 'DeliveryRequestController@show')->name('deliveries.show');
//     Route::post('/deliveries', 'DeliveryRequestController@store')->name('deliveries.store');
//     Route::put('/deliveries/{id}', 'DeliveryRequestController@update')->name('deliveries.update');
//     Route::delete('/deliveries/{id}', 'DeliveryRequestController@destroy')->name('deliveries.destroy');
// });
