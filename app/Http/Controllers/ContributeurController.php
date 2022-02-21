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

    static function getNameById($id): JsonResponse
    {
        try {
            $res = User::findOrFail($id);
            $nom = $res->nom.' '.$res->prenom;
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 404, 'message' => 'Recette introuvable'], 404);
        }
        return response()->json($nom, 200);
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

        $sixDernieres = Recette::where('id_createur', '=', $id)->orderBy('created_at', 'desc')->take(6)->get();
        foreach ($sixDernieres as $key => $value) {
            foreach ($recettes as $k => $v) {
                if ($v['id'] === $value['id']) {
                    $test[] = $v['id'];
                    unset($recettes[$k]);
                }
            }
        }
        if ($sixDernieres->count() > 0) {
            $result["sixDernieres"] = $sixDernieres;
        }

        if ($recettes->count() > 0) {
            $result["recettes"] = $recettes;
        }

        if (isset($result)) {
            return response()->json($result);
        } else {
            return response()->json(['message' => 'Aucune recette']);
        }
    }

    public function getProducteursName(){

        $productors = [];

        try {
            $res = User::select("id", "nom", "prenom")->where('producteur', "=", 1)->get();

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 404, 'message' => 'Producteur introuvable'], 404);
        }

        foreach($res as $value){
            $productor = [];
            $productor['id'] = $value['id'];
            $productor['nom'] = $value['nom'].' '.$value['prenom'];
            array_push($productors, $productor);
        }

        return response()->json($productors, 200);
    }
}
