<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expertise extends Model
{
    protected $fillable = [
        'nom'
    ];
    protected $table = 'expertise';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
