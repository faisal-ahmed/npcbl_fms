<?php

use CodeIgniter\Router\RouteCollection;
use Config\Services;

/**
 * @var RouteCollection $routes
 */

$routes = Services::routes();

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Hrm');
$routes->setDefaultMethod('login');
$routes->setTranslateURIDashes(true);
$routes->set404Override();
$routes->setAutoRoute(true);

// Public API
$routes->get('npcbl-personnel/verify', 'Api::nppPersonnelVerification');

// Root Route
$routes->get('/', 'Hrm::login');

// Hrm Controller's Routes
$routes->get('hrm', 'Hrm::index');
$routes->match(['get', 'post'], 'hrm/login', 'Hrm::login');
$routes->match(['get', 'post'], 'hrm/register', 'Hrm::register');
$routes->match(['get', 'post'], 'hrm/forget-password', 'Hrm::forgetPassword');

// Home Controller's Routes
$routes->get('home', 'Home::index');
$routes->get('home/logout', 'Home::logout');
$routes->post('home/export-supervisor-approver', 'Home::exportSupervisorApproverExcel');
$routes->match(['get', 'post'], 'home/update-password', 'Home::updatePassword');
$routes->match(['get', 'post'], 'home/personal_info', 'Home::personalInfo');
$routes->match(['get', 'post'], 'home/manual-leave-entry', 'Home::manualLeaveEntry');
$routes->match(['get', 'post'], 'home/update-user-profile', 'Home::updateUserProfile');
$routes->match(['get', 'post'], 'home/ajaxGetUserLeaveProfile', 'Home::ajaxGetUserLeaveProfile');
$routes->match(['get', 'post'], 'home/supervisor-approver-list', 'Home::supervisorApproverList');
$routes->match(['get', 'post'], 'home/my-team', 'Home::myTeamMembers');

// Leave Controller's Routes
$routes->get('leave', 'Leave::index');
$routes->get('leave/export_csv', 'Leave::export_csv');
$routes->match(['get', 'post'], 'leave/apply-for-cl', 'Leave::applyForCL');
$routes->match(['get', 'post'], 'leave/apply-for-others', 'Leave::applyForOthers');
$routes->match(['get', 'post'], 'leave/leave-report', 'Leave::leaveReport');
$routes->match(['get', 'post'], 'leave/alternative', 'Leave::alternative');
$routes->match(['get', 'post'], 'leave/statistics', 'Leave::statistics');
$routes->match(['get', 'post'], 'leave/leave-approval', 'Leave::leaveApproval');
$routes->match(['get', 'post'], 'leave/approval-archive', 'Leave::leaveApprovalArchive');
$routes->match(['get', 'post'], 'leave/set-approver', 'Leave::setLeaveApprover');

// ACL Controller's Routes
$routes->get('acl/manage-permissions', 'Acl::managePermissions');
$routes->post('acl/update-permission', 'Acl::updatePermission');
$routes->match(['get', 'post'], 'acl/add-global-action', 'Acl::addGlobalAction');

// Tax Controller's Routes
$routes->get('tax/my-tax', 'Tax::index');
$routes->post('tax/export-excel', 'Tax::exportExcel');
$routes->match(['get', 'post'], 'tax/add-tax-return', 'Tax::addRecord');
$routes->match(['get', 'post'], 'tax/all-tax-return', 'Tax::allRecords');
$routes->match(['get', 'post'], 'tax/update-tax-record/(:num)', 'Tax::updateRecord/$1');