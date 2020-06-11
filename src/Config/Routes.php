<?php

$routes->group(CI_SITE_AREA, ['namespace' => '\Adnduweb\Ci4_page\Controllers\Admin', 'filter' => 'apiauth'], function ($routes) {

    $routes->get('(:any)/pages', 'AdminPagesController::renderViewList', ['as' => 'page-index']);
    $routes->get('(:any)/pages/edit/(:any)', 'AdminPagesController::renderForm/$2');
    $routes->post('(:any)/pages/edit/(:any)', 'AdminPagesController::postProcess/$2');
    $routes->get('(:any)/pages/add', 'AdminPagesController::renderForm');
    $routes->post('(:any)/pages/add', 'AdminPagesController::postProcess');
});

$locale = '/';
if (service('Settings')->setting_activer_multilangue == true) {
    $locale = '/{locale}';
}
//HOME
$routes->get($locale . '/', '\App\Controllers\Front\HomeFrontController::index');
