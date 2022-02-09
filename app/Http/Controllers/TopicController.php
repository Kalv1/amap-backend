<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\JsonResponse;
use Laravel\Lumen\Routing\Controller;

class TopicController extends Controller
{
    public function getAll(): JsonResponse
    {
        return response()->json(Topic::all());
    }
}
