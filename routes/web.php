<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
     return redirect(route('filament.admin.auth.login'));

});

Route::get('/login', function () {
  return redirect(route('filament.admin.auth.login'));
})->name('login');

Route::controller(PdfController::class)->group(function (){
  route::get('/pdfbanksum/{By}', 'PdfBankSum')->name('pdfbanksum') ;
  route::get('/pdfmosdadabank/{Baky?}/{bank_id?}', 'PdfMosdadaBank')->name('pdfmosdadabank') ;
  route::get('/pdfmotakrabank/{Baky?}/{bank_id?}', 'PdfMotakraBank')->name('pdfmotakrabank') ;

});
