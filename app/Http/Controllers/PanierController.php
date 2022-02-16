<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\ProduitProducteur;
use App\Models\User;

class PanierController extends Controller
{
    public function getAll($id){
        return response()->json(User::find($id)->products->makeHidden('pivot'));
    }
}
