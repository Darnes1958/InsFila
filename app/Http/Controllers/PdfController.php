<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Customer;
use App\Models\Main;
use App\Models\OurCompany;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PdfController extends Controller
{
  function PdfBankSum(Request $request){

    $RepDate=date('Y-m-d');
    $cus=OurCompany::where('Company',Auth::user()->company)->first();

    $res=Main::all();



    $html = view('PrnView.pdf-bank-sum',
      ['RepTable'=>$res,'cus'=>$cus,'RepDate'=>$RepDate])->toArabicHTML();

    $pdf = PDF::loadHTML($html)->output();

    $headers = array(
      "Content-type" => "application/pdf",
    );

// Create a stream response as a file download
    return response()->streamDownload(
      fn () => print($pdf), // add the content to the stream
      "invoice.pdf", // the name of the file/stream
      $headers
    );




  }
}
