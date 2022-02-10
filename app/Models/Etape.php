<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Request;

class Etape extends Model
{
    protected $fillable = [
        'id_recette',
        'titre',
        'contenu',
        'temps',
        'numero',
        'url_img'
    ];

    protected $table = 'etape';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
