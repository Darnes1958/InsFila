<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Overkst_arc extends Model
{
  protected $connection = 'other';

  public function Main_arc(){
    return $this->belongsTo(Main_arc::class);
  }
  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);
    if (Auth::check()) {
      $this->connection = Auth::user()->company;
    }
  }
}
