<?php

namespace App\Livewire\Forms;


use App\Models\Main;
use App\Models\Tran;
use Livewire\Attributes\Rule;
use Livewire\Form;
use App\Livewire\Traits\AksatTrait;

class MainView extends Form
{
  use AksatTrait;
    #[Rule('required')]

    public $id = '';

    #[Rule('required')]
    public $customer_id = '';

    #[Rule('required')]
    public $bank_id = '';

    #[Rule('required')]
    public $acc = '';

    #[Rule('required')]

    public $sul_begin = '';


    public $sul_end = '';

    #[Rule('required')]
    public $sul = '';

    #[Rule('required')]
    public $kst_count = '';

    #[Rule('required')]
    public $kst = '';

    #[Rule('required')]
    public $pay = 0;

    public $raseed = 0;
    public $notes = '';

    public $user_id;

    public $BankName;
    public $CusName;

    public function SetMainView($main_id){
        $rec=Main::where('id',$main_id)->first();
        $this->id=$main_id;
        $this->customer_id=$rec->customer_id;
        $this->bank_id=$rec->bank_id;
        $this->acc=$rec->acc;
        $this->sul_begin=$rec->sul_begin;
        $this->sul_end=$rec->sul_end;
        $this->sul=$rec->sul;
        $this->kst_count=$rec->kst_count;
        $this->kst=$rec->kst;
        $this->pay=$rec->pay;
        $this->raseed=$rec->raseed;
        $this->notes=$rec->notes;
        $this->user_id=$rec->user_id;
        $this->CusName=$rec->customer->CusName;
        $this->BankName=$rec->bank->BankName;
        $this->raseed=$this->sul-$this->pay;
    }
    public function Tarseed(){
        Main::where('id',$this->id)->update(['pay'=>Tran::where('main_id',$this->id)->sum('ksm'),]);
      $this->pay=Main::find($this->id)->pay;
      $this->raseed=$this->sul-$this->pay;

    }

}
