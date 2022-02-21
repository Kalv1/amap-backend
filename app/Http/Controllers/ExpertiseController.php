<?php

namespace App\Http\Controllers;

use App\Models\Expertise;
use Illuminate\Http\JsonResponse;

class ExpertiseController
{
    public function getExpertises():JsonResponse
    {
        return response()->json(Expertise::all());
    }
}
