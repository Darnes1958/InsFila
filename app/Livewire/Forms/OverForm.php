<?php

namespace App\Livewire\Forms;

use App\Models\Overkst;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class OverForm extends Form
{


  #[Rule('required')]
  public $main_id = '';
  #[Rule('required|date')]
  public $over_date = '';
  #[Rule('required')]
  public $kst = '';
  #[Rule('required')]
  public $status = 'غير مرجع';
  public $tar_id ='';
  public $haf_id = '';
  public $user_id = '';

  public function SetOver(Overkst $over) {

    $this->main_id=$over->main_id;
    $this->over_date=$over->over_date;
    $this->kst=$over->kst;
    $this->status=$over->status;
    $this->tar_id=$over->tar_id;
    $this->haf_id=$over->haf_id;
    $this->user_id=$over->user_id;
  }


}
