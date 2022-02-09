<?php

namespace App\Http\Controllers;

use App\Models\UstensileRecette;
use App\Models\Ustensile;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\Controller;

class UstensileRecetteController extends Controller
{

    public function getAll(): JsonResponse
    {
        return response()->json(UstensileRecette::all());
    }

    public function getUstensilesRecette(Request $req, $id): JsonResponse
    {
        $ustensiles = [];

        try {
            $res = UstensileRecette::where('id_recette', "=", $id)->get();

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 404, 'message' => 'Recette introuvable'], 404);
        }

        foreach($res as $value){
            $ustensileNom = Ustensile::findOrFail($value['id_ustensile']);

            $value["nom"] = $ustensileNom["nom"];

            array_push($ustensiles, $value);
        }

        return response()->json($ustensiles, 200);
    }

}
