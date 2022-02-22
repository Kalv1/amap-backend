<?php

namespace Database\Seeders;

use App\Models\Recette;
use Illuminate\Database\Seeder;

class RecetteSeeder extends Seeder
{
    public function run()
    {
        Recette::factory(20)->create();
    }
}
