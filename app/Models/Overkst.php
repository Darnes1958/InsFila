<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Overkst extends Model
{
  protected $connection = 'other';

  public function Main(){
    return $this->belongsTo(Main::class);
  }
  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);
    if (Auth::check()) {
      $this->connection = Auth::user()->company;
    }
  }
}
