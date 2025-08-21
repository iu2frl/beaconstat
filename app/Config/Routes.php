<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/home/showBand', 'Home::showBand');
$routes->get('beacons/view/(:num)', 'Beacons::view/$1');

// Reports routes
$routes->get('reports', 'Reports::index');
$routes->get('reports/add/(:num)', 'Reports::add/$1');
$routes->post('reports/add/(:num)', 'Reports::add/$1');
