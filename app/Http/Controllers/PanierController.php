<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ContributeurController;
use App\Models\Panier;
use Illuminate\Http\JsonResponse;

class PanierController extends Controller
{
    public function getAll(): JsonResponse
    {
        $res = Panier::all();

        foreach($res as $value){
            $userNom = ContributeurController::getNameById($value['id_producteur']);

            $value["nom"] = $userNom->original;

        }

        return response()->json($res);

    }
}
