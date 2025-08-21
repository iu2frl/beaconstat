<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Home route
$routes->get('/', 'Home::index');
// Beacons per band route
$routes->get('/home/showBand', 'Home::showBand');
// Single beacon view route
$routes->get('beacons/view/(:num)', 'Beacons::view/$1');
// Language route
$routes->get('language/switch/(:segment)', 'Language::switch/$1');
// Reports routes
$routes->get('reports', 'Reports::index');
$routes->get('reports/add/(:num)', 'Reports::add/$1');
$routes->post('reports/add/(:num)', 'Reports::add/$1');
