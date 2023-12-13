<?php
namespace App\Livewire\Traits;

use App\Livewire\Forms\MainForm;
use App\Livewire\Forms\OverForm;
use App\Livewire\Forms\TransForm;
use App\Models\Main;
use App\Models\Main_arc;
use App\Models\Overkst;
use App\Models\Overkst_arc;
use App\Models\Tran;
use App\Models\Trans_arc;
use Carbon\Carbon;
use DateTime;

trait MainTrait {



  public function EndDate($date,$card_nocount){
      return $date = date('Y-m-d', strtotime($date . "+".$card_nocount." month"));
  }

  public function RetLate($main_id,$kst_count,$nextKst){
    $toDate = Carbon::parse($nextKst);
    $fromDate = Carbon::now();

    if ($fromDate>$toDate)
      $months = $toDate->diffInMonths($fromDate);
    else $months=0;

    $count=Tran::where('main_id',$main_id)->count();
    if ($months>($kst_count-$count)) $months=$kst_count-$count;

    return $months;

  }
  public function LateChk(){
    $Main=Main::where('LastUpd','<',now())->get();
    foreach ($Main as $main)
      Main::where('id',$main->id)->
      update([
        'LastUpd'=>now(),
        'Late'=>$this->RetLate($main->id,$main->kst_count,$main->NextKst),
      ]);
  }

  public function toArc($main_id,MainForm $TmainForm,TransForm $TtransForm,OverForm $ToverForm){
      $TmainForm->reset();
      $TtransForm->reset();
      $ToverForm->reset();
      $TmainForm->SetMain($main_id);
      Main_arc::create(
          $TmainForm->all()
      );

      $res=Tran::where('main_id',$main_id)->get();

      foreach ($res as $item){
          $TtransForm->SetTrans($item);
          $TtransForm->user_id=$item->user_id;

          Trans_arc::create(
              $TtransForm->all()
          );
      }
      $res=Overkst::where('main_id',$main_id)->get();
      foreach ($res as $item){
          $ToverForm->SetOver($item);
          $ToverForm->user_id=$item->user_id;
          Overkst_arc::create(
              $ToverForm->all()
          );
      }
      $main_id=Main::latest()->first()->id;
      Overkst::where('main_id',$main_id)->delete();
      Tran::where('main_id',$main_id)->delete();
      Main::where('id',$main_id)->delete();
  }
}
