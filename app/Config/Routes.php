<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/home/showBand', 'Home::showBand');
$routes->get('beacons/view/(:num)', 'Beacons::view/$1');
