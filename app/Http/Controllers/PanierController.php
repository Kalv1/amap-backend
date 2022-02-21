<?php

namespace App\Http\Controllers;

use App\Models\ProduitPanier;
use App\Models\ProduitProducteur;
use App\Models\User;

class PanierController extends Controller
{
    public function getAll($id)
    {
        return response()->json(User::find($id)->products->makeHidden('pivot'));
    }

    public function addItem($id, $prod)
    {
        if (ProduitPanier::where([
            ['id_produit', '=', $prod],
            ['id_producteur', '=', $id]
        ])->exists()) {
            return response()->json(['error' => 'Produit déjà présent dans le panier'], 400);
        }
        $produit = new ProduitPanier();
        $produit->id_producteur = $id;
        $produit->id_produit = $prod;
        $produit->save();
        return response()->json($produit);
    }

    public function removeItem($id, $prod){
        if(ProduitPanier::where([
            ['id_produit', '=', $prod],
            ['id_producteur', '=', $id]
        ])->exists()) {
            $prodtorm = ProduitPanier::where([
                ['id_produit', '=', $prod],
                ['id_producteur', '=', $id]
            ])->delete();
            return response()->json(['message' => 'Produit supprimé avec succès']);
        } else {
            return response()->json(['message' => 'Produit innexistant pour se panier'], 404);
        }
    }
}
