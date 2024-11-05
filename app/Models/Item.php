<?php

namespace App\Models;

use App\Enums\TwoUnit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Item extends Model
{
    protected $connection = 'other';



    public function Place_stock(){
      return $this->hasMany(Place_stock::class);
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        if (Auth::check()) {
            $this->connection=Auth::user()->company;

        }
    }
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts =[
        'two_unit' => TwoUnit::class,
    ];
}
