<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'setting';
    protected $fillable = ['pair','type','repeat','type_24hr','value_24hr','type_price','value_price','amount','status','user_id'];

    const STATUS_ACTIVE = 1;
    const STATUS_STOP = 0;
    const STATUS_DONE = 2;
}
