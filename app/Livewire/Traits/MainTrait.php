<?php
namespace App\Livewire\Traits;

use DateTime;

trait MainTrait {
  public function EndDate($date,$card_nocount){
      return $date = date('Y-m-d', strtotime($date . "+".$card_nocount." month"));
  }
}
