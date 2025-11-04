<?php

namespace App\Controllers;

use Myth\Auth\Password;
use App\Models\BansosModel;
use App\Models\KabupatenModel;
use App\Models\KecamatanModel;
use App\Models\DesaModel;
use App\Models\ProgramModel;
use App\Models\KegiatanModel;
use App\Models\SubKegiatanModel;
use \Dompdf\Dompdf;

class MasterBansos extends BaseController
{
    protected $bansos_model;
    protected $kab_model;
    protected $kec_model;
    protected $desa_model;
    protected $program_model;
    protected $kegiatan_model;
    protected $sub_kegiatan_model;
    
    protected $dompdf;
    protected $is_opd;
    protected $kode_user;

    public function __construct()
    {
        $this->bansos_model = new BansosModel();
        $this->kab_model = new KabupatenModel();
        $this->kec_model = new KecamatanModel();
        $this->desa_model = new DesaModel();
        $this->program_model = new ProgramModel();
        $this->kegiatan_model = new KegiatanModel();
        $this->sub_kegiatan_model = new SubKegiatanModel();

        $this->dompdf = new Dompdf();

        $this->is_opd = false;
        if (in_array('useropd', user()->getRoles(), true)) {
            $this->is_opd = true;
        }
        $this->kode_user = null;
        if($this->is_opd){
            $this->kode_user = user()->kode_user;
        }
    }

    public function index()
    {
        $data = [
            'tittle' => 'Master Bansos'
        ];
        
        return view('master/bansos/index', $data);
    }

