<?php

use CodeIgniter\Router\Router;
use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
// $routes = Services::routes();

// Default
$routes->get('tasks_to', 'Pages::tasks'); // страница задач

/**
 * @var RouteCollection $routes
 */
$routes->get('/',           'Dashboard::index');
$routes->get('maps',        'Dashboard::maps');
$routes->get('notes',       'Dashboard::notes');
$routes->get('debugger',    'Dashboard::debugger');
$routes->get('picker',      'Dashboard::picker');

// Task API
$routes->get   ('/tasks', 'TaskController::index');
$routes->get   ('/tasks/list', 'TaskController::list');
$routes->post  ('/tasks/create', 'TaskController::create');
$routes->put   ('/tasks/update/(:num)', 'TaskController::update/$1');
$routes->delete('/tasks/delete/(:num)', 'TaskController::delete/$1');
// $routes->get('/tasks/view/(:num)', 'TaskController::view/$1'); - отдельная задача



