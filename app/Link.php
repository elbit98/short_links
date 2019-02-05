<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    public $incrementing = false;
    protected $primaryKey = 'processed_link';

    protected $fillable = ['processed_link', 'standard_link'];

}
