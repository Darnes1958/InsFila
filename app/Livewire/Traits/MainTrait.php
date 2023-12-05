<?php
namespace App\Livewire\Traits;

use App\Models\Main;
use App\Models\Tran;
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
}
