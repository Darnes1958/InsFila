<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Sell extends Model
{
    protected $connection = 'other';

    public function Sell_tran(){
        return $this->hasMany(Sell_tran::class);
    }
    public function Main(){
        return $this->hasOne(Main::class);
    }
    public function Main_arc(){
        return $this->hasOne(Main_arc::class);
    }
    public function Place(){
        return $this->belongsTo(Place::class);
    }
    public function Customer(){
      return $this->belongsTo(Customer::class);
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        if (Auth::check()) {
            $this->connection=Auth::user()->company;
        }
    }
}
