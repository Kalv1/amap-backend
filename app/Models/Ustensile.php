<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ustensile extends Model
{

    protected $fillable = [
        'nom'
    ];
    protected $table = 'ustensile';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
