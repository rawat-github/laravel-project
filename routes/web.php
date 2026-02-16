<?php

use App\Http\Controllers\AddressBookController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/addressbook', [AddressBookController::class, 'index']);
Route::get('/add-contact', [AddressBookController::class, 'create']);
Route::post('/store-contact', [AddressBookController::class, 'store']);
Route::get('/edit-contact/{id}', [AddressBookController::class, 'edit']);
Route::put('/update-contact/{id}', [AddressBookController::class, 'update']);
Route::get('/delete-contact/{id}', [AddressBookController::class, 'destroy']);
