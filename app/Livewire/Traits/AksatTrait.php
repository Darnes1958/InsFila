<?php
namespace App\Livewire\Traits;

use App\Livewire\Forms\TransForm;
use App\Models\Fromexcel;
use App\Models\Main;
use App\Models\Overkst;
use App\Models\Tarkst;
use App\Models\Tran;

use App\Models\Wrongkst;
use DateTime;
use http\Header;
use Illuminate\Support\Facades\Auth;

trait AksatTrait {
    use MainTrait;


    public function Fill_From_Excel($main_id,$ksm,$ksm_date,$haf,$from_id)
    {

        $main=Main::find($main_id);

        if ($main->raseed<=0) {
            $this->StoreOver($main_id,$ksm_date,$ksm,$haf);
            Fromexcel::find($from_id)->update(['kst'=>$ksm]);
            $wtype='over';
        }

        if ($main->raseed>0){
            $over_id=0;
            if ($main->raseed<$ksm)
            {
                $over_id=$this->StoreOver($main_id,$ksm_date,$ksm-$main->raseed,$haf);
                $baky=$ksm-$main->raseed;
                $ksm=$main->raseed;
                Fromexcel::find($from_id)->update(['baky'=>$baky]);
                $wtype='half';
            } else $wtype='normal';

           $res= $this->StoreTran($main_id,$ksm_date,$ksm,$haf);
            Fromexcel::find($from_id)->update(['kst'=>$ksm]);
            if ($over_id!=0)
               Overkst::where('id',$over_id)->update(['tran_id'=>$res->id]);

        }

      return $wtype;
    }
    public function StoreTran($main_id,$ksm_date,$ksm,$haf)
    {
        $res= Tran::create([
            'main_id'=>$main_id,
            'ksm'=>$ksm,
            'ksm_type_id'=>2,
            'ksm_date'=>$ksm_date,
            'user_id'=>Auth::id(),
            'ser'=>Tran::where('main_id',$main_id)->max('ser')+1,
            'kst_date'=>$this->getKst_date($main_id),
            'haf_id'=>$haf,
        ]);
        $this->MainTarseed($main_id);
        return $res;
    }
    public static function StoreTran2($main_id,$ksm_date,$ksm,$haf)
    {
        $res= Tran::create([
            'main_id'=>$main_id,
            'ksm'=>$ksm,
            'ksm_type_id'=>2,
            'ksm_date'=>$ksm_date,
            'user_id'=>Auth::id(),
            'ser'=>Tran::where('main_id',$main_id)->max('ser')+1,
            'kst_date'=>self::getKst_date2($main_id),
            'haf_id'=>$haf,
        ]);
        self::MainTarseed2($main_id);
        return $res;
    }
    public function StoreWrong($taj,$acc,$name,$date,$ksm,$haf){
        Wrongkst::create([
            'taj_id'=>$taj,
            'acc'=>$acc,
            'name'=>$name,
            'wrong_date'=>$date,
            'kst'=>$ksm,
            'user_id'=>Auth::id(),
            'haf_id'=>$haf,
        ]);
    }
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
    public function StoreOver($main_id,$ksm_date,$ksm,$haf=0){
        $over=Overkst::create([
            'main_id'=>$main_id,
            'over_date'=>$ksm_date,
            'kst'=>$ksm,
            'haf_id'=>$haf,
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
    public static function setMonth2($begin){
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
    public static function getKst_date2($main_id){
        $res=Tran::where('main_id',$main_id)->get();
        if (count($res)>0) {
            $date=$res->max('kst_date');
            $date= date('Y-m-d', strtotime($date . "+1 month"));
            return $date;
        } else
        {
            $begin=Main::find($main_id)->sul_begin;

            return self::setMonth2($begin);

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
    public static function SortTrans2($main_id){
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
