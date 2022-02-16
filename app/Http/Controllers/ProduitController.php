<?php

namespace App\Http\Controllers;

use App\Models\Produit;

class ProduitController extends Controller
{
    public function getAll(){
        return response()->json(Produit::all());
    }
}
