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

    public function recettes()
    {
        return $this->belongsToMany(Ustensile::class, 'ustensile_recette', 'id_ustensile', 'id_recette')
            ->withPivot(['id_ustensile','id_recette', 'nombre']);
    }
}
