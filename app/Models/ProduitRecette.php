<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProduitRecette extends Model
{

    protected $fillable = [
        'nombre',
        'unite'
    ];
    protected $table = 'produit_recette';
    protected $primaryKey = ['id_produit', 'id_recette'];
    public $timestamps = false;
    public $incrementing = false;
}
