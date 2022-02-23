<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\User;
use App\Http\Controllers\ContributeurController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Laravel\Lumen\Routing\Controller;
use \DateTime;

class AnswerController extends Controller
{
    public function getAllFromQuestion(Request $req, $id): JsonResponse
    {
        $answers = [];

        try {
            $res = Answer::where('id_question', "=", $id)->get();

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 404, 'message' => 'Réponses introuvable'], 404);
        }

        foreach($res as $value){
            $user = User::findOrFail($value['id_user']);
            $nom = ContributeurController::getNameById($value['id_user']);
            $value["nom"] = $nom->original;
            $value["img_user"] = $user->url_img;

            array_push($answers, $value);
        }

        return response()->json($answers, 200);
    }

}
