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
        $numetape = 1;
        foreach ($req->input('etapes') as $etape) {
            if($etape['desc'] != '' && $etape['desc'] != null){
                $etapemodel = new Etape();
                $etapemodel->numero = $numetape;
                $numetape++;
                $etapemodel->id_recette = $req->input('id_recette');
                $etapemodel->contenu = $etape['desc'];
                if($etape['titre'] != '' || !is_null($etape['titre'])){
                    $etapemodel->titre = $etape['titre'];
                }
                if($etape['temps'] == '') {
                    $etapemodel->temps = 0;
                } else {
                    $etapemodel->temps = $etape['temps'];
                }
                $etapemodel->save();
            }
        }
        return response()->json(['message' => 'Etape ajouté avec succes']);
    }
}
