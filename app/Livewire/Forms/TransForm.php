<?php

namespace App\Livewire\Forms;

use App\Livewire\Traits\AksatTrait;
use App\Models\Main;
use App\Models\Overkst;
use App\Models\Tran;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Form;

class TransForm extends Form
{
use AksatTrait;
    #[Rule('required')]
    public $main_id = '';

    public $ser = '';

    #[Rule('required')]
    public $ksm = '';

    #[Rule('required|date')]
    public $ksm_date = '';

    public $kst_date ='';


    #[Rule('required')]
    public $ksm_type_id = 2;

    public $ksm_notes = '';
    public $haf_id=0;
    public $over_id=0;
    public $baky=0;


    public $user_id = '';

    public function SetTrans(Tran $tran){
      $this->main_id=$tran->main_id;
      $this->ser=$tran->ser;
      $this->ksm=$tran->ksm;
      $this->ksm_date=$tran->ksm_date;
      $this->kst_date=$tran->kst_date;
      $this->ksm_type_id=$tran->ksm_type_id;
      $this->ksm_notes=$tran->ksm_notes;
      $this->haf_id=$tran->haf_id;
      $this->over_id=$tran->over_id;
      $this->baky=$tran->baky;
      $this->user_id=Auth::user()->id;
    }
    public function FillTrans($main_id){
        $this->main_id=$main_id;
        $this->ser=Tran::where('main_id',$main_id)->max('ser')+1;
        $this->kst_date=$this->getKst_date($main_id);
        $this->user_id=Auth::user()->id;
    }
    public function TransDelete($id){
      Tran::where('id',$id)->delete();
      $this->SortTrans($this->main_id);
      $this->SortKstDate($this->main_id);
    }
    public function DoOver($main_id){
      Overkst::create([
        'main_id'=>$main_id,
        'over_date'=>$this->ksm_date,
        'kst'=>$this->ksm,
        'status'=>'غير مرجع',]);
      $count=Overkst::where('main_id',$main_id)->count();
      $sum=Overkst::where('main_id',$main_id)->sum('kst');
      Main::where('id',$main_id)->update([
        'over_count'=>$count,
        'over_kst'=>$sum,
      ]);
    }
}
