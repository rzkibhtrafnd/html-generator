<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HtmlGeneratorController;

Route::get('/', [HtmlGeneratorController::class, 'index'])->name('home');
Route::post('/generate', [HtmlGeneratorController::class, 'generate'])->name('generate');
Route::get('/history', [HtmlGeneratorController::class, 'history'])->name('history');
Route::get('/history/{id}/download-excel', [HtmlGeneratorController::class, 'downloadExcel'])->name('history.download.excel');
Route::get('/history/{id}/edit', [HtmlGeneratorController::class, 'edit'])->name('history.edit');
Route::put('/history/{id}', [HtmlGeneratorController::class, 'update'])->name('history.update');
Route::delete('/history/{id}', [HtmlGeneratorController::class, 'destroy'])->name('history.destroy');
Route::get('/preview/{id}', [HtmlGeneratorController::class, 'preview'])->name('preview');
Route::get('/preview/{id}/{filename}', [HtmlGeneratorController::class, 'previewFile'])->name('preview.file');