<?php

namespace Database\Factories;

use App\Models\Recette;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecetteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Recette::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $saison = rand(1,5);
        switch($saison) {
            case 1:
                $s = 'toutes';
                break;
            case 2:
                $s = 'été';
                break;
            case 3:
                $s = 'printemps';
                break;
            case 4:
                $s = 'automne';
                break;
            case 5:
                $s = 'hiver';
                break;
        }
        $regime = rand(1,7);
        switch($regime) {
            case 1:
                $r = 'végétarien';
                break;
            case 2:
                $r = 'vegan';
                break;
            case 3:
                $r = 'sans gluten';
                break;
            case 4:
                $r = 'flexitarien';
                break;
            case 5:
                $r = 'hypocalorique';
                break;
            case 6:
                $r = 'carnivore';
                break;
            case 7:
                $r = 'omnivore';
                break;
        }
        $type = rand(1,5);
        switch($type) {
            case 1:
                $t = 'plat';
                break;
            case 2:
                $t = 'dessert';
                break;
            case 3:
                $t = 'entrée';
                break;
            case 4:
                $t = 'collation';
                break;
            case 5:
                $t = 'boisson';
                break;
        }

        $id_crea = rand(2,26);
        return [
            'id_createur' => $id_crea,
            'titre' => $this->faker->word(),
            'saison' => $s,
            'description' => $this->faker->realText(200,  2),
            'url_img' => 'https://i0.wp.com/www.mimisrecipes.com/wp-content/uploads/2018/12/recipe-placeholder-featured.jpg?resize=700%2C400&ssl=1',
            'difficulte' => rand(1,3),
            'temps' => rand(60, 1200),
            'nb_pers' => rand(1, 7),
            'regime' => $r,
            'type' => $t,
        ];
    }
}
