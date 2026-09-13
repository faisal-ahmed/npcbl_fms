<?php

use CodeIgniter\Router\RouteCollection;
use Config\Services;

/**
 * @var RouteCollection $routes
 */

$routes = Services::routes();

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Applicants');
$routes->setDefaultMethod('login');
$routes->setTranslateURIDashes(true);
$routes->set404Override();
$routes->setAutoRoute(true);

//Root or Just Public Route
$routes->get('/', 'Applicants::login');
$routes->get('applicants', 'Applicants::index');
$routes->get('applicants/logout', 'Applicants::logout');
$routes->match(['get', 'post'], 'applicants/login', 'Applicants::login');
$routes->match(['get', 'post'], 'applicants/home', 'Applicants::home');
$routes->match(['get', 'post'], 'applicants/submitBkash', 'Applicants::submitBkash');