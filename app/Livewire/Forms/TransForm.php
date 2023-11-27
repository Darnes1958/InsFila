<?php

namespace App\Livewire\Forms;

use App\Livewire\Traits\AksatTrait;
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

    public $user_id = '';

    public function SetTrans(Tran $tran){
      $this->main_id=$tran->main_id;
      $this->ser=$tran->ser;
      $this->ksm=$tran->ksm;
      $this->ksm_date=$tran->ksm_date;
      $this->kst_date=$tran->kst_date;
      $this->ksm_type_id=$tran->ksm_type_id;
      $this->ksm_notes=$tran->ksm_notes;
      $this->user_id=Auth::user()->id;

    }
    public function FillTrans($main_id){
        $this->main_id=$main_id;
        $this->ser=Tran::where('main_id',$main_id)->max('ser')+1;
        $this->kst_date=$this->getKst_date($main_id);
        $this->user_id=Auth::user()->id;
    }
    public function TransDelete($id){
      Trans::where('id',$id)->delete();
      $this->SortTrans($this->main_id);
      $this->SortKstDate($this->main_id);

    }
}
