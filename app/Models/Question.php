<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'id_recette',
        'id_user',
        'id_expertise',
        'question',
        'date'
    ];
    protected $table = 'recette_question';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
