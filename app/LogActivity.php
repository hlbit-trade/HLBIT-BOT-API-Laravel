<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    protected $table = 'log';
    protected $fillable = ['status','message'];
}
