<?php

namespace App\Livewire\Forms;


use App\Models\Main;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Livewire\Attributes\Rule;
use Livewire\Form;


class MainForm extends Form
{


    #[Rule('required')]
    #[Rule('unique:other.mains,id',message: 'هذا الرقم مخزون مسبقا')]
    public $id = '';

    #[Rule('required')]
    public $customer_id = '';

    #[Rule('required')]
    public $bank_id = '';

    #[Rule('required',message: 'يجب ادخال رقم الحساب')]
    public $acc = '';

    #[Rule('required',message: 'يجب ادخال تاريخ ')]
    #[Rule('date',message: 'يجب ادخال تاريخ صحيح')]
    public $sul_begin = '';


    public $sul_end = '';

    #[Rule('required',message: 'يجب ادخال قيمة العقد')]
    #[Rule('numeric',message: 'يجب ادخال قيمة العقد')]
    #[Rule('min:1',message: 'يجب ادخال قيمة العقد صحيحة')]
    public $sul = '';

    #[Rule('required',message: ' يجب ادخال عدد الاقساط')]
    #[Rule('numeric',message:   'يجب ادخال رقم')]
    #[Rule('min:1',message: 'يجب ادخال عدد الاقساط صحيح')]
    public $kst_count = '';

    #[Rule('required',message: ' يجب ادخال قيمة القسط')]
    #[Rule('numeric',message:   'يجب ادخال رقم')]
    #[Rule('min:1',message: 'يجب ادخال قيمة القسط صحيحة')]

    public $kst = '';

    public $pay = 0;
    public $raseed = 0;

    public $notes = '';

    public $user_id;



    public function SetMain($id){
        $rec=Main::where('id',$id)->first();
        $this->id=$id;
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

    }


}
