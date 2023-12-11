<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use mysql_xdevapi\Table;

class Main extends Model
{
    use HasFactory;
  protected $connection = 'other';


  public function Bank(){
    return $this->belongsTo(Bank::class);
  }
  public function Customer(){
    return $this->belongsTo(Customer::class);
  }

  public function Sell(){
        return $this->hasMany(Sell::class);
    }

    public function Tran(){
        return $this->hasMany(Tran::class);
    }

    public function TarKst(){
     return $this->hasMany(Tarkst::class);
    }
    public function Stop(){
        return $this->hasOne(Stop::class);
}
    public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);
    if (Auth::check()) {
      $this->connection=Auth::user()->company;

    }
  }

}
