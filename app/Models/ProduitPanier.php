<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProduitPanier extends Model
{
    protected $fillable = [
        'id_producteur',
        'id_produit'
    ];
    protected $table = 'produit_producteur';
    protected $primaryKey = [ 'id_produit', 'id_producteur'];
    public $timestamps = false;
    public $incrementing = false;
}
