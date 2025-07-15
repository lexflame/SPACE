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
$routes->group('api/task', function($routes) {
    $routes->get('/', 'Task::index');              // ?status=&due_date=&label=&search=
    $routes->post('create', 'Task::create');       // создать задачу
    $routes->put('update/(:num)', 'Task::update/$1'); // обновить
    $routes->delete('delete/(:num)', 'Task::delete/$1'); // удалить
});




