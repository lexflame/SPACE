<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Dashboard::index');
// $routes->get('tasks', 'Dashboard::tasks');
$routes->get('maps', 'Dashboard::maps');
$routes->get('notes', 'Dashboard::notes');
$routes->get('debugger', 'Dashboard::debugger');
$routes->get('picker', 'Dashboard::picker');

$routes->get('tasks_to',                'Tasks::index');
$routes->get('tasks/list',              'Tasks::list');
$routes->post('tasks/add',              'Tasks::add');
$routes->post('tasks/update/(:num)',    'Tasks::update/$1');
$routes->post('tasks/toggle/(:num)',    'Tasks::toggle/$1');
$routes->post('tasks/delete/(:num)',    'Tasks::delete/$1');
// $routes->post('tasks/reorder',       'Tasks::reorder'); // если реализуешь drag'n'drop порядок
$routes->post('tasks/status/(:num)',    'Tasks::change_status/$1');

$routes->get('/mapmanager',                          'Mapmanager::index');
$routes->get('/mapmanager/addMap',                   'Mapmanager::addMap');
$routes->post('/mapmanager/saveMap',                 'Mapmanager::saveMap');
$routes->post('/mapmanager/delete/(:num)',           'Mapmanager::delete/$1');
$routes->get ('/mapeditor/(:num)',                   'Mapeditor::index/$1');
$routes->get ('/mapeditor/getMarkers/(:num)',        'Mapeditor::getMarkers/$1');
$routes->get ('/mapeditor/getMarker/(:num)',         'Mapeditor::getMarker/$1');
$routes->post('/mapeditor/saveMarker',               'Mapeditor::saveMarker');
$routes->post('/mapeditor/moveMarker',               'Mapeditor::moveMarker');
$routes->get ('/mapeditor/getMarkerImages/(:num)',   'Mapeditor::getMarkerImages/$1');
$routes->get ('/mapeditor/getHistory/(:num)',        'Mapeditor::getHistory/$1');
$routes->post('/mapeditor/setLayerVisibility',       'Mapeditor::setLayerVisibility');

$routes->get('/categoryadmin',                  'Categoryadmin::index');
$routes->post('/categoryadmin/save',            'Categoryadmin::save');
$routes->post('/categoryadmin/delete/(:num)',   'Categoryadmin::delete/$1');

$routes->get ('/layeradmin/(:num)',        'Layeradmin::index/$1');
$routes->post('/layeradmin/save',          'Layeradmin::save');
$routes->post('/layeradmin/delete/(:num)', 'Layeradmin::delete/$1');

// Shield защищает сессией все пути
$routes->get('navigation', 'Navigation::index', ['filter' => 'session']);

$routes->group('api', ['filter' => 'session'], function($routes) {
    $routes->resource('routes', ['controller' => 'Api\Routes']);
    $routes->post('routes/upload', 'Api\Routes::upload');
});

$routes->get('events/routes', 'Events::routes', ['filter'=>'session']);