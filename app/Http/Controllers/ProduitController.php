<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    public function getAll(): JsonResponse
    {
        return response()->json(Produit::orderBy("nom", "asc")->get());
    }

}
