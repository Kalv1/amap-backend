<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Laravel\Lumen\Routing\Controller;
use \DateTime;

class TopicController extends Controller
{
    public function getAll(): JsonResponse
    {
        return response()->json(Topic::all());
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
            return response()->json(['error' => 404, 'message' => "La question de l'utilisateur ne peux pas être créé".$id_expertise], 404); 
        }
    }
}
