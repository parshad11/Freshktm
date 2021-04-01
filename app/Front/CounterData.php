<?php

namespace App\Front;

use Illuminate\Database\Eloquent\Model;

class CounterData extends Model
{
    protected $table='counter_data';

     /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
}
