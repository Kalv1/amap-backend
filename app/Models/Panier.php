<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Panier extends Model
{

    protected $fillable = [
        'nom',
        'url_img',
        'id_producteur'
    ];
    protected $table = 'panier';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
