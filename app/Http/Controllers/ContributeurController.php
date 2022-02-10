<?php

namespace App\Http\Controllers;

use App\Models\Recette;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class ContributeurController extends Controller
{
    public function getAll(): JsonResponse
    {
        $contributeurs = [];

        $recettes = Recette::selectRaw('COUNT(*) as nb_recettes, id_createur')->groupBy('id_createur')->get();
        foreach ($recettes as $recette) {
            $contributeurs[] = User::find($recette->id_createur);
        }
        return response()->json($contributeurs);
    }

    public function getSuivis($id): JsonResponse
    {
        return response()->json(User::find($id)->following);
    }

    public function getContributeur($id): JsonResponse
    {
        return response()->json(User::find($id));
    }

    public function suivre($idSuiveur, $idSuivi) {
        try {
            User::find($idSuiveur)->following()->attach($idSuivi);
            return response()->json(['message' => 'Utilisateur suivi']);
        }
        catch (\Exception $e){
            return response()->json(['error' => $e->getMessage(), 'code' => $e->getCode()]);
        }
    }

    public function nePlusSuivre($idSuiveur, $idSuivi) {
        try {
            User::find($idSuiveur)->following()->detach($idSuivi);
            return response()->json(['message' => 'Utilisateur plus suivi']);
        }
        catch (\Exception $e){
            return response()->json(['error' => $e->getMessage(), 'code' => $e->getCode()]);
        }
    }

    public function getRecettes($id) {
        //récupération de toutes les recettes du contributeur ayant l'id passé en paramètre
        $recettes = Recette::where('id_createur', '=', $id)->get();
        $nbLikesMax = 0;
        $topRecette = null;

        //recherche de la recette la plus likée
        foreach ($recettes as $recette) {
            $nb_likes = count($recette->likes);
            if (count($recette->likes) > $nbLikesMax) {
                $topRecette = $recette;
                $nbLikesMax = count($recette->likes);
            }
            unset($recette->likes);
            $recette->nbLikes = $nb_likes;
        }

        //si au moins 1 like
        if ($topRecette !== null) {
            $result["topRecette"] = $topRecette;

            $doublon = null;
            foreach ($recettes as $key => $value) {
                if ($value === $topRecette) {
                    $doublon = $key;
                }
            }
            unset($recettes[$doublon]);
        }

        $result["recettes"] = $recettes;
        return response()->json($result);
    }
}
