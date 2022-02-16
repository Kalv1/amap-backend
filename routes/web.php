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
$router->get('contributeurs', 'ContributeurController@getAll');
$router->get('contributeurs/suivis/{id:[0-9]+}', 'ContributeurController@getSuivis');
$router->get('contributeur/{id:[0-9]+}', 'ContributeurController@getContributeur');
$router->get('contributeur/{id:[0-9]+}/recettes', 'ContributeurController@getRecettes');
$router->put('contributeur/{idSuiveur:[0-9]+}/suivre/{idSuivi:[0-9]+}', 'ContributeurController@suivre');
$router->put('contributeur/{idSuiveur:[0-9]+}/unfollow/{idSuivi:[0-9]+}', 'ContributeurController@nePlusSuivre');


/*
 * Route for products
*/

$router->get('recette/produits/{id:[0-9]+}', 'ProduitRecetteController@getProduitsRecette');

/*
 * Route for ustensile
*/

$router->get('recette/ustensiles/{id:[0-9]+}', 'UstensileRecetteController@getUstensilesRecette');


/*
 * Route for etape
*/

$router->get('recette/etapes/{id:[0-9]+}', 'EtapeController@getRecipeEtapes');

$router->group([

    'middleware' => 'auth',
    'prefix' => 'auth'

], function ($router) {

    $router->post('ustensilerecette', 'UstensileRecetteController@addUstensile');
    $router->post('produitrecette', 'ProduitRecetteController@addProduit');
    $router->post('etape', 'EtapeController@addEtape');
    $router->post('recette', 'RecetteController@create');
    $router->post('logout', 'AuthController@logout');
    $router->post('refresh', 'AuthController@refresh');
    $router->get('me', 'AuthController@me');

});

/*
 * Route for Topic
 */

$router->get('topics', 'TopicController@getAll');

/*
 * Route for User
 */

$router->get('users', 'UserController@getUsers');
$router->get('users/{id:[0-9]+}', 'UserController@getUser');
$router->get('users/{id:[0-9]+}/avis[/]', 'UserController@getUserAvis');
$router->get('users/{id:[0-9]+}/topics[/]', 'UserController@getUserTopics');
$router->get('users/{id:[0-9]+}/expertises[/]', 'UserController@getUserExpertises');
$router->put('users/{id:[0-9]+}[/]', 'UserController@putUser');

