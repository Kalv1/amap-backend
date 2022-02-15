<?php

namespace App\Http\Controllers;

use App\Models\Etape;
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

    public function addUstensile(Request $req){
        foreach ($req->input('ustenciles') as $ustensile){
            if($ustensile != '' && $ustensile != null){
                $exist = Ustensile::where('nom', '=', mb_strtolower($ustensile))->first();
                if($exist === null) {
                    $newUstensile = new Ustensile();
                    $newUstensile->nom = mb_strtolower($ustensile);
                    $newUstensile->save();

                    $ustensileRecette = new UstensileRecette();
                    $ustensileRecette->id_ustensile = $newUstensile->id;
                    $ustensileRecette->id_recette = $req->input('id_recette');
                    $ustensileRecette->save();
                } else {
                    $ustensileRecette = new UstensileRecette();
                    $ustensileRecette->id_ustensile = $exist->id;
                    $ustensileRecette->id_recette = $req->input('id_recette');
                    $ustensileRecette->save();
                }
            }
        }
        return response()->json(['message' => 'Ustenciles ajouté avec succes']);
    }
}


