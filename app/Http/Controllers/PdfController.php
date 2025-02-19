<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Customer;
use App\Models\Main;
use App\Models\OurCompany;
use App\Models\Taj;
use App\Models\Tran;
use ArPHP\I18N\Arabic;
use Barryvdh\DomPDF\Facade\Pdf;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PdfController extends Controller
{
    function PdfBankSum(Request $request){

    $RepDate=date('Y-m-d');
    $cus=OurCompany::where('Company',Auth::user()->company)->first();
            $res=Taj::with('main')
                ->has('main')
                ->withCount('main as count')
                ->withSum('main as pay','pay')
                ->withSum('main as sul','sul')
                ->withSum('main as raseed','raseed')
                ->get();

        $reportHtml = view('PrnView.pdf-bank-sum',
            ['RepTable'=>$res,'cus'=>$cus,'RepDate'=>$RepDate])->render();
        $arabic = new Arabic();
        $p = $arabic->arIdentify($reportHtml);

        for ($i = count($p)-1; $i >= 0; $i-=2) {
            $utf8ar = $arabic->utf8Glyphs(substr($reportHtml, $p[$i-1], $p[$i] - $p[$i-1]));
            $reportHtml = substr_replace($reportHtml, $utf8ar, $p[$i-1], $p[$i] - $p[$i-1]);
        }

        $pdf = PDF::loadHTML($reportHtml);
        return $pdf->download('report.pdf');

  }
    function PdfNames(Request $request){
        $RepDate = date('Y-m-d');
        $cus = OurCompany::where('Company', Auth::user()->company)->first();
        $res = Main::where('taj_id', $request->bank_id)->get();
        $BankName=Taj::find($request->bank_id)->TajName;

        $reportHtml = view('PrnView.pdf-all',
            ['RepTable' => $res, 'cus' => $cus, 'RepDate' => $RepDate,'BankName'=>$BankName])->render();
        $arabic = new Arabic();
        $p = $arabic->arIdentify($reportHtml);

        for ($i = count($p)-1; $i >= 0; $i-=2) {
            $utf8ar = $arabic->utf8Glyphs(substr($reportHtml, $p[$i-1], $p[$i] - $p[$i-1]));
            $reportHtml = substr_replace($reportHtml, $utf8ar, $p[$i-1], $p[$i] - $p[$i-1]);
        }

        $pdf = PDF::loadHTML($reportHtml);
        return $pdf->download('report.pdf');

    }
    function PdfMosdadaBank(Request $request)
    {
        $RepDate = date('Y-m-d');
        $cus = OurCompany::where('Company', Auth::user()->company)->first();

          $res = Main::where('raseed', '<=', $request->Baky)
            ->where('taj_id', $request->bank_id)->get();


         $BankName=Taj::find($request->bank_id)->TajName;

        $reportHtml = view('PrnView.pdf-mosdada',
            ['RepTable' => $res, 'cus' => $cus, 'RepDate' => $RepDate,'BankName'=>$BankName,])->render();
        $arabic = new Arabic();
        $p = $arabic->arIdentify($reportHtml);
        for ($i = count($p)-1; $i >= 0; $i-=2) {
            $utf8ar = $arabic->utf8Glyphs(substr($reportHtml, $p[$i-1], $p[$i] - $p[$i-1]));
            $reportHtml = substr_replace($reportHtml, $utf8ar, $p[$i-1], $p[$i] - $p[$i-1]);
        }
        $pdf = PDF::loadHTML($reportHtml);
        return $pdf->download('report.pdf');

    }
    function PdfNotMosdadaBank(Request $request)
    {
        $RepDate = date('Y-m-d');
        $cus = OurCompany::where('Company', Auth::user()->company)->first();

            $res = Main::where('pay', 0)
                ->where('taj_id', $request->bank_id)->get();

            $BankName=Taj::find($request->bank_id)->TajName;

        $reportHtml = view('PrnView.pdf-not-mosdada',
            ['RepTable' => $res, 'cus' => $cus, 'RepDate' => $RepDate,'BankName'=>$BankName,])->render();
        $arabic = new Arabic();
        $p = $arabic->arIdentify($reportHtml);
        for ($i = count($p)-1; $i >= 0; $i-=2) {
            $utf8ar = $arabic->utf8Glyphs(substr($reportHtml, $p[$i-1], $p[$i] - $p[$i-1]));
            $reportHtml = substr_replace($reportHtml, $utf8ar, $p[$i-1], $p[$i] - $p[$i-1]);
        }
        $pdf = PDF::loadHTML($reportHtml);
        return $pdf->download('report.pdf');
    }
    function PdfMotakraBank(Request $request)
    {
        $RepDate = date('Y-m-d');
        $cus = OurCompany::where('Company', Auth::user()->company)->first();

         $res = Main::where('Late', '>=', $request->Baky)
            ->where('taj_id', $request->bank_id)
            ->when($request->notPay,function ($q){
                $q->where('pay',0);
            }) ->get();

            $BankName=Taj::find($request->bank_id)->TajName;
        $reportHtml = view('PrnView.pdf-motakra',
            ['RepTable' => $res, 'cus' => $cus, 'RepDate' => $RepDate,'BankName'=>$BankName,])->render();
        $arabic = new Arabic();
        $p = $arabic->arIdentify($reportHtml);
        for ($i = count($p)-1; $i >= 0; $i-=2) {
            $utf8ar = $arabic->utf8Glyphs(substr($reportHtml, $p[$i-1], $p[$i] - $p[$i-1]));
            $reportHtml = substr_replace($reportHtml, $utf8ar, $p[$i-1], $p[$i] - $p[$i-1]);
        }
        $pdf = PDF::loadHTML($reportHtml);
        return $pdf->download('report.pdf');

    }
    function PdfMohasla(Request $request)
    {
        $RepDate = date('Y-m-d');
        $cus = OurCompany::where('Company', Auth::user()->company)->first();
        $res = Tran::whereBetween('ksm_date',[$request->Date1,$request->Date2])

                ->wherein('main_id',function ($q) use($request){
                    $q->select('id')->from('mains')->where('taj_id',$request->bank_id);
                })->get();
         $BankName=Taj::find($request->bank_id)->TajName;
        $reportHtml = view('PrnView.pdf-mohasla',
            ['RepTable' => $res, 'cus' => $cus, 'Date1' => $request->Date1, 'Date2' => $request->Date2,'BankName'=>$BankName,])->render();
        $arabic = new Arabic();
        $p = $arabic->arIdentify($reportHtml);
        for ($i = count($p)-1; $i >= 0; $i-=2) {
            $utf8ar = $arabic->utf8Glyphs(substr($reportHtml, $p[$i-1], $p[$i] - $p[$i-1]));
            $reportHtml = substr_replace($reportHtml, $utf8ar, $p[$i-1], $p[$i] - $p[$i-1]);
        }
        $pdf = PDF::loadHTML($reportHtml);
        return $pdf->download('report.pdf');

    }
    function PdfNotMohasla(Request $request)
    {
        $RepDate = date('Y-m-d');
        $cus = OurCompany::where('Company', Auth::user()->company)->first();


            $res= Main::where('taj_id',$request->bank_id)
                ->whereNotin('id',function ($q) use ($request) {
                    $q->select('main_id')->from('trans')->whereBetween('ksm_date',[$request->Date1,$request->Date2]);
                    })
                ->get();

         $BankName=Taj::find($request->bank_id)->TajName;
        $reportHtml = view('PrnView.pdf-not-mohasla',
            ['RepTable' => $res, 'cus' => $cus, 'Date1' => $request->Date1, 'Date2' => $request->Date2,'BankName'=>$BankName,])->render();
        $arabic = new Arabic();
        $p = $arabic->arIdentify($reportHtml);
        for ($i = count($p)-1; $i >= 0; $i-=2) {
            $utf8ar = $arabic->utf8Glyphs(substr($reportHtml, $p[$i-1], $p[$i] - $p[$i-1]));
            $reportHtml = substr_replace($reportHtml, $utf8ar, $p[$i-1], $p[$i] - $p[$i-1]);
        }
        $pdf = PDF::loadHTML($reportHtml);
        return $pdf->download('report.pdf');

    }

    function PdfStopALl(Request $request)
  {
    $RepDate = date('Y-m-d');
    $cus = OurCompany::where('Company', Auth::user()->company)->first();

    if ($request->By==1)
      $res=Main::with('Stop')->where('taj_id',$request->bank_id)
        ->has('Stop')
        ->whereHas('stop',function ($q) use($request){
         $q->whereBetween('stop_date',[$request->Date1,$request->Date2]);
        })
        ->get();

     {$BankName=Taj::find($request->bank_id)->TajName;$taj=$request->bank_id;}
    $TajAcc=Taj::find($taj)->TajAcc;
      $reportHtml = view('PrnView.pdf-stop',
          ['RepTable' => $res, 'cus' => $cus, 'TajAcc' => $TajAcc,'BankName'=>$BankName,])->render();
      $arabic = new Arabic();
      $p = $arabic->arIdentify($reportHtml);
      for ($i = count($p)-1; $i >= 0; $i-=2) {
          $utf8ar = $arabic->utf8Glyphs(substr($reportHtml, $p[$i-1], $p[$i] - $p[$i-1]));
          $reportHtml = substr_replace($reportHtml, $utf8ar, $p[$i-1], $p[$i] - $p[$i-1]);
      }
      $pdf = PDF::loadHTML($reportHtml);
      return $pdf->download('report.pdf');



  }
  function PdfStopOne($id)
  {
    $RepDate = date('Y-m-d');
    $cus = OurCompany::where('Company', Auth::user()->company)->first();


   $record=Main::find($id);



   $taj=Taj::find(Bank::find($record->bank_id)->taj_id);

   $BankName=$taj->TajName;
   $TajAcc=$taj->TajAcc;
      $reportHtml = view('PrnView.pdf-stop-one',
          ['record' => $record, 'cus' => $cus, 'TajAcc' => $TajAcc,'BankName'=>$BankName,])->render();
      $arabic = new Arabic();
      $p = $arabic->arIdentify($reportHtml);
      for ($i = count($p)-1; $i >= 0; $i-=2) {
          $utf8ar = $arabic->utf8Glyphs(substr($reportHtml, $p[$i-1], $p[$i] - $p[$i-1]));
          $reportHtml = substr_replace($reportHtml, $utf8ar, $p[$i-1], $p[$i] - $p[$i-1]);
      }
      $pdf = PDF::loadHTML($reportHtml);
      return $pdf->download('report.pdf');

  }

  function PdfMainCont($id){

    $RepDate = date('Y-m-d');
    $cus = OurCompany::where('Company', Auth::user()->company)->first();

    $res=Main::find($id);

    $item_name='';

    $mindate=$res->sul_begin;
    $mdate=Carbon::parse($mindate) ;
    $mmdate=$mdate->month.'-'.$mdate->year;

    $maxdate=$res->sul_end;
    $xdate=Carbon::parse($maxdate) ;
    $xxdate=$xdate->month.'-'.$xdate->year;

    $taj=Taj::find(Bank::find($res->bank_id)->taj_id);

    $BankName=$taj->TajName;
    $TajAcc=$taj->TajAcc;

      $reportHtml = view('PrnView.pdf-main-cont',
          ['res' => $res, 'cus' => $cus, 'TajAcc' => $TajAcc,'BankName'=>$BankName,'mindate'=>$mmdate,'maxdate'=>$xxdate,])->render();
      $arabic = new Arabic();
      $p = $arabic->arIdentify($reportHtml);
      for ($i = count($p)-1; $i >= 0; $i-=2) {
          $utf8ar = $arabic->utf8Glyphs(substr($reportHtml, $p[$i-1], $p[$i] - $p[$i-1]));
          $reportHtml = substr_replace($reportHtml, $utf8ar, $p[$i-1], $p[$i] - $p[$i-1]);
      }
      $pdf = PDF::loadHTML($reportHtml);
      return $pdf->download('report.pdf');


  }
  function PdfMain($id){

    $RepDate = date('Y-m-d');
    $cus = OurCompany::where('Company', Auth::user()->company)->first();

    $res=Main::find($id);
    $res2=Tran::where('main_id',$id)->get();

      $reportHtml = view('PrnView.pdf-main',
          ['res' => $res, 'cus' => $cus, 'res2' => $res2,])->render();
      $arabic = new Arabic();
      $p = $arabic->arIdentify($reportHtml);
      for ($i = count($p)-1; $i >= 0; $i-=2) {
          $utf8ar = $arabic->utf8Glyphs(substr($reportHtml, $p[$i-1], $p[$i] - $p[$i-1]));
          $reportHtml = substr_replace($reportHtml, $utf8ar, $p[$i-1], $p[$i] - $p[$i-1]);
      }
      $pdf = PDF::loadHTML($reportHtml);
      return $pdf->download('report.pdf');


  }

}
