<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Recette;
use App\Http\Controllers\ContributeurController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Laravel\Lumen\Routing\Controller;
use \DateTime;

class TopicController extends Controller
{
    public function getAll(): JsonResponse
    {
        $res = Question::all();
        $questions = [];
        foreach($res as $value){
            $nom = ContributeurController::getNameById($value['id_user']);
            $recette = Recette::find($value['id_recette']);
            $value["nom"] = $nom->original;
            $value["recette"] = $recette->titre;

            array_push($questions, $value);
        }

        return response()->json($res);
    }

    public function getQuestion(Request $req, $id): JsonResponse
    {
        try {
            $res = Question::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 404, 'message' => 'Recette introuvable'], 404);
        }

        $nom = ContributeurController::getNameById($res['id_user']);
        $recette = Recette::find($res['id_recette']);
        $res["nom"] = $nom->original;
        $res["recette"] = $recette->titre;

        return response()->json($res, 200);
    }

    public function addQuestion(Request $request): JsonResponse
    {
        $question = new Question();
        $id_recette = $request->input('id_recette');
        $id_user = $request->input('id_user');
        $id_expertise = $request->input('expertise');
        $message = $request->input('message');
        $date = new DateTime();

        if(isset($id_recette) && isset($id_user) && isset($id_expertise) && isset($message)){
            $question->id_recette = $id_recette;
            $question->id_user = $id_user;
            $question->id_expertise = $id_expertise;
            $question->question = $message;
            $question->date = $date;

            $question->save();

            return response()->json($question,201);
        }else {
            return response()->json(['error' => 404, 'message' => "La question de l'utilisateur ne peux pas être créé"], 404); 
        }
    }

    public function putResolved(Request $request) {
        
            try {
                $question = Question::find($request->input('id_question'));
            } catch (ModelNotFoundException $e) {
                return response()->json(['message' => 'Question innexistante'], 404);
            }

            $question->resolu = true;
            $question->save();

            return response()->json($question,201);
        
    }
}