<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\ProduitPanier;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProduitPanierController extends Controller
{
    public function getAll(): JsonResponse
    {
        return response()->json(ProduitPanier::all());
    }

    public function getProduitsPanier(Request $req, $id): JsonResponse
    {
        try {
            $res = ProduitPanier::where('id_producteur', "=", $id)->get();

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 404, 'message' => 'Recette introuvable'], 404);
        }

        return response()->json($res, 200);
    }
}
