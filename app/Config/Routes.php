<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

//global routes
$routes->get('/', 'Home::index');
$routes->get('/gantipassword', 'Home::gantipassword');
$routes->get('/home/updatepassword', 'Home::updatepassword');

//YEARS PICK
$routes->post('/home/saveyears', 'Home::saveyears');

//REAL INDEX AFTER CHOOSE YEARS
// $routes->get('/home/realindex', 'Home::realindex');

//admin
$routes->get('/indexusers', 'Home::indexusers', ['filter' => 'role:admin']);
$routes->get('/gantipasswordbyadmin', 'Home::gantipasswordbyadmin', ['filter' => 'role:admin']);
$routes->post('/home/updatepasswordbyadmin', 'Home::updatepasswordbyid', ['filter' => 'role:admin']);
$routes->get('/indexhibah', 'Masterhibah::index', ['filter' => 'role:admin']);
$routes->get('/usulanhibahadmin', 'Usulanhibahadmin::index', ['filter' => 'role:admin']);

// Master-Bansos
$routes->get('master/bansos', 'MasterBansos::index');
$routes->match(['get', 'post'], 'master/bansos/datatable', 'MasterBansos::datatable');
$routes->get('master/bansos/create', 'MasterBansos::create');
$routes->post('master/bansos/store', 'MasterBansos::store');
$routes->get('master/bansos/edit/(:num)', 'MasterBansos::edit/$1');
$routes->post('master/bansos/update', 'MasterBansos::update');
$routes->get('master/bansos/delete/(:num)', 'MasterBansos::delete/$1');
$routes->post('master/bansos/detail-json', 'MasterBansos::detailJson');

// Master-Hibah
$routes->get('master/hibah', 'MasterHibah::index');
$routes->match(['get', 'post'], 'master/hibah/datatable', 'MasterHibah::datatable');
$routes->get('master/hibah/create', 'MasterHibah::create');
$routes->post('master/hibah/store', 'MasterHibah::store');
$routes->get('master/hibah/edit/(:num)', 'MasterHibah::edit/$1');
$routes->post('master/hibah/update', 'MasterHibah::update');
$routes->get('master/hibah/delete/(:num)', 'MasterHibah::delete/$1');
$routes->post('master/hibah/detail-json', 'MasterHibah::detailJson');

//ajax
$routes->get('master/kecamatan/(:num)', 'MasterBansos::getKecamatan/$1');
$routes->get('master/desa/(:num)', 'MasterBansos::getDesa/$1');
$routes->get('master/kegiatan/(:num)', 'MasterBansos::getKegiatan/$1');
$routes->get('master/sub-kegiatan/(:num)', 'MasterBansos::getSubKegiatan/$1');
$routes->post('master/cek_nik', 'MasterBansos::cekNik');
$routes->post('master/cek_no_akta', 'MasterHibah::cekNoAkta');

// import-excel
$routes->get('import-excel-hibah', 'ImportExcel::index_hibah');
$routes->post('import-excel-hibah/do', 'ImportExcel::doImportHibah');

$routes->get('import-excel-bansos', 'ImportExcel::index_bansos');
$routes->post('import-excel-bansos/do', 'ImportExcel::doImportBansos');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