    public function datatable()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['data'=>[]]);
        }

        $rows = $this->bansos_model->get_all($this->kode_user);

        // Bentuk array untuk DataTables (paling gampang: array of arrays)
        $data = [];
        $no = 1;
        foreach ($rows as $r) {
            $alamat = $r['nama_kabupaten'].', '.$r['nama_kecamatan'].', '.$r['nama_desa'].', '.$r['alamat'];
            $data[] = [
                'id'     => (int)($r['id'] ?? 0),
                'nik'    => $r['nik'] ?? '-',
                'nama'   => $r['nama'] ?? '-',
                'alamat' => $alamat,
            ];
        }

        // Jika CSRF aktif & regenerate, kirim token baru (opsional)
        $resp = ['data' => $data];
        if (function_exists('csrf_hash')) $resp['csrf'] = csrf_hash();

        return $this->response->setJSON($resp);
    }

    public function create()
    {
        $data = [
            'url'           => site_url('master/bansos/store'),
            'button'        => 'Tambah',
            'tittle'        => 'Tambah Master Bansos',
            'id'            => old('id'),
            'nik'           => old('nik'),
            'nama'          => old('nama'),
            'kabupaten'     => old('kabupaten'),
            'kecamatan'     => old('kecamatan'),
            'desa'          => old('desa'),
            'alamat'        => old('alamat'),
            'program'       => old('program'),
            'kegiatan'      => old('kegiatan'),
            'sub_kegiatan'  => old('sub_kegiatan'),
            'ref_kabupaten' => $this->kab_model->get_all(),
            'ref_program'   => $this->program_model->get_all($this->kode_user),
            'ref_kecamatan' => [],
            'ref_desa' => [],
            'ref_kegiatan' => [],
            'ref_sub_kegiatan' => []
        ];
        
        return view('master/bansos/form', $data);
    }

    public function store(){
        $userId = user()->id ?? null;

        $now = date('Y-m-d H:i:s');
        $db  = \Config\Database::connect();
        $db->transBegin();

        try {
            $data = [
                'nik'                => $this->request->getPost('nik'),
                'nama'               => $this->request->getPost('nama'),
                'fk_kabupaten_id'    => $this->request->getPost('kabupaten'),
                'fk_kecamatan_id'    => $this->request->getPost('kecamatan'),
                'fk_desa_id'         => $this->request->getPost('desa'),
                'fk_program_id'      => $this->request->getPost('program'),
                'fk_kegiatan_id'     => $this->request->getPost('kegiatan'),
                'fk_sub_kegiatan_id' => $this->request->getPost('sub_kegiatan'),
                'alamat'             => $this->request->getPost('alamat'),
                'kode_opd'           => $this->kode_user,
                'created_at'         => $now,
                'created_by'         => $userId
            ];

            $db->table('ms_bansos')->insert($data);

            if ($db->transStatus() === false) {
                throw new \RuntimeException('DB transaction failed');
            }
            $db->transCommit();

            session()->setFlashdata('success', 'Berhasil insert data');
            return redirect()->to('/master/bansos');

        } catch (\Throwable $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal menyimpan: ' . $e->getMessage());
            return redirect()->to('/master/bansos');
        }
        
    }

    public function edit($id)
    {
        $row = $this->bansos_model->get_by_id($id);

        $data = [
            'url'           => site_url('master/bansos/update'),
            'button'        => 'Edit',
            'tittle'        => 'Edit Master Bansos',
            'id'            => old('id', $row->id),
            'nik'           => old('nik', $row->nik),
            'nama'          => old('nama', $row->nama),
            'kabupaten'     => old('kabupaten', $row->fk_kabupaten_id),
            'kecamatan'     => old('kecamatan', $row->fk_kecamatan_id),
            'desa'          => old('desa', $row->fk_desa_id),
            'alamat'        => old('alamat', $row->alamat),
            'program'       => old('program', $row->fk_program_id),
            'kegiatan'      => old('kegiatan', $row->fk_kegiatan_id),
            'sub_kegiatan'  => old('sub_kegiatan', $row->fk_sub_kegiatan_id),
            'ref_kabupaten' => $this->kab_model->get_all(),
            'ref_program'   => $this->program_model->get_all($this->kode_user),
            'ref_kecamatan' => $this->kec_model->get_all($row->fk_kabupaten_id),
            'ref_desa' => $this->desa_model->get_all($row->fk_kecamatan_id),
            'ref_kegiatan' => $this->kegiatan_model->get_all($row->fk_program_id),
            'ref_sub_kegiatan' => $this->sub_kegiatan_model->get_all($row->fk_kegiatan_id)
        ];
        

        return view('master/bansos/form', $data);
    }

    public function update(){
        $userId = user()->id ?? null;

        $now = date('Y-m-d H:i:s');
        $db  = \Config\Database::connect();
        $db->transBegin();

        $id = $this->request->getPost('id');

        try {
            $data = [
                'nik'                => $this->request->getPost('nik'),
                'nama'               => $this->request->getPost('nama'),
                'fk_kabupaten_id'    => $this->request->getPost('kabupaten'),
                'fk_kecamatan_id'    => $this->request->getPost('kecamatan'),
                'fk_desa_id'         => $this->request->getPost('desa'),
                'fk_program_id'      => $this->request->getPost('program'),
                'fk_kegiatan_id'     => $this->request->getPost('kegiatan'),
                'fk_sub_kegiatan_id' => $this->request->getPost('sub_kegiatan'),
                'alamat'             => $this->request->getPost('alamat'),
                'updated_at'         => $now,
                'updated_by'         => $userId
            ];

            $db->table('ms_bansos')->where('id', $id)->update($data);

            if ($db->transStatus() === false) {
                throw new \RuntimeException('DB transaction failed');
            }
            $db->transCommit();

            session()->setFlashdata('success', 'Berhasil update data');
            return redirect()->to('/master/bansos');

        } catch (\Throwable $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal update: ' . $e->getMessage());
            return redirect()->to('/master/bansos');
        }
        
    }

    public function delete($id)
    {
        $db  = \Config\Database::connect();
        $db->transBegin();

        try {

            $row = $this->bansos_model->get_by_id($id);

            if($row){
                $db->table('ms_bansos')->where('id', $id)->delete();
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('DB transaction failed');
            }
            $db->transCommit();

            session()->setFlashdata('success', 'Berhasil hapus data');
            return redirect()->to('/master/bansos');

        } catch (\Throwable $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal hapus: ' . $e->getMessage());
            return redirect()->to('/master/bansos');
        }
    }

    public function getKecamatan($kab_id)
    {
        $data = $this->kec_model->get_all($kab_id); // Pastikan method get_all menerima $kab_id sebagai filter

        // Format data untuk select2
        $result = [];
        foreach ($data as $item) {
            $result[] = [
                'id' => $item['id'],
                'text' => $item['nama_kecamatan'],
            ];
        }

        return $this->response->setJSON(['results' => $result]);
    }

    public function getDesa($kec_id)
    {

        $data = $this->desa_model->get_all($kec_id); // Pastikan method get_all menerima $kab_id sebagai filter

        // Format data untuk select2
        $result = [];
        foreach ($data as $item) {
            $result[] = [
                'id' => $item['id'],
                'text' => $item['nama_desa'],
            ];
        }

        return $this->response->setJSON(['results' => $result]);
    }

    public function getKegiatan($program_id)
    {

        $data = $this->kegiatan_model->get_all($program_id); // Pastikan method get_all menerima $kab_id sebagai filter

        // Format data untuk select2
        $result = [];
        foreach ($data as $item) {
            $result[] = [
                'id' => $item['id'],
                'text' => $item['nama_kegiatan'],
            ];
        }

        return $this->response->setJSON(['results' => $result]);
    }

    public function getSubKegiatan($kegiatan_id)
    {

        $data = $this->sub_kegiatan_model->get_all($kegiatan_id); // Pastikan method get_all menerima $kab_id sebagai filter

        // Format data untuk select2
        $result = [];
        foreach ($data as $item) {
            $result[] = [
                'id' => $item['id'],
                'text' => $item['nama_sub_kegiatan'],
            ];
        }

        return $this->response->setJSON(['results' => $result]);
    }

    public function cekNik()
    {
        try {
            // Ambil NIK dari request POST
            $nik = $this->request->getPost('nik');
            $id = $this->request->getPost('id') ?? null;

            $query = $this->bansos_model->cek_nik($nik, $id);

            return $this->response->setJSON(['exists' => $query]);
        } catch (\Throwable $th) {
            //throw $th;
            echo $th->getMessage();
        }
    }

    public function detailJson()
    {
        try {
            $this->response->setHeader('Content-Type', 'application/json');
            if (strtolower($this->request->getMethod()) !== 'post') {
                return $this->response->setJSON(['error' => 'Invalid method']);
            }

            $id = $this->request->getPost('id');
            $csrf = csrf_hash();

            $row = $this->bansos_model->get_by_id($id);

            return $this->response->setJSON(['csrf' => $csrf, 'data' => $row]);
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }
}
