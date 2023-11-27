<?php
namespace App\Livewire\Traits;

use App\Models\Main;
use App\Models\Tran;

use DateTime;

trait AksatTrait {
  public function getKst_date($main_id){
    $res=Tran::where('main_id',$main_id)->get();
    if (count($res)>0) {
      $date=$res->max('kst_date');
      $date= date('Y-m-d', strtotime($date . "+1 month"));
      return $date;
    } else
    {
      $begin=Main::find($main_id)->sul_begin;
      $month = date('m', strtotime($begin));
      $year = date('Y', strtotime($begin));
      $date=$year.$month.'28';
      $date = DateTime::createFromFormat('Ymd',$date);
      $date=$date->format('Y-m-d');

      return $date;

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
