<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;

class ContributeurController extends Controller
{
    public function getAll(): JsonResponse
    {
        return response()->json(User::all());
    }

    public function getSuivis($id): JsonResponse
    {
        return response()->json(User::find($id)->following);
    }

    public function suivre($idSuiveur, $idSuivi) {
        try {
            User::find($idSuiveur)->following()->attach($idSuivi);
            return response()->json(['message' => 'Utilisateur suivi']);
        }
        catch (\Exception $e){
            return response()->json(['error' => $e->getMessage(), 'code' => $e->getCode()]);
        }
    }

    public function nePlusSuivre($idSuiveur, $idSuivi) {
        try {
            User::find($idSuiveur)->following()->detach($idSuivi);
            return response()->json(['message' => 'Utilisateur plus suivi']);
        }
        catch (\Exception $e){
            return response()->json(['error' => $e->getMessage(), 'code' => $e->getCode()]);
        }
    }

    public function getRecettes($id) {
        return true;
    }
}
