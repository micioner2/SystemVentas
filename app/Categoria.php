<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = ['nombre','estado'];
    public $timestamps = false;
    protected $guarded = ['id'];
}
