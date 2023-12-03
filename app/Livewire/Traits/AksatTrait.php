<?php
namespace App\Livewire\Traits;

use App\Models\Main;
use App\Models\Overkst;
use App\Models\Tarkst;
use App\Models\Tran;

use DateTime;

trait AksatTrait {
    public function TarTarseed($main_id){
        $count=Tarkst::where('main_id',$main_id)->count();
        $sum=Tarkst::where('main_id',$main_id)->sum('kst');
        Main::where('id',$main_id)->update([
            'tar_count'=>$count,
            'tar_kst'=>$sum,
        ]);

    }
    public function OverTarseed($main_id){
        $count=Overkst::where('main_id',$main_id)->count();
        $sum=Overkst::where('main_id',$main_id)->sum('kst');
        Main::where('id',$main_id)->update([
            'over_count'=>$count,
            'over_kst'=>$sum,
        ]);

    }
    public function StoreOver($main_id,$ksm_date,$ksm){
        $over=Overkst::create([
            'main_id'=>$main_id,
            'over_date'=>$ksm_date,
            'kst'=>$ksm,
            ]);
        $this->OverTarseed($main_id);
        return $over->id;
    }
  public function setMonth($begin){
      $month = date('m', strtotime($begin));
      $year = date('Y', strtotime($begin));
      $date=$year.$month.'28';
      $date = DateTime::createFromFormat('Ymd',$date);
      $date=$date->format('Y-m-d');
      return $date;
  }
  public function getKst_date($main_id){
    $res=Tran::where('main_id',$main_id)->get();
    if (count($res)>0) {
      $date=$res->max('kst_date');
      $date= date('Y-m-d', strtotime($date . "+1 month"));
      return $date;
    } else
    {
      $begin=Main::find($main_id)->sul_begin;

      return $this->setMonth($begin);

    }
  }
  public function SortKstDate($main_id){
    $sul_begin=Main::find($main_id)->sul_begin;
    $day = date('d', strtotime($sul_begin));
    $month = date('m', strtotime($sul_begin));
    $year = date('Y', strtotime($sul_begin));
    $date=$year.$month.'28';
    $date = DateTime::createFromFormat('Ymd',$date);
    $date=$date->format('Y-m-d');

    $res=Tran::where('main_id',$main_id)->orderBy('ser','asc')->get();
    foreach ($res as $item) {
      Tran::where('id', $item->id)->update([
        'kst_date' => $date,
      ]);
      $date = date('Y-m-d', strtotime($date . "+1 month"));

    }
  }
  public function SortTrans($main_id){
    $res=Tran::where('main_id',$main_id)->get();
    $ser=1;
    foreach ($res as $item) {
      Tran::where('id', $item->id)->update([
        'ser' => $ser,
      ]);
      $ser++;
    }
  }

}
