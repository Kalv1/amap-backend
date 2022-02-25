<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nom', 'email', 'prenom', 'password', 'telephone'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'updated_at'
    ];

    protected $table = 'utilisateur';

    public function setPasswordAttribute($value) {
        $this->attributes['password'] = Hash::make($value);
    }

    public function products(){
        return $this->belongsToMany(Produit::class, 'produit_producteur', 'id_producteur', 'id_produit');
    }


    /**
     * Get following people
     */
    public function following()
    {
        return $this->belongsToMany(User::class, 'suivi', 'id_suiveur', 'id_suivi');
    }


    public function recettesAimees()
    {
        return $this->belongsToMany(Recette::class, 'aime', 'id_user', 'id_recette');
    }

    public function recettesFavorites()
    {
        return $this->belongsToMany(Recette::class, 'favoris', 'id_user', 'id_recette');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function recettes(){
        return
            $this->belongsToMany('App\Models\Recette', 'avis','id_utilisateur', 'id_recette')
                ->withPivot(['id','texte']);
    }

    public function expertises(){
        return
            $this->belongsToMany('App\Models\Expertise', 'expertise_utilisateur','id_utilisateur', 'id_expertise')
                ->withPivot(['id_utilisateur','id_expertise']);
    }

    public function messages(){
        return
            $this->belongsToMany('App\Models\Question', 'reponse_question','id_user', 'id_question')
                ->withPivot(['reponse', 'date']);
    }
}
