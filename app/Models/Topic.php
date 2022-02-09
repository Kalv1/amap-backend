<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $table = 'recette_question';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
