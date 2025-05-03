<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HtmlGeneratorController;

Route::get('/', [HtmlGeneratorController::class, 'index'])->name('home');
Route::post('/generate', [HtmlGeneratorController::class, 'generate'])->name('generate');
Route::get('/riwayat', [HtmlGeneratorController::class, 'history'])->name('history');
Route::get('/preview/{id}', [HtmlGeneratorController::class, 'preview'])->name('preview');
Route::get('/preview/{id}/{filename}', [HtmlGeneratorController::class, 'previewFile'])->name('preview.file');