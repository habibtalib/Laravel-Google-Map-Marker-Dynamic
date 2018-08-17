<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Physician extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'gender', 'age', 'language', 'long', 'lat'
    ];
}
