<?php

namespace App\Http\Controllers;

use App\Models\Etape;
use Illuminate\Http\JsonResponse;
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
}
