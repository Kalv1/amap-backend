<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\ProduitRecette;
use App\Models\Recette;
use App\Models\Ustensile;
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

            $recette = Recette::findOrFail($id);
            $recette->likes()->wherePivot('id_recette', '=', $id)->detach();
            $recette->ustensiles()->wherePivot('id_recette', '=', $id)->detach();
            $recette->delete();

            return response()->json("Resource deleted successfully", 200);
        }
        catch (\Exception $e){
            return response()->json(['error' => $e->getMessage(), 'code' => $e->getCode()]);
        }
    }

    public function getRecetteAime($id): JsonResponse
    {
        try {
            $recette = Recette::with('likes')
                ->where('id','=',$id)->first();
            $res = [];
            foreach($recette->likes as $aime){
                $res[] = [$aime->pivot->id_user => $aime->pivot->id_recette];
            }

        }
        catch (\Exception $e){
            return response()->json(['error' => $e->getMessage(), 'code' => $e->getCode()]);
        }
        return response()->json($res, 200);
    }

    public function getRecetteUstensiles($id): JsonResponse
    {
        try {
            $recette = Recette::with('ustensiles')
                ->where('id', '=', $id)->first();
            $res = [];
            $ustensiles = [];
            foreach($recette->ustensiles as $ustensile_recette){
                $res[] = ['id_ustensile' => $ustensile_recette->pivot->id_ustensile, 'id_recette' => $ustensile_recette->pivot->id_recette, 'nombre' => $ustensile_recette->pivot->nombre];

            }

            foreach($res as $value){
                $ustensileNom = Ustensile::findOrFail($value['id_ustensile']);

                $value["nom"] = $ustensileNom["nom"];

                $ustensiles[] = $value;
            }


        } catch (\Exception $e){
            return response()->json(['error' => $e->getMessage(), 'code' => $e->getCode()]);
        }

        return response()->json($ustensiles, 200);
    }

    public function putRecetteUstencile(Request $req): JsonResponse
    {
        foreach ($req->input('ustensiles') as $ustensile){
            if($ustensile != '' && $ustensile != null){
                $exist = Ustensile::where('nom', '=', mb_strtolower($ustensile))->first();
                $recette = Recette::with('ustensiles')
                    ->where('id', '=', $req->input('id_recette'))
                    ->first();
                if($exist === null) {
                    $newUstensile = new Ustensile();
                    $newUstensile->nom = mb_strtolower($ustensile);
                    $newUstensile->save();

                    $recette->ustensiles()->attach($newUstensile->id);

                } else {
                    $recette->ustensiles()->attach($req->input('id_ustensile'));
                }
            }
        }
        return response()->json(['message' => 'Ustensiles ajouté avec succes']);
    }

    public function getRecettesSimilaires(Request $req, $id): JsonResponse
    {
        $result = [];

        $ingredientsRecette = ProduitRecette::where('id_recette', '=', $id)->get();

        $prodsRecette = ProduitRecette::select('id_recette')->groupBy('id_recette')->get();
        $nbActuel = count($ingredientsRecette);

        while (count($result) < 6 && $nbActuel > 1) {
            foreach ($prodsRecette as $prodRecette) {
                if ($prodRecette->id_recette != $id) {
                    $ingrRec = ProduitRecette::where('id_recette', '=', $prodRecette->id_recette)->get();
                    $compteur = 0;
                    $prodSimi = [];

                    foreach ($ingrRec as $item) {
                        foreach ($ingredientsRecette as $i) {
                            if ($item->id_produit == $i->id_produit) {
                                $compteur++;
                                $prodSimi[] = Produit::find($item->id_produit);
                            }
                        }
                    }

                    if ($compteur === $nbActuel) {
                        $result[] = ['recette' => Recette::find($prodRecette->id_recette), 'prodSimi' => $prodSimi];
                    }
                }
            }
            $nbActuel--;
        }

        return response()->json($result, 200);
    }
}
