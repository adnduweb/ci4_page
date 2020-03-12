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


$routes->group(CI_SITE_AREA, ['namespace' => '\Spreadaurora\ci4_page\Controllers\Admin'], function ($routes) {

    $routes->get('(:num)/(:any)/pages', 'AdminPagesController::renderViewList', ['as' => 'page-index']);
    $routes->get('(:num)/(:any)/pages/edit/(:any)', 'AdminPagesController::renderForm/$3');
    $routes->post('(:num)/(:any)/pages/edit/(:any)', 'AdminPagesController::postProcess/$3');
    $routes->get('(:num)/(:any)/pages/add', 'AdminPagesController::renderForm');
    $routes->post('(:num)/(:any)/pages/add', 'AdminPagesController::postProcess');
});

$routes->group('', ['namespace' => '\Spreadaurora\ci4_page\Controllers\Front'], function ($routes) {

    $locale = '';
if (service('Settings')->setting_activer_multilangue == true) {
    $locale = '/{locale}';
} 


    $routes->get($locale . '/(:segment)', 'FrontPagesController::show/$1');
});