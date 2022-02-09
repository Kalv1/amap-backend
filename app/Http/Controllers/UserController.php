<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Laravel\Lumen\Routing\Controller;

class UserController extends Controller
{
    public function getUserAvis($id): JsonResponse
    {
        try {
            $user = User::with('recettes')
                ->where('id','=',$id)->first();
            $res = [];
            foreach($user->recettes as $avis){
                $res[] = [$avis->pivot->id => $avis->pivot->texte];
            }


        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 404, 'message' => 'Recette introuvable'], 404);
        }
        return response()->json($res, 200);
    }

    public function getUserTopics($id): JsonResponse
    {
        try {
            $topics = Topic::where('id_user','=',$id)->get();
        }
        catch (ModelNotFoundException $e) {
            return response()->json(['error' => 404, 'message' => "Topics de l'utilisateur introuvable"], 404);
        }
        return response()->json($topics, 200);
    }

    public function putUser(Request $request, $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);
        }
        catch (ModelNotFoundException $e) {
            return response()->json(['error' => 404, 'message' => "Utilisateur introuvable"], 404);
        }
                $nom = $request->input('nom');
                $prenom = $request->input('prenom');
                $email = $request->input('email');
                $telephone = $request->input('tel');

                $nom = filter_var($nom, FILTER_SANITIZE_STRING);
                $prenom = filter_var($prenom, FILTER_SANITIZE_STRING);
                $email = filter_var($email, FILTER_SANITIZE_EMAIL);
                $telephone = filter_var($telephone, FILTER_SANITIZE_NUMBER_INT);

                $user->nom = $nom;
                $user->prenom = $prenom;
                $user->email = $email;
                $user->telephone = $telephone;
                $user->save();

        return response()->json(201);

    }
}
