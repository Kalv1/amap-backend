<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('recettes', 'RecetteController@getAll');
$router->get('recette/{id:[0-9]+}', 'RecetteController@getRecette');

$router->post('auth/login', 'AuthController@login');
$router->post('auth/register', 'AuthController@register');


/*
 * Route for Contributor
 */
$router->get('contributeur/{id:[0-9]+}', 'ContributeurController@getNameById');
$router->get('contributeurs', 'ContributeurController@getAll');
$router->get('contributeurs/suivis/{id:[0-9]+}', 'ContributeurController@getSuivis');
$router->get('contributeur/{id:[0-9]+}/recettes', 'ContributeurController@getRecettes');
$router->put('contributeur/{idSuiveur:[0-9]+}/suivre/{idSuivi:[0-9]+}', 'ContributeurController@suivre');
$router->put('contributeur/{idSuiveur:[0-9]+}/unfollow/{idSuivi:[0-9]+}', 'ContributeurController@nePlusSuivre');


/*
 * Route Productor
 */

$router->get('producteurs', 'ContributeurController@getProducteursName');

/*
 * Route for productsRecipe
*/

$router->get('recette/produits/{id:[0-9]+}', 'ProduitRecetteController@getProduitsRecette');

/*
 * Route for productsBasket
*/

$router->get('panier/produits/{id:[0-9]+}', 'ProduitPanierController@getProduitsPanier');


/*
 * Route for product
*/

$router->get('produits', 'ProduitController@getAll');

/*
 * Route for ustensile
*/

$router->get('recette/ustensiles/{id:[0-9]+}', 'UstensileRecetteController@getUstensilesRecette');


/*
 * Route for step
*/

$router->get('recette/etapes/{id:[0-9]+}', 'EtapeController@getRecipeEtapes');


$router->group([

    'middleware' => 'auth',
    'prefix' => 'auth'

], function ($router) {

    $router->post('logout', 'AuthController@logout');
    $router->post('refresh', 'AuthController@refresh');
    $router->get('me', 'AuthController@me');

});


/*
 * Route for panier
 */

$router->get('paniers', 'PanierController@getAll');