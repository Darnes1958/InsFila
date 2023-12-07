<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Customer;
use App\Models\Main;
use App\Models\OurCompany;
use App\Models\Taj;
use App\Models\Tran;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PdfController extends Controller
{
    function PdfBankSum(Request $request){

    $RepDate=date('Y-m-d');
    $cus=OurCompany::where('Company',Auth::user()->company)->first();

        if ($request->By=='1') {
            $res=Bank::with('main')
                ->has('main')
                ->withCount('main as count')
                ->withSum('main as pay','pay')
                ->withSum('main as sul','sul')
                ->withSum('main as raseed','raseed')
                ->get();
        }
        if ($request->By=='2'){
            $res=Taj::with('main')
                ->has('main')
                ->withCount('main as count')
                ->withSum('main as pay','pay')
                ->withSum('main as sul','sul')
                ->withSum('main as raseed','raseed')
                ->get();

        }



    $html = view('PrnView.pdf-bank-sum',
      ['RepTable'=>$res,'cus'=>$cus,'RepDate'=>$RepDate,'By'=>$request->By])->toArabicHTML();

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
    function PdfAll(Request $request)
    {
        $RepDate = date('Y-m-d');
        $cus = OurCompany::where('Company', Auth::user()->company)->first();
        if ($request->By==1)
            $res = Main::where('bank_id', $request->bank_id)->get();
        else
            $res=Main::whereIn('bank_id',function ($q) use($request){
                $q->select('id')->from('banks')->where('taj_id',$request->bank_id);})->get() ;
        if ($request->By==1)
             $BankName=Bank::find($request->bank_id)->BankName;
        else $BankName=Taj::find($request->bank_id)->TajName;
        $html = view('PrnView.pdf-all',
            ['RepTable' => $res, 'cus' => $cus, 'RepDate' => $RepDate,'BankName'=>$BankName,'By'=>$request->By])->toArabicHTML();
        $pdf = PDF::loadHTML($html)->output();
        $headers = array("Content-type" => "application/pdf",);
        return response()->streamDownload(fn () => print($pdf), "invoice.pdf", $headers );
    }
    function PdfMosdadaBank(Request $request)
    {
        $RepDate = date('Y-m-d');
        $cus = OurCompany::where('Company', Auth::user()->company)->first();
        if ($request->By==1)
          $res = Main::where('raseed', '<=', $request->Baky)
            ->where('bank_id', $request->bank_id)->get();
        else
           $res=Main::whereIn('bank_id',function ($q) use($request){
                $q->select('id')->from('banks')->where('taj_id',$request->bank_id);
            })
            ->where('raseed','<=',$request->Baky)
            ->get() ;

        if ($request->By==1)
         $BankName=Bank::find($request->bank_id)->BankName;
        else
         $BankName=Taj::find($request->bank_id)->TajName;

        $html = view('PrnView.pdf-mosdada',
            ['RepTable' => $res, 'cus' => $cus, 'RepDate' => $RepDate,'BankName'=>$BankName,'By'=>$request->By])->toArabicHTML();
        $pdf = PDF::loadHTML($html)->output();
        $headers = array(
            "Content-type" => "application/pdf",
        );
        return response()->streamDownload(fn () => print($pdf), "invoice.pdf", $headers );
    }
    function PdfMotakraBank(Request $request)
    {
        $RepDate = date('Y-m-d');
        $cus = OurCompany::where('Company', Auth::user()->company)->first();
        if ($request->By==1)
         $res = Main::where('Late', '>=', $request->Baky)
            ->where('bank_id', $request->bank_id)->get();
        else
         $res = Main::where('Late', '>=', $request->Baky)
                ->whereIn('bank_id',function ($q) use($request) {
                    $q->select('id')->from('banks')->where('taj_id', $request->bank_id);
                        })
               ->get();

        if ($request->By==1)
            $BankName=Bank::find($request->bank_id)->BankName;
        else
            $BankName=Taj::find($request->bank_id)->TajName;
        $html = view('PrnView.pdf-motakra',
            ['RepTable' => $res, 'cus' => $cus, 'RepDate' => $RepDate,'BankName'=>$BankName,'By'=>$request->By])->toArabicHTML();
        $pdf = PDF::loadHTML($html)->output();
        $headers = array( "Content-type" => "application/pdf", );
        return response()->streamDownload(fn () => print($pdf), "invoice.pdf", $headers );
    }
    function PdfMohasla(Request $request)
    {
        $RepDate = date('Y-m-d');
        $cus = OurCompany::where('Company', Auth::user()->company)->first();
        $res = Tran::whereBetween('ksm_date',[$request->Date1,$request->Date2])
            ->when($request->By==1,function ($query) use ($request){
                $query->wherein('main_id',function ($q) use($request){
                    $q->select('id')->from('mains')->where('bank_id',$request->bank_id);
                });
            })
            ->when($request->By==2,function ($query) use ($request){
                $query->wherein('main_id',function ($q) use ($request){
                    $q->select('id')->from('mains')->whereIn('bank_id',function ($qq) use($request){
                        $qq->select('id')->from('banks')->where('taj_id',$request->bank_id);
                    });
                });
            })->get();
        if ($request->By==1) $BankName=Bank::find($request->bank_id)->BankName;
        else $BankName=Taj::find($request->bank_id)->TajName;
        $html = view('PrnView.pdf-mohasla',
            ['RepTable' => $res, 'cus' => $cus, 'Date1' => $request->Date1, 'Date2' => $request->Date2,'BankName'=>$BankName,'By'=>$request->By])->toArabicHTML();
        $pdf = PDF::loadHTML($html)->output();
        $headers = array( "Content-type" => "application/pdf", );
        return response()->streamDownload(fn () => print($pdf), "invoice.pdf", $headers );
    }
    function PdfNotMohasla(Request $request)
    {
        $RepDate = date('Y-m-d');
        $cus = OurCompany::where('Company', Auth::user()->company)->first();

        if ($request->By==1)
            $res= Main::where('bank_id',$request->bank_id)
                ->whereNotin('id',function ($q) use ($request) {
                    $q->select('main_id')->from('trans')->whereBetween('ksm_date',[$request->Date1,$request->Date2]);
                    })
                ->get();
        if ($request->By==2)
            $res= Main::whereIn('bank_id',function ($q) use($request){
                        $q->select('id')->from('banks')->where('taj_id',$request->bank_id);
                        })
                ->whereNotin('id',function ($q) use($request){
                    $q->select('main_id')->from('trans')->whereBetween('ksm_date',[$request->Date1,$request->Date2]);
                    })
                ->get();


        if ($request->By==1) $BankName=Bank::find($request->bank_id)->BankName;
        else $BankName=Taj::find($request->bank_id)->TajName;
        $html = view('PrnView.pdf-not-mohasla',
            ['RepTable' => $res, 'cus' => $cus, 'Date1' => $request->Date1, 'Date2' => $request->Date2,'BankName'=>$BankName,'By'=>$request->By])->toArabicHTML();
        $pdf = PDF::loadHTML($html)->output();
        $headers = array( "Content-type" => "application/pdf", );
        return response()->streamDownload(fn () => print($pdf), "invoice.pdf", $headers );
    }

}
