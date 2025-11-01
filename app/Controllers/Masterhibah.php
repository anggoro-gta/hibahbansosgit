<?php

namespace App\Controllers;

use App\Models\mshibahModel;
use App\Models\usersModel;

class Masterhibah extends BaseController
{
    protected $usersModel;
    protected $mshibah;

    public function __construct()
    {
        $this->usersModel = new usersModel();
        $this->mshibah = new mshibahModel();
    }

    public function index()
    {
        $datahibah = $this->mshibah->getallhibah();        

        $data = [
            'tittle' => 'master hibah',
            'datahibah' => $datahibah
        ];

        return view('admin/indexhibah', $data);
    }

    public function editdatauser($id)
    {
        session();
        $data = [
            'tittle' => 'GTA CI siDika Edit Users',
            'validation' => \Config\Services::validation(),
            'datauser' => $this->usersModel->getuser($id)
        ];

        //jika indek tidak ada dalam database
        if (empty($data['datauser'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tersebut : ' . $id . ' Tidak ada dalam database');
        }

        return view('datamaster/useredit', $data);
    }

    public function update($id)
    {
        date_default_timezone_set('Asia/Jakarta');
        $usernamelama = $this->request->getVar('usernamelama');
        $emaillama = $this->request->getVar('emaillama');

        //cek username
        if ($usernamelama == $this->request->getVar('username')) {
            $rule_username = 'required';
        } else {
            $rule_username = 'required|is_unique[users.username]';
        }

        //cek email
        //cek username
        if ($emaillama == $this->request->getVar('email')) {
            $rule_email = 'required';
        } else {
            $rule_email = 'required|is_unique[users.email]';
        }

        // validation data update
        if (!$this->validate([
            'username' => [
                'rules' => $rule_username,
                'errors' => [
                    'is_unique' => 'username sudah ada dalam database cari nama username lain.'
                ]
            ],
            'email' => [
                'rules' => $rule_email,
                'errors' => [
                    'is_unique' => 'email sudah ada dalam database gunakan email lain.'
                ]
            ]
        ])) {
            // $validation = \Config\Services::validation();
            // return redirect()->to('komik/edit/' . $this->request->getVar('slug'))->withInput()->with('validation', $validation);
            return redirect()->to('masterusers/' . $this->request->getVar('id'))->withInput();
        }

        $this->usersModel->save([
            'id' => $id,
            'email' => $this->request->getVar('email'),
            'username' => $this->request->getVar('username')
        ]);

        session()->setFlashdata('pesan', 'merubah');

        return redirect()->to('/masterusers');
    }

    public function delete()
    {
        $id = $this->request->getVar('id_hidden');

        $this->usersModel->delete($id);
        session()->setFlashdata('pesan', 'hapus');
        return redirect()->to('/masterusers');
    }
}
