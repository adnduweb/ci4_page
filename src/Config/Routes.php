<?php

// $routes->group('album', ['namespace' => 'Album\Controllers'], function ($routes) {
// 	// URI: /album
// 	$routes->get('', 'Album::index', ['as' => 'album-index']);

// 	// URI: /album/add
// 	$routes->match(['get', 'post'], 'add', 'Album::add', ['as' => 'album-add']);

// 	// example URI: /album/delete/1
// 	$routes->get('delete/(:num)', 'Album::delete/$1', ['as' => 'album-delete']);

// 	// example URI: /album/1
// 	$routes->match(['get', 'post'], 'edit/(:num)', 'Album::edit/$1', ['as' => 'album-edit']);
// });

// On définit la langue dans la route


$routes->group(CI_SITE_AREA, ['namespace' => '\Adnduweb\Ci4_page\Controllers\Admin', 'filter' => 'apiauth'], function ($routes) {

    $routes->get('(:any)/pages', 'AdminPagesController::renderViewList', ['as' => 'page-index']);
    $routes->get('(:any)/pages/edit/(:any)', 'AdminPagesController::renderForm/$2');
    $routes->post('(:any)/pages/edit/(:any)', 'AdminPagesController::postProcess/$2');
    $routes->get('(:any)/pages/add', 'AdminPagesController::renderForm');
    $routes->post('(:any)/pages/add', 'AdminPagesController::postProcess');
});

//ADN HACK;
//$routes->group('', ['namespace' => '\Adnduweb\Ci4_page\Controllers\Front'], function ($routes) {

$locale = '/';
if (service('Settings')->setting_activer_multilangue == true) {
    $locale = '/{locale}';
}
//Blog
$routes->get($locale . '/categories/(:segment)' . env('app.suffix_url'), 'FrontCategoriesController::Show/$1', ['namespace' => '\Adnduweb\Ci4_blog\Controllers\Front']);
$routes->get($locale . '/actualites/(:segment)' . env('app.suffix_url'), 'FrontArticleController::Show/$1', ['namespace' => '\Adnduweb\Ci4_blog\Controllers\Front']);

//Pages
$routes->get($locale . '/(:segment)' . env('app.suffix_url'), 'FrontPagesController::show/$1', ['namespace' => '\Adnduweb\Ci4_page\Controllers\Front']);
$routes->get($locale . '/(:segment)/(:segment)' . env('app.suffix_url'), 'FrontPagesController::show/$2', ['namespace' => '\Adnduweb\Ci4_page\Controllers\Front']);
$routes->get($locale . '/(:segment)/(:segment)/(:segment)' . env('app.suffix_url'), 'FrontPagesController::show/$3', ['namespace' => '\Adnduweb\Ci4_page\Controllers\Front']);
