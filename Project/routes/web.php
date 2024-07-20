<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\TransaksiController;

Route::get('/', function () {
    return view('welcome');
});
Route::resource('barang', BarangController::class);
Route::resource('transaksi', TransaksiController::class);

