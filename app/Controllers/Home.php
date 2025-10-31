<?php

namespace App\Controllers;

use App\Models\tbusulanmusrenModel;
use Myth\Auth\Password;
use App\Models\usersModel;

class Home extends BaseController
{
    protected $usersModel;
    protected $tbusulanmusren;

    public function __construct()
    {
        $this->usersModel = new usersModel();
        $this->tbusulanmusren = new tbusulanmusrenModel();
    }

    public function index()
    {
        $counttotalusulan = '';
        $countakomodir = '';
        $counttdkakomodir = '';
        $countblmproses = '';

        if (isset($_SESSION['years'])) {
            $tahun = $_SESSION['years'];

            $totalusulan = $this->tbusulanmusren->gettotalusulanbytahun($tahun);
            $counttotalusulan = count($totalusulan);

            $akomodir = $this->tbusulanmusren->gettotalusulanakomodirbytahun($tahun);
            $countakomodir = count($akomodir);

            $tdkakomodir = $this->tbusulanmusren->gettotalusulantdkakomodirbytahun($tahun);
            $counttdkakomodir = count($tdkakomodir);

            $blmproses = $this->tbusulanmusren->gettotalusulanblmprosesbytahun($tahun);
            $countblmproses = count($blmproses);
        }

        $data = [
            'tittle' => 'Home',
            'counttotalusulan' => $counttotalusulan,
            'countakomodir' => $countakomodir,
            'counttdkakomodir' => $counttdkakomodir,
            'countblmproses' => $countblmproses
        ];

        return view('pages/homenew', $data);
    }

    public function indexusers()
    {
        $datausers = $this->usersModel->findAll();

        $data = [
            'tittle' => 'Users',
            'data_users' => $datausers
        ];

        return view('admin/indexusers', $data);
    }

    public function gantipasswordbyadmin()
    {
        date_default_timezone_set('Asia/Jakarta');

        $id = $this->request->getVar('iddata');        
        $fullname = $this->request->getVar('fullname');        

        $data = [
            'tittle' => 'reset password users',
            'validation' => \Config\Services::validation(),
            'iddata' => $id,
            'fullname' => $fullname
        ];

        return view('admin/gantipasswordbyadmin_view', $data);
    }

    public function updatepasswordbyid()
    {
        date_default_timezone_set('Asia/Jakarta');

        // validation data update
        if (!$this->validate([
            'password1' => [
                'rules' => 'required|min_length[3]|matches[password2]',
                'errors' => [
                    'required' => 'harus ada isinya',
                    'min_length' => 'telalu pendek tidak boleh kurang dari 3 karakter',
                    'matches' => 'tidak cocok dengan password ke dua'
                ]
            ],
            'password2' => [
                'rules' => 'required|min_length[3]|matches[password1]',
                'errors' => [
                    'required' => 'harus ada isinya',
                    'min_length' => 'telalu pendek tidak boleh kurang dari 3 karakter',
                    'matches' => 'tidak cocok dengan password ke satu'
                ]
            ]
        ])) {
            return redirect()->to('gantipasswordbyadmin')->withInput();
        }

        $password = $this->request->getVar('password1');
        $id = $this->request->getVar('iddata');

        $hash = Password::hash($password);

        $this->usersModel->updatepassbyid($hash, $id);
        session()->setFlashdata('pesan', 'updatepass');

        return redirect()->to('/indexusers');
    }

    public function saveyears()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)
                ->setJSON(['ok' => false, 'msg' => 'Bad request']);
        }

        $year = trim((string)$this->request->getPost('year'));
        if ($year === '' || !ctype_digit($year)) {
            return $this->response->setStatusCode(422)
                ->setJSON(['ok' => false, 'msg' => 'Tahun tidak valid']);
        }

        session()->set('years', (int) $year);

        $resp = ['ok' => true, 'year' => (int)$year];
        if (function_exists('csrf_hash')) $resp['csrf'] = csrf_hash(); // kalau CSRF rotate
        return $this->response->setJSON($resp);
    }

    // public function realindex()
    // {
    //     if (isset($_SESSION['years'])) {
    //         $data = [
    //             'tittle' => 'Home'
    //         ];

    //         return view('pages/homenew', $data);
    //     } else {
    //         return redirect()->to('/');
    //     }
    // }

    // public function register()
    // {
    //     $data = [
    //         'tittle' => 'Register'
    //     ];

    //     return view('auth/registerview', $data);
    // }

    public function gantipassword()
    {
        $data = [
            'tittle' => 'Ganti Password',
            'validation' => \Config\Services::validation(),
        ];

        return view('pages/gantipassword_view', $data);
    }

    public function updatepassword()
    {
        date_default_timezone_set('Asia/Jakarta');
        $kode_user_skpd = user()->kode_user;

        // validation data update
        if (!$this->validate([
            'password1' => [
                'rules' => 'required|min_length[3]|matches[password2]',
                'errors' => [
                    'required' => 'harus ada isinya',
                    'min_length' => 'telalu pendek tidak boleh kurang dari 3 karakter',
                    'matches' => 'tidak cocok dengan password ke dua'
                ]
            ],
            'password2' => [
                'rules' => 'required|min_length[3]|matches[password1]',
                'errors' => [
                    'required' => 'harus ada isinya',
                    'min_length' => 'telalu pendek tidak boleh kurang dari 3 karakter',
                    'matches' => 'tidak cocok dengan password ke satu'
                ]
            ]
        ])) {
            return redirect()->to('gantipassword')->withInput();
        }

        $password = $this->request->getVar('password1');

        $hash = Password::hash($password);

        $this->usersModel->updatepass($hash, $kode_user_skpd);
        session()->setFlashdata('pesan', 'updatepass');

        return redirect()->to('/');
    }
}
