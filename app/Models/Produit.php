<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{

    protected $fillable = [
        'nom'
    ];
    protected $table = 'produit';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
