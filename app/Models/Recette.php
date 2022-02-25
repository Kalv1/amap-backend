<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recette extends Model
{
    use hasFactory;

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
    public $timestamps = true;

    public function utilisateurs()
    {
        return
            $this->belongsToMany('App\Models\User', 'avis', 'id_recette', 'id_utilisateur')
                ->withPivot(['id', 'texte']);
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'aime', 'id_recette', 'id_user')
                    ->withPivot(['id_user','id_recette']);
    }

    public function favs()
    {
        return $this->belongsToMany(User::class, 'favoris', 'id_recette', 'id_user')
            ->withPivot(['id_user','id_recette']);
    }

    public function ustensiles()
    {
        return $this->belongsToMany(Ustensile::class, 'ustensile_recette', 'id_recette', 'id_ustensile')
            ->withPivot(['id_ustensile','id_recette', 'nombre']);
    }
}
