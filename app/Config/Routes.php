<?php

use CodeIgniter\Router\RouteCollection;
use Config\Services;

/**
 * @var RouteCollection $routes
 */

$routes = Services::routes();

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Employee');
$routes->setDefaultMethod('login');
$routes->setTranslateURIDashes(true);
$routes->set404Override();
$routes->setAutoRoute(true);

//Root or Just Public Route
$routes->get('/', 'Employee::login');

//Employee Route
$routes->get('employee', 'Employee::index');
$routes->get('employee/logout', 'Employee::logout');
$routes->match(['get', 'post'], 'employee/login', 'Employee::login');
$routes->match(['get', 'post'], 'employee/home', 'Employee::home');

//Home Route
$routes->get('home', 'Home::index');

// ACL Controller's Routes
$routes->get('acl/manage-permissions', 'Acl::managePermissions');
$routes->post('acl/update-permission', 'Acl::updatePermission');
$routes->match(['get', 'post'], 'acl/add-global-action', 'Acl::addGlobalAction');
