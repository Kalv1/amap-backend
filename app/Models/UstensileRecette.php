<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UstensileRecette extends Model
{

    protected $fillable = [
        'nombre'
    ];


    protected $table = 'ustensile_recette';
    protected $primaryKey = ['id_ustensile', 'id_recette'];
    public $timestamps = false;
    public $incrementing = false;
}
