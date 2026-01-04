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

//landigpage routes
$routes->get('/landingpage', 'Landingpage::index');

//global routes
$routes->get('/', 'Home::index', ['filter' => 'role:admin,useropd']);
$routes->get('/gantipassword', 'Home::gantipassword', ['filter' => 'role:admin,useropd']);
$routes->get('/home/updatepassword', 'Home::updatepassword', ['filter' => 'role:admin,useropd']);

//YEARS PICK
$routes->post('/home/saveyears', 'Home::saveyears', ['filter' => 'role:admin,useropd']);

//REAL INDEX AFTER CHOOSE YEARS
// $routes->get('/home/realindex', 'Home::realindex');

//admin
$routes->get('/indexusers', 'Home::indexusers', ['filter' => 'role:admin']);
$routes->get('/gantipasswordbyadmin/(:num)', 'Home::gantipasswordbyadmin/$1', ['filter' => 'role:admin']);
$routes->post('/home/updatepasswordbyadmin', 'Home::updatepasswordbyid', ['filter' => 'role:admin']);
$routes->get('/indexhibah', 'Masterhibah::index', ['filter' => 'role:admin']);
$routes->get('/usulanhibahadmin', 'Usulanhibahadmin::index', ['filter' => 'role:admin']);
$routes->match(['get', 'post'], 'setting/users/datatable', 'Home::datatableusers', ['filter' => 'role:admin']);

//View Hibah ADMIN
$routes->get('view/hibah', 'ViewUsulanHibah::index', ['filter' => 'role:admin']);
$routes->match(['get', 'post'], 'view/hibah/datatable', 'ViewUsulanHibah::datatable', ['filter' => 'role:admin']);

// Master-Hibah
$routes->get('master/hibah', 'MasterHibah::index', ['filter' => 'role:admin,useropd']);
$routes->match(['get', 'post'], 'master/hibah/datatable', 'MasterHibah::datatable', ['filter' => 'role:admin,useropd']);
$routes->get('master/hibah/create', 'MasterHibah::create', ['filter' => 'role:admin,useropd']);
$routes->post('master/hibah/store', 'MasterHibah::store', ['filter' => 'role:admin,useropd']);
$routes->get('master/hibah/edit/(:num)', 'MasterHibah::edit/$1', ['filter' => 'role:admin,useropd']);
$routes->post('master/hibah/update', 'MasterHibah::update', ['filter' => 'role:admin,useropd']);
$routes->get('master/hibah/delete/(:num)', 'MasterHibah::delete/$1', ['filter' => 'role:admin,useropd']);
$routes->post('master/hibah/detail-json', 'MasterHibah::detailJson', ['filter' => 'role:admin,useropd']);
$routes->post('master/hibah/history-json', 'MasterHibah::historyUsulanJson', ['filter' => 'role:admin,useropd']);

// Usulan-Hibah
$routes->get('usulan/hibah', 'UsulanHibah::index', ['filter' => 'role:admin,useropd']);
$routes->match(['get', 'post'], 'usulan/hibah/datatable', 'UsulanHibah::datatable', ['filter' => 'role:admin,useropd']);
$routes->get('usulan/hibah/create', 'UsulanHibah::create', ['filter' => 'role:admin,useropd']);
$routes->post('usulan/hibah/store', 'UsulanHibah::store', ['filter' => 'role:admin,useropd']);
$routes->get('usulan/hibah/edit/(:num)', 'UsulanHibah::edit/$1', ['filter' => 'role:admin,useropd']);
$routes->post('usulan/hibah/update', 'UsulanHibah::update', ['filter' => 'role:admin,useropd']);
$routes->get('usulan/hibah/delete/(:num)', 'UsulanHibah::delete/$1', ['filter' => 'role:admin,useropd']);
$routes->post('usulan/hibah/layak-usulan-json', 'UsulanHibah::layakUsulanJson', ['filter' => 'role:admin,useropd']);

// Master-Bansos
$routes->get('master/bansos', 'MasterBansos::index', ['filter' => 'role:admin,useropd']);
$routes->match(['get', 'post'], 'master/bansos/datatable', 'MasterBansos::datatable', ['filter' => 'role:admin,useropd']);
$routes->get('master/bansos/create', 'MasterBansos::create', ['filter' => 'role:admin,useropd']);
$routes->post('master/bansos/store', 'MasterBansos::store', ['filter' => 'role:admin,useropd']);
$routes->get('master/bansos/edit/(:num)', 'MasterBansos::edit/$1', ['filter' => 'role:admin,useropd']);
$routes->post('master/bansos/update', 'MasterBansos::update', ['filter' => 'role:admin,useropd']);
$routes->get('master/bansos/delete/(:num)', 'MasterBansos::delete/$1', ['filter' => 'role:admin,useropd']);
$routes->post('master/bansos/detail-json', 'MasterBansos::detailJson', ['filter' => 'role:admin,useropd']);
$routes->post('master/bansos/history-json', 'MasterBansos::historyUsulanJson', ['filter' => 'role:admin,useropd']);

// Usulan-Bansos
$routes->get('usulan/bansos', 'UsulanBansos::index', ['filter' => 'role:admin,useropd']);
$routes->match(['get', 'post'], 'usulan/bansos/datatable', 'UsulanBansos::datatable', ['filter' => 'role:admin,useropd']);
$routes->get('usulan/bansos/create', 'UsulanBansos::create', ['filter' => 'role:admin,useropd']);
$routes->post('usulan/bansos/store', 'UsulanBansos::store', ['filter' => 'role:admin,useropd']);
$routes->get('usulan/bansos/edit/(:num)', 'UsulanBansos::edit/$1', ['filter' => 'role:admin,useropd']);
$routes->post('usulan/bansos/update', 'UsulanBansos::update', ['filter' => 'role:admin,useropd']);
$routes->get('usulan/bansos/delete/(:num)', 'UsulanBansos::delete/$1', ['filter' => 'role:admin,useropd']);
$routes->post('usulan/bansos/layak-usulan-json', 'UsulanBansos::layakUsulanJson', ['filter' => 'role:admin,useropd']);

// Master-BKK
$routes->get('master/bkk', 'MasterBkk::index', ['filter' => 'role:admin,useropd']);
$routes->match(['get', 'post'], 'master/bkk/datatable', 'MasterBkk::datatable', ['filter' => 'role:admin,useropd']);
$routes->get('master/bkk/create', 'MasterBkk::create', ['filter' => 'role:admin,useropd']);
$routes->post('master/bkk/store', 'MasterBkk::store', ['filter' => 'role:admin,useropd']);
$routes->get('master/bkk/edit/(:num)', 'MasterBkk::edit/$1', ['filter' => 'role:admin,useropd']);
$routes->post('master/bkk/update', 'MasterBkk::update', ['filter' => 'role:admin,useropd']);
$routes->get('master/bkk/delete/(:num)', 'MasterBkk::delete/$1', ['filter' => 'role:admin,useropd']);
$routes->post('master/bkk/detail-json', 'MasterBkk::detailJson', ['filter' => 'role:admin,useropd']);
$routes->post('master/bkk/history-json', 'MasterBkk::historyUsulanJson', ['filter' => 'role:admin,useropd']);

// Usulan-BKK
$routes->get('usulan/bkk', 'UsulanBkk::index', ['filter' => 'role:admin,useropd']);
$routes->match(['get', 'post'], 'usulan/bkk/datatable', 'UsulanBkk::datatable', ['filter' => 'role:admin,useropd']);
$routes->get('usulan/bkk/create', 'UsulanBkk::create', ['filter' => 'role:admin,useropd']);
$routes->post('usulan/bkk/store', 'UsulanBkk::store', ['filter' => 'role:admin,useropd']);
$routes->get('usulan/bkk/edit/(:num)', 'UsulanBkk::edit/$1', ['filter' => 'role:admin,useropd']);
$routes->post('usulan/bkk/update', 'UsulanBkk::update', ['filter' => 'role:admin,useropd']);
$routes->get('usulan/bkk/delete/(:num)', 'UsulanBkk::delete/$1', ['filter' => 'role:admin,useropd']);
$routes->post('usulan/bkk/layak-usulan-json', 'UsulanBkk::layakUsulanJson', ['filter' => 'role:admin,useropd']);

// SIPD-Bansos
$routes->get('sipd/bansos', 'SipdBansos::index', ['filter' => 'role:admin,useropd']);
$routes->post('sipd/bansos/export-excel', 'SipdBansos::exportExcel', ['filter' => 'role:admin,useropd']);

//ajax
$routes->get('master/kecamatan/(:num)', 'MasterBansos::getKecamatan/$1', ['filter' => 'role:admin,useropd']);
$routes->get('master/desa/(:num)', 'MasterBansos::getDesa/$1', ['filter' => 'role:admin,useropd']);
$routes->get('master/kegiatan/(:num)', 'MasterBansos::getKegiatan/$1', ['filter' => 'role:admin,useropd']);
$routes->get('master/sub-kegiatan/(:num)', 'MasterBansos::getSubKegiatan/$1', ['filter' => 'role:admin,useropd']);
$routes->post('master/cek_nik', 'MasterBansos::cekNik', ['filter' => 'role:admin,useropd']);
$routes->post('master/cek_no_akta', 'MasterHibah::cekNoAkta', ['filter' => 'role:admin,useropd']);

// import-excel
$routes->get('import-excel-hibah', 'ImportExcel::index_hibah', ['filter' => 'role:admin,useropd']);
$routes->post('import-excel-hibah/do', 'ImportExcel::doImportHibah', ['filter' => 'role:admin,useropd']);

$routes->get('import-excel-bansos', 'ImportExcel::index_bansos', ['filter' => 'role:admin,useropd']);
$routes->post('import-excel-bansos/do', 'ImportExcel::doImportBansos', ['filter' => 'role:admin,useropd']);


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
