<?php

namespace App\Http\Controllers;

use App\Models\Recette;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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


    function public_path($path = null)
    {
        return app()->basePath() . DIRECTORY_SEPARATOR . 'public'. DIRECTORY_SEPARATOR . $path;
    }


    public function create(Request $req) : JsonResponse
    {
        try {
            $this->validate($req, [
                'id_createur' => 'required',
                'titre' => 'required',
                'description' => 'required',
                'saison' => 'required',
                'difficulte' => 'required',
                'temps' => 'required|regex:/[0-9]+/',
                'nb_pers' => 'required',
                'regime' => 'required',
                'type' => 'required'
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Modèle de donnée incorrect']);
        }
        $recipe = Recette::create($req->all());
        if($req->hasFile('url_img')) {
            $picname = $req->file('url_img')->getClientOriginalName();
            $picname = uniqid() . '_' . $picname;
            $path = 'uploads' . DIRECTORY_SEPARATOR . 'img';
            $destination = $this->public_path($path);
            var_dump($destination);
            File::makeDirectory($destination, 0777, true, true);
            $req->file('url_img')->move($destination, $picname);
            $recipe->url_img = $picname;
        } else {
            $recipe->url_img = 'default.png';
        }
        $recipe->save();

        return response()->json(['id' => $recipe->id]);
    }

    public function deleteRecette($id): JsonResponse
    {
        try {
            Recette::findOrFail($id)->delete();
            return response()->json("Resource deleted successfully", 200);
        }
        catch (\Exception $e){
            return response()->json(['error' => $e->getMessage(), 'code' => $e->getCode()]);
        }
    }
}
