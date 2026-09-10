<?php

use CodeIgniter\Router\RouteCollection;
use Config\Services;

/**
 * @var RouteCollection $routes
 */

$routes = Services::routes();

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Fms');
$routes->setDefaultMethod('login');
$routes->setTranslateURIDashes(true);
$routes->set404Override();
$routes->setAutoRoute(true);

// Root Route
$routes->get('/', 'Hrm::login');

// Hrm Controller's Routes
$routes->get('hrm', 'Hrm::index');
$routes->match(['get', 'post'], 'fms/login', 'Fms::login');
$routes->match(['get', 'post'], 'fms/forget-password', 'Fms::forgetPassword');

// Home Controller's Routes
$routes->get('home', 'Home::index');
$routes->get('home/logout', 'Home::logout');
$routes->match(['get', 'post'], 'home/update-password', 'Home::updatePassword');
$routes->match(['get', 'post'], 'home/personal_info', 'Home::personalInfo');
$routes->match(['get', 'post'], 'home/update-user-profile', 'Home::updateUserProfile');

// ACL Controller's Routes
$routes->get('acl/manage-permissions', 'Acl::managePermissions');
$routes->post('acl/update-permission', 'Acl::updatePermission');
$routes->match(['get', 'post'], 'acl/add-global-action', 'Acl::addGlobalAction');