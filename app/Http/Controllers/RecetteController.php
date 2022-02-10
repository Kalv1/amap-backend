<?php

namespace App\Http\Controllers;

use App\Models\Recette;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RecetteController extends Controller
{
    public function getAll(){
        return response()->json(Recette::all());
    }

    public function getRecette(Request $req, $id): JsonResponse
    {
        try {
            $res = Recette::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 404, 'message' => 'Recette introuvable'], 404);
        }
        return response()->json($res, 200);
    }

    public function create(Request $req) : JsonResponse
    {
        try {
            var_dump($req);
            $this->validate($req, [
                'id_createur' => 'required',
                'titre' => 'required',
                'description' => 'required',
                'saison' => 'required',
                'difficulte' => 'required',
                'temps' => 'required',
                'nb_pers' => 'required',
                'regime' => 'required',

            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Modèle de donnée incorrect']);
        }
        $recipe = Recette::create($req->all());
        $recipe->save();
        return response()->json($recipe);
    }
}
