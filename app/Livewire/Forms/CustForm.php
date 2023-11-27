<?php

namespace App\Livewire\Forms;


use App\Models\INS\Cust;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Livewire\Attributes\Rule;
use Livewire\Form;


class CustForm extends Form
{

    #[Rule('required',message: 'يجب ادخال الاسم')]
    public $CusName = '';
    public $address = '';
    public $mdar = '';
    public $libyana = '';
    public $card_no = '';
    public $other = '';
    public $user_id='';

    public function SetCust(Cust $cust){

        $this->cust_name=$cust->cust_name;
        $this->address=$cust->address;
        $this->mdar=$cust->mdar;
        $this->libyana=$cust->libyana;
        $this->other=$cust->other;
        $this->card_no=$cust->card_no;
        $this->user_id=$cust->user_id;
    }


}
