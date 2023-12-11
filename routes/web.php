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

  route::get('/pdfall/{bank_id?}/{By?}', 'PdfAll')->name('pdfall') ;

  route::get('/pdfmosdadabank/{Baky?}/{bank_id?}/{By?}', 'PdfMosdadaBank')->name('pdfmosdadabank') ;
  route::get('/pdfmotakrabank/{Baky?}/{bank_id?}/{By?}', 'PdfMotakraBank')->name('pdfmotakrabank') ;

  route::get('/pdfmohasla/{bank_id?}/{Date1?}/{Date2?}/{By?}', 'PdfMohasla')->name('pdfmohasla') ;
  route::get('/pdfnotmohasla/{bank_id?}/{Date1?}/{Date2?}/{By?}', 'PdfNotMohasla')->name('pdfnotmohasla') ;

  route::get('/pdfstopall/{bank_id?}/{Date1?}/{Date2?}/{By?}', 'PdfStopALL')->name('pdfstopall') ;
  route::get('/pdfstopone/{record}/', 'PdfStopOne')->name('pdfstopone') ;

  route::get('/pdfmaincont/{id}/', 'PdfMainCont')->name('pdfmaincont') ;
  route::get('/pdfmain/{id}/', 'PdfMain')->name('pdfmain') ;

});
