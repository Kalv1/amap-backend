<?php

namespace App\Http\Controllers;

use App\Models\Etape;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\Controller;

class EtapeController extends Controller
{
    public function getRecipeEtapes($id): JsonResponse
    {
        $step = Etape::where('id_recette', '=', $id)->orderBy("numero", "asc")->get();
        if(!empty($step)) {
            return response()->json(Etape::where('id_recette', '=', $id)->orderBy("numero", "asc")->get());
        } else {
            return response()->json(['error' => 404, 'message' => 'Aucune étapes trouvée pour la recette'], 404);
        }

    }

    public function addEtape(Request $req){
        try {
            $this->validate($req, [
                'id_recette' => 'required',
                'contenu' => 'required',
                'temps' => 'required',
                'numero' => 'required'
            ]);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Modèle de donnée invalide certain champs sont manquant','error' => 404 ], 404);
        }
        $etape = Etape::create($req->all());
        $etape->save();
        return response()->json(['message' => 'Modèle ajouté avec succès', 'model' => $etape]);
    }
}
