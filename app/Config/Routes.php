<?php

use CodeIgniter\Router\Router;
use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
// $routes = Services::routes();

// Default
// $routes->get('tasks_to', 'Pages::tasks'); // страница задач

/**
 * @var RouteCollection $routes
 */
$routes->get('/',           'Dashboard::index');
$routes->get('maps',        'Dashboard::maps');
$routes->get('notes',       'Dashboard::notes');
$routes->get('debugger',    'Dashboard::debugger');
$routes->get('picker',      'Dashboard::picker');

// Task API
$routes->get   ('/tasks',               'TaskController::index');
$routes->get   ('/tasks/list',          'TaskController::list');
$routes->post  ('/tasks/create',        'TaskController::create');
$routes->post  ('/tasks/sync/(:num)',   'TaskController::sync/$1');
$routes->put   ('/tasks/update/(:num)', 'TaskController::update/$1');
$routes->delete('/tasks/delete/(:num)', 'TaskController::delete/$1');
// $routes->get('/tasks/view/(:num)', 'TaskController::view/$1'); -  отдельная задача

$routes->get('tacmap',      'Tacmap::index');
$routes->get('dev',         'Tacmap::dev');
$routes->get('tacmap/data', 'Tacmap::data');

/**
 * INTERACT
 */

$routes->get   ('/interact',             'Inter::index');
$routes->get   ('/lpage',                'Inter::lpage');
$routes->get   ('/load/(:num)/(:num)',   'Inter::wpage');
$routes->get   ('/Container/edo/',       'Inter::EdoBlock');

$routes->get   ('/disk_data',             'Disk::index');
$routes->cli   ('sync_data',              'Disk::index');
$routes->cli   ('thumd_tacmap',           'Disk::thumd_tacmap');

$routes->group('files', function($routes) {
    $routes->get('/', 'FileController::index');
    $routes->get('(:num)', 'FileController::show/$1');
    $routes->post('/', 'FileController::create');
    $routes->put('(:num)', 'FileController::update/$1');
    $routes->patch('(:num)', 'FileController::update/$1');
    $routes->delete('(:num)', 'FileController::delete/$1');
});



// $route['lpage/'] = 'Inter/lpage/';
// $route['load/(:any)/(:any)'] = 'Inter/wpage';
// $route['Container/edo/'] = 'Inter/EdoBlock/';