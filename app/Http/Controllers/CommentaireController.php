<?php

namespace App\Http\Controllers;

use App\Models\Avis;
use App\Models\Recette;
use App\Models\User;

class CommentaireController extends Controller
{

    public function getAllFromRecipe($id){

        $recette = Recette::with('utilisateurs')->where('id' , '=', $id)->get();

        return response()->json($recette);

    }

}
