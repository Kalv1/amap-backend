<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expertise extends Model
{
    protected $fillable = [
        'nom'
    ];

    protected $table = 'expertise';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function utilisateurs()
    {
        return
            $this->belongsToMany('App\Models\User', 'expertise_utilisateur', 'id_expertise', 'id_utilisateur')
                ->withPivot(['id_utilisateur', 'id_expertise']);
    }
}