<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Propietario extends Model
{
    public $timestamps = true;
    protected $fillable = [
        'name'
    ];
}