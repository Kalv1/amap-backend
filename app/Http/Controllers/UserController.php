<?php

namespace App\Http\Controllers;

use App\Models\Expertise;
use App\Models\Recette;
use App\Models\Topic;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Laravel\Lumen\Routing\Controller;

class UserController extends Controller
{
    // Global user methods
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

    // User's Avis methods
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

    // User's Topics methods
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

    // User's Expertises methods
    public function getUserExpertises($id): JsonResponse
    {

        try {
            $user = User::with('expertises')
                ->where('id','=',$id)
                ->first();
            $res = [];
            foreach($user->expertises as $expertise_utilisateur){
                $expertise = Expertise::find($expertise_utilisateur->pivot->id_expertise);
                $res[] = $expertise;
            }


        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 404, 'message' => 'Expertise introuvable'], 404);
        }


        return response()->json($res, 200);
    }

    public function postUserExpertise($idUser, $idExpertise): JsonResponse
    {
        try {
            $user = User::with('expertises')
                ->where('id','=',$idUser)
                ->first();

            // Get user's expertises
            $res = [];
            foreach($user->expertises as $expertise_utilisateur){
                $expertise = Expertise::find($expertise_utilisateur->pivot->id_expertise);
                $res[] = $expertise;
            }
        }
        catch (ModelNotFoundException $e) {
            return response()->json(['error' => 404, 'message' => 'Utilisateur introuvable'], 404);
        }

        // Verify if user's expertise already exist
        $isExist = false;
        foreach ($res as $element) {
            if ($idExpertise == $element->id) {
                $isExist = true;
            }
        }

        if($isExist) {
            return response()->json(['error' => 409, 'message' => "L'utilisateur possède déjà cette expertise"], 409);
        }
        else {
            $user->expertises()->attach($idExpertise);
            return response()->json("Resource created successfully", 201);
        }

    }

    public function deleteUserExpertise($idUser, $idExpertise): JsonResponse
    {
        // Find user
        try {
            $user = User::find($idUser);
            $user->expertises()->wherePivot('id_expertise', '=', $idExpertise)->detach();
        }
        catch (ModelNotFoundException $e) {
            return response()->json(['error' => 404, 'message' => 'Utilisateur introuvable'], 404);
        }

        return response()->json("Resource deleted successfully", 200);

    }

    // User's Likes methods
    public function getLikedRecette($idUser): JsonResponse
    {
        try {
            $recettes = User::find($idUser)->recettesAimees()->get();
            return response()->json($recettes);
        }
        catch (\Exception $e){
            return response()->json(['error' => $e->getMessage(), 'code' => $e->getCode()]);
        }
    }

    public function likeRecette($idUser, $idRecette): JsonResponse
    {
        try {
            User::find($idUser)->recettesAimees()->attach($idRecette);
            return response()->json(['message' => 'Recette aimée']);
        }
        catch (\Exception $e){
            return response()->json(['error' => $e->getMessage(), 'code' => $e->getCode()]);
        }
    }

    public function dislikeRecette($idUser, $idRecette): JsonResponse
    {
        try {
            User::find($idUser)->recettesAimees()->detach($idRecette);
            return response()->json(['message' => 'Recette plus aimée']);
        }
        catch (\Exception $e){
            return response()->json(['error' => $e->getMessage(), 'code' => $e->getCode()]);
        }
    }
    //User's favorites methods

    public function getFavRecette($idUser): JsonResponse
    {
        try {
            $recettes = User::find($idUser)->recettesFavorites()->get();
            return response()->json($recettes);
        }
        catch (\Exception $e){
            return response()->json(['error' => $e->getMessage(), 'code' => $e->getCode()]);
        }
    }

    public function favRecette($idUser, $idRecette): JsonResponse
    {
        try {
            User::find($idUser)->recettesFavorites()->attach($idRecette);
            return response()->json(['message' => 'Recette en favorite']);
        }
        catch (\Exception $e){
            return response()->json(['error' => $e->getMessage(), 'code' => $e->getCode()]);
        }
    }

    public function unfavRecette($idUser, $idRecette): JsonResponse
    {
        try {
            User::find($idUser)->recettesFavorites()->detach($idRecette);
            return response()->json(['message' => "Recette n'est en plus favorites"]);
        }
        catch (\Exception $e){
            return response()->json(['error' => $e->getMessage(), 'code' => $e->getCode()]);
        }
    }

    // User's Recipes methods
    public function getUserRecettes($id): JsonResponse
    {
        try {
            $recettes = Recette::with('likes')->where('id_createur','=',$id)->get();
            $res = [];
            foreach ($recettes as $recette) {
                $res[] = ['recette' => $recette , 'nbLikes' => $recette->likes->count()];
            }
        }
        catch (\Exception $e){
            return response()->json(['error' => $e->getMessage(), 'code' => $e->getCode()]);
        }

        return response()->json($res, 200);
    }

}
