<?php

use App\Http\Controllers\BarangController;

Route::get('/', function () {
    return redirect()->route('barang.index');
});

Route::resource('barang', BarangController::class);

