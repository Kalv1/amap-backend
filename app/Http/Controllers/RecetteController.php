<?php

namespace App\Http\Controllers;

use App\Models\Recette;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

     return response()->json('');
    }
}
