<?php

$routes->group(CI_SITE_AREA, ['namespace' => '\Adnduweb\Ci4_page\Controllers\Admin', 'filter' => 'apiauth'], function ($routes) {

    $routes->get(config('Page')->urlMenuAdmin . '/pages', 'AdminPageController::renderViewList', ['as' => 'page-index']);
    $routes->get(config('Page')->urlMenuAdmin . '/pages/edit/(:any)', 'AdminPageController::renderForm/$1');
    $routes->post(config('Page')->urlMenuAdmin . '/pages/edit/(:any)', 'AdminPageController::postProcess/$1');
    $routes->get(config('Page')->urlMenuAdmin . '/pages/add', 'AdminPageController::renderForm');
    $routes->post(config('Page')->urlMenuAdmin . '/pages/add', 'AdminPageController::postProcess');
});
