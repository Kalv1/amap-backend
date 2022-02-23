<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{

    protected $fillable = [
        'reponse',
        'date'
    ];
    protected $table = 'reponse_question';
    protected $primaryKey = ['id_question', 'id_user'];
    public $timestamps = false;
    public $incrementing = false;
}
