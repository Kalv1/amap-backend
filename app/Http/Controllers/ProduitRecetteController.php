<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\ProduitRecette;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProduitRecetteController extends Controller
{
    public function getAll(): JsonResponse
    {
        return response()->json(ProduitRecette::all());
    }

    public function getProduitsRecette(Request $req, $id): JsonResponse
    {
        $produits = [];

        try {
            $res = ProduitRecette::where('id_recette', "=", $id)->get();

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 404, 'message' => 'Recette introuvable'], 404);
        }

        foreach($res as $value){
            $produitNom = Produit::findOrFail($value['id_produit']);

            $value["nom"] = $produitNom["nom"];

            array_push($produits, $value);
        }

        return response()->json($produits, 200);
    }
}
