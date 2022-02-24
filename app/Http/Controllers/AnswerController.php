<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
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

    public function addAnswer(Request $request, $id): JsonResponse
    {
        $answer = new Answer();
        $id_question = $request->input('id_question');
        $id_user = $request->input('id_user');
        $reponse = $request->input('reponse');
        $date = new DateTime('NOW');

        if(isset($answer) && isset($id_user) && isset($reponse)){
            $answer->id_question = $id_question;
            $answer->id_user = $id_user;
            $answer->reponse = $reponse;
            $answer->date = $date;

            $answer->save();

            $user = User::findOrFail($answer['id_user']);
            $nom = ContributeurController::getNameById($answer['id_user']);
            $answer["nom"] = $nom->original;
            $answer["img_user"] = $user->url_img;
            $answer->date = $date->format('Y-m-d H:i:s');

            return response()->json($answer,201);
        }else {
            return response()->json(['error' => 404, 'message' => "La réponse ne peux pas être créé"], 404); 
        }
    }

    public function deleteAnswer($id_question, $id_user): JsonResponse
    {
        if(Answer::where([
            ['id_question', '=', $id_question],
            ['id_user', '=', $id_user]
        ])->exists()) {
            $answer = Answer::where([
                ['id_question', '=', $id_question],
                ['id_user', '=', $id_user]
            ])->delete();
            return response()->json(['message' => 'réponse supprimé avec succès']);
        } else {
            return response()->json(['message' => 'réponse innexistant'], 404);
        }

    }

    public function putAnswer(Request $request, $id_question, $id_user) {
        $user = User::find($id_user);
        $question = Question::find($id_question);
        try {
            $user->messages()->updateExistingPivot($id_question, array('reponse' => $request->input('reponse'), 'date' => new DateTime()),false);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Problème lors de la modification de la réponse'], 404);
        }

        return response()->json(201);
        
    }

}
