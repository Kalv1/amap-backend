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

    public function addProduit(Request $request){
        foreach($request->input('produits') as $produit){
            if($produit['nom'] != '' && !is_null($produit['nom'])){
                $prod = Produit::where('nom', '=', mb_strtolower($produit['nom']))->first();
                if($prod === null) {
                    $newprod = new Produit();
                    $newprod->nom = mb_strtolower($produit['nom']);
                    $newprod->save();
                    $prodrecette = new ProduitRecette();
                    $prodrecette->id_recette = $request->input('id_recette');
                    $prodrecette->id_produit = $newprod->id;
                    $prodrecette->nombre = $produit['poid'];
                    $prodrecette->unite = $produit['unite'];
                } else {
                    $prodrecette = new ProduitRecette();
                    $prodrecette->id_recette = $request->input('id_recette');
                    $prodrecette->id_produit = $prod->id;
                    $prodrecette->nombre = $produit['poid'];
                    $prodrecette->unite = $produit['unite'];
                }
                $prodrecette->save();
            }
        }
        return response()->json(['message' => 'Produits ajouté avec succes']);
    }
}
