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
$router->get('recette/{id:[0-9]+}/similaires', 'RecetteController@getRecettesSimilaires');

$router->post('auth/login', 'AuthController@login');
$router->post('auth/register', 'AuthController@register');

/*
 * Route for Contributor
 */
$router->get('contributeur/{id:[0-9]+}', 'ContributeurController@getNameById');
$router->get('contributeurs', 'ContributeurController@getAll');
$router->get('contributeurs/suivis/{id:[0-9]+}', 'ContributeurController@getSuivis');
$router->get('contributeur/{id:[0-9]+}', 'ContributeurController@getContributeur');
$router->get('contributeur/{id:[0-9]+}/recettes', 'ContributeurController@getRecettes');
$router->post('contributeur/{idSuiveur:[0-9]+}/suivre/{idSuivi:[0-9]+}', 'ContributeurController@suivre');
$router->delete('contributeur/{idSuiveur:[0-9]+}/unfollow/{idSuivi:[0-9]+}', 'ContributeurController@nePlusSuivre');


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

$router->get('recette/ustensiles/{id:[0-9]+}', 'RecetteController@getRecetteUstensiles');


/*
 * Route for step
*/

$router->get('recette/etapes/{id:[0-9]+}', 'EtapeController@getRecipeEtapes');


/*
 * Route for ingredients
*/

$router->get('ingredients', 'ProduitController@getAll');
$router->get('panier/{id:[0-9]+}', 'PanierController@getAll');
$router->post('panier/{id:[0-9]+}/{prod:[0-9]+}', 'PanierController@addItem');
$router->delete('panier/{id:[0-9]+}/{prod:[0-9]+}', 'PanierController@removeItem');

$router->group([

    'middleware' => 'auth',
    'prefix' => 'auth'

], function ($router) {

    $router->post('etape', 'EtapeController@addEtape');
    $router->post('produitrecette', 'ProduitRecetteController@addProduit');
    $router->post('ustensilerecette', 'RecetteController@putRecetteUstencile');
    $router->post('recette', 'RecetteController@create');
    $router->post('logout', 'AuthController@logout');
    $router->post('refresh', 'AuthController@refresh');
    $router->get('me', 'AuthController@me');

});


/*
 * Route for recettes
 */

$router->delete('recettes/{id:[0-9]+}', 'RecetteController@deleteRecette');
$router->get('recettes/{id:[0-9]+}/aime', 'RecetteController@getRecetteAime');


/*
 * Route for panier
 */

$router->get('paniers', 'PanierController@getAll');


/*
 * Route for Topic
 */

$router->get('topics', 'TopicController@getAll');


/*
 * Route for Expertises
 */

$router->get('expertises', 'ExpertiseController@getAll');


/*
 * Route for User
 */

// Global user routes
$router->get('users/{id:[0-9]+}', 'UserController@getUser');
$router->put('users/{id:[0-9]+}', 'UserController@putUser');

// User's avis routes
$router->get('users/{id:[0-9]+}/avis', 'UserController@getUserAvis');

// User's topics routes
$router->get('users/{id:[0-9]+}/topics', 'UserController@getUserTopics');

// User's expertises routes
$router->get('users/{id:[0-9]+}/expertises', 'UserController@getUserExpertises');
$router->delete('users/{idUser:[0-9]+}/expertises/{idExpertise:[0-9]+}', 'UserController@deleteUserExpertise'); // Delete user's expertise (delete method doesn't work for pivot)
$router->post('users/{idUser:[0-9]+}/expertises/{idExpertise:[0-9]+}', 'UserController@postUserExpertise'); // Post user's expertise

// User's recipes routes
$router->get('users/{id:[0-9]+}/recettes', 'ContributeurController@getRecettes');

// User's like routes
$router->get('users/{idUser:[0-9]+}/liked', 'UserController@getLikedRecette');
$router->post('users/{idUser:[0-9]+}/like/{idRecette:[0-9]+}', 'UserController@likeRecette');
$router->delete('users/{idUser:[0-9]+}/dislike/{idRecette:[0-9]+}', 'UserController@dislikeRecette');

