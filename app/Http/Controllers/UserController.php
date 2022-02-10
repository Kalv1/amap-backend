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
    public function getUsers(): JsonResponse
    {
        return response()->json(User::all());
    }

    public function getUser($id)
    {
        try {
            $user = User::findOrFail($id);
        }
        catch (ModelNotFoundException $e) {
            return response()->json(['error' => 404, 'message' => "Utilisateur introuvable"], 404);
        }

        return response()->json($user, 200);

    }

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

        $this->validate($request, array(
            'email' => "remail|unique:users,email,$id"
        ));

        $nom = $request->input('nom');
        $prenom = $request->input('prenom');
        $email = $request->input('email');
        $telephone = $request->input('tel');
        $password = $request->input('password');


        if($email !== null) {
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            $user->email = $email;
        }
        if($password !== null) {
            $password = filter_var($password, FILTER_SANITIZE_STRING);
            $user->password = $password;
        }
        if($nom !== null) {
            $nom = filter_var($nom, FILTER_SANITIZE_STRING);
            $user->nom = $nom;
        }
        if($prenom !== null) {
            $prenom = filter_var($prenom, FILTER_SANITIZE_STRING);
            $user->prenom = $prenom;
        }
        if($telephone !== null) {
            $telephone = filter_var($telephone, FILTER_SANITIZE_NUMBER_INT);
            $user->telephone = $telephone;
        }

        $user->save();

        return response()->json($user,201);

    }
}
