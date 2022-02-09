<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recette extends Model
{
    protected $fillable = [
        'titre',
        'description',
        'saison',
        'difficulte',
        'nb_pers',
        'regime',
        'id_createur',
        'temps'
    ];

    protected $table = 'recette';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function utilisateurs()
    {
        return
            $this->belongsToMany('App\Models\User', 'avis', 'id_recette', 'id_utilisateur')
                ->withPivot(['id', 'texte']);
    }
}