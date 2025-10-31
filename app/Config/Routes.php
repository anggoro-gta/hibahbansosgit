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

//musrenbang
$routes->get('/entryusulan', 'Entryusulanmusren::index', ['filter' => 'role:bidangadmin']);
$routes->get('/masterttd', 'Entryusulanmusren::masterttd', ['filter' => 'role:bidangadmin']);
$routes->get('/inputttdmaster', 'Entryusulanmusren::inputttdmaster', ['filter' => 'role:bidangadmin']);
$routes->get('/inputttddarimaster/(:any)', 'Entryusulanmusren::ambildarimasterttd/$1', ['filter' => 'role:bidangadmin']);
$routes->get('/inputttd/(:any)', 'Entryusulanmusren::inputttd/$1', ['filter' => 'role:bidangadmin']);
$routes->post('/savemasterttd', 'Entryusulanmusren::savemasterttd', ['filter' => 'role:bidangadmin']);
$routes->post('/savettd', 'Entryusulanmusren::savettd', ['filter' => 'role:bidangadmin']);
$routes->get('/saveambildarimasterttd/(:any)/(:any)', 'Entryusulanmusren::saveambildarimasterttd/$1/$2', ['filter' => 'role:bidangadmin']);
$routes->post('/deletemasterttd', 'Entryusulanmusren::deletemasterttd', ['filter' => 'role:bidangadmin']);
$routes->post('/deletettd', 'Entryusulanmusren::deletettd', ['filter' => 'role:bidangadmin']);
$routes->get('/detailusulan/(:any)', 'Entryusulanmusren::detailusulan/$1', ['filter' => 'role:bidangadmin']);
$routes->get('/detailprior/(:any)', 'Entryusulanmusren::detailprior/$1', ['filter' => 'role:bidangadmin']);
$routes->get('/detailttd/(:any)', 'Entryusulanmusren::detailttd/$1', ['filter' => 'role:bidangadmin']);
$routes->get('/nomorttd/(:any)', 'Entryusulanmusren::nomorttd/$1', ['filter' => 'role:bidangadmin']);
$routes->get('/inpunomorttd/(:any)', 'Entryusulanmusren::inputnomorttd/$1', ['filter' => 'role:bidangadmin']);
$routes->post('/savenomorttd', 'Entryusulanmusren::savenomorttd', ['filter' => 'role:bidangadmin']);
$routes->post('/deletenomorttd', 'Entryusulanmusren::deletenomorttd', ['filter' => 'role:bidangadmin']);
$routes->get('/entryusulanmusren/apigetdatamusren', 'Entryusulanmusren::apigetdatamusren', ['filter' => 'role:bidangadmin']);
$routes->get('/entryusulanmusren/apigetstatususulan', 'Entryusulanmusren::apigetstatususulan', ['filter' => 'role:bidangadmin']);
$routes->get('/entryusulanmusren/apigetdataprior', 'Entryusulanmusren::apigetdataprior', ['filter' => 'role:bidangadmin']);
$routes->get('/entryusulanmusren/apigetprior', 'Entryusulanmusren::apigetprior', ['filter' => 'role:bidangadmin']);
$routes->post('/entryusulanmusren/updatestatususulan', 'Entryusulanmusren::saveupdatestatususulan', ['filter' => 'role:bidangadmin']);
$routes->post('/entryusulanmusren/updateprior', 'Entryusulanmusren::saveupdateprior', ['filter' => 'role:bidangadmin']);
//musrenbang print ba sidang kelompok
$routes->get('/printbasidangkelompok/(:any)', 'Entryusulanmusren::printbasidangkelompok/$1', ['filter' => 'role:bidangadmin']);
$routes->get('/printlampiransdgkel/(:any)', 'Entryusulanmusren::printlampiransidangkelompok/$1', ['filter' => 'role:bidangadmin']);
$routes->get('/printlampiransdgkelx/(:any)', 'Entryusulanmusren::printlampiransidangkelompokx/$1', ['filter' => 'role:bidangadmin']);

//penandatanganan BA musren
$routes->get('/penandatanganan', 'Entryttd::index', ['filter' => 'role:bidangadmin']);
$routes->post('/penandatanganan/savepenandatanganan', 'Entryttd::savepenandatanganan', ['filter' => 'role:bidangadmin']);
$routes->post('/penandatanganan/deletepenandatanganan', 'Entryttd::deletepenandatanganan', ['filter' => 'role:bidangadmin']);

//cetak musrenbang
$routes->get('/printbamusrenbang', 'Cetakmusren::index', ['filter' => 'role:bidangadmin']);
$routes->get('/printlampiranba', 'Cetakmusren::printlamiran', ['filter' => 'role:bidangadmin']);

//usulan
$routes->get('usulan/data', 'Usulan::index');
$routes->post('usulan/datatable', 'Usulan::datatable');
$routes->post('usulan/detail-json', 'Usulan::detailJson');
$routes->get('usulan/show/(:num)', 'Usulan::show/$1');
$routes->post('usulan/update-status', 'Usulan::updateStatus');
$routes->post('usulan/foto-delete', 'Usulan::deleteFoto');
$routes->get('usulan/laporan', 'Usulan::laporan');
$routes->post('usulan/datatable-laporan', 'Usulan::datatableLaporan');
$routes->get('usulan/export-pdf', 'Usulan::exportPdf');
$routes->get('usulan/export-excel', 'Usulan::exportExcel');

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
