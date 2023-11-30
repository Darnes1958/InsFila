<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class WrongForm extends Form
{
    #[Rule('required',message: 'يجب ادخال التاريخ')]
    public $wrong_date = '';
    #[Rule('required',message: 'يجب ادخال الخصم')]
    public $kst = '';
    #[Rule('required',message: 'يجب ادخال المصرف')]
    public $bank_id='';
    public $user_id='';

}
