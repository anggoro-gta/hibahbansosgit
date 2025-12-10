<?php

namespace App\Controllers;

use App\Models\BansosModel;
use App\Models\DesaModel;
use App\Models\HibahModel;
use Myth\Auth\Password;
use App\Models\usersModel;

class Home extends BaseController
{
    protected $usersModel;
    // protected $tbusulanmusren;
    protected $hibahmodel;
    protected $bansosmodel;
    protected $desamodel;

    public function __construct()
    {
        $this->usersModel = new usersModel();
        $this->hibahmodel = new HibahModel();
        $this->bansosmodel = new BansosModel();
        $this->desamodel = new DesaModel();
    }

    public function index()
    {
        if (isset($_SESSION['years'])) {
            $tahun = $_SESSION['years'];

            if (in_groups('admin')) {
                $jumlahusulanhibah = $this->hibahmodel->countallusulanhibahbytahun($tahun);
                $jumlahusulanbansos = $this->bansosmodel->countallusulanbansosbytahun($tahun);
                $jumlahusulanbkk = $this->desamodel->countallusulanbkkbytahun($tahun);

                $data = [
                    'jumlahusulanhibah' => $jumlahusulanhibah,
                    'jumlahusulanbansos' => $jumlahusulanbansos,
                    'jumlahusulanbkk' => $jumlahusulanbkk,
                    'tittle' => 'Home',
                ];

                return view('pages/homenew', $data);
            } else {
                $userId = user()->id;
                $jumlahusulanhibahid = $this->hibahmodel->countallusulanhibahbytahundanuserid($tahun, $userId);
                $jumlahusulanbansosid = $this->bansosmodel->countallusulanbansosbytahundanuserid($tahun, $userId);
                $jumlahusulanbkkid = $this->desamodel->countallusulanbkkbytahundanuserid($tahun, $userId);

                $data = [
                    'jumlahusulanhibah' => $jumlahusulanhibahid,
                    'jumlahusulanbansos' => $jumlahusulanbansosid,
                    'jumlahusulanbkk' => $jumlahusulanbkkid,
                    'tittle' => 'Home',
                ];

                return view('pages/homenew', $data);
            }
        } else {
            $data = [
                'tittle' => 'Home',
            ];

            return view('pages/homenew', $data);
        }
    }

    public function indexusers()
    {
        // $datausers = $this->usersModel->findAll();

        $data = [
            'tittle' => 'Users'
        ];

        return view('admin/indexusers', $data);
    }

    public function datatableusers()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['data' => []]);
        }

        $rows = $this->usersModel->getall();

        // Bentuk array untuk DataTables (paling gampang: array of arrays)
        $data = [];
        $no = 1;
        foreach ($rows as $r) {
            $data[] = [
                'id'            => (int)($r['id'] ?? 0),
                'username'   => $r['username'] ?? '-',
                'nama' => $r['fullname'] ?? '-',
            ];
        }

        // Jika CSRF aktif & regenerate, kirim token baru (opsional)
        $resp = ['data' => $data];
        if (function_exists('csrf_hash')) $resp['csrf'] = csrf_hash();

        return $this->response->setJSON($resp);
    }

    public function gantipasswordbyadmin($id)
    {
        date_default_timezone_set('Asia/Jakarta');

        $getfullname = $this->usersModel->getfullname($id);

        $data = [
            'tittle' => 'reset password users',
            'validation' => \Config\Services::validation(),
            'iddata' => $id,
            'fullname' => $getfullname
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
