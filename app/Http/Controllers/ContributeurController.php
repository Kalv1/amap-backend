<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;

class ContributeurController extends Controller
{
    public function getAll(): JsonResponse
    {
        return response()->json(User::all());
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
        return true;
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
