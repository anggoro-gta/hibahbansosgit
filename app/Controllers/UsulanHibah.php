<?php

namespace App\Controllers;

use Myth\Auth\Password;
use App\Models\HibahModel;
use App\Models\KabupatenModel;
use App\Models\KecamatanModel;
use App\Models\DesaModel;
use App\Models\ProgramModel;
use App\Models\KegiatanModel;
use App\Models\SubKegiatanModel;
use \Dompdf\Dompdf;

class UsulanHibah extends BaseController
{
    protected $hibah_model;
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
        $this->hibah_model = new HibahModel();
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
            'tittle' => 'Usulan Hibah'
        ];
        
        return view('usulan/hibah/index', $data);
    }

    public function datatable()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['data'=>[]]);
        }

        $tahun = $_SESSION['years'];

        $rows = $this->hibah_model->get_all_usulan($this->kode_user, $tahun);

        // Bentuk array untuk DataTables (paling gampang: array of arrays)
        $data = [];
        $no = 1;
        foreach ($rows as $r) {
            $alamat = $r['nama_kabupaten'].', '.$r['nama_kecamatan'].', '.$r['nama_desa'].', '.$r['alamat'];
            $data[] = [
                'id'                 => (int)($r['id'] ?? 0),
                'nama_lembaga'       => $r['nama_lembaga'] ?? '-',
                'alamat'             => $alamat,
                'apbd'               => $r['apbd'] ?? '',
                'perubahan_perbup_1' => $r['perubahan_perbup_1'] ?? '',
                'perubahan_perbup_2' => $r['perubahan_perbup_2'] ?? '',
                'papbd'              => $r['papbd'] ?? '',
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
            'url'    => site_url('usulan/hibah/store'),
            'button' => 'Tambah',
            'tittle' => 'Tambah Usulan Hibah',
            'rows'   => $this->hibah_model->get_layak_usulan($this->kode_user)
        ];
        
        return view('usulan/hibah/form', $data);
    }

    public function store(){
        $userId = user()->id ?? null;

        $now = date('Y-m-d H:i:s');
        $db  = \Config\Database::connect();
        $db->transBegin();

        try {
            $json = $this->request->getPost('selected_ids'); // string JSON dari hidden input
            $ids  = json_decode($json, true);

            // pastikan array
            if (!is_array($ids) || empty($ids)) {
                return redirect()->back()->with('error', 'Tidak ada data yang dipilih');
            }

            // contoh insert ke tb_usulan_hibah
            $tahun = date('Y');

            $batchData = [];
            foreach ($ids as $msHibahId) {
                $batchData[] = [
                    'fk_ms_hibah_id' => $msHibahId,
                    'tahun'          => $tahun,
                    'created_by'     => $userId 
                ];
            }

            // insert batch
            $db->table('tb_usulan_hibah')->insertBatch($batchData);

            if ($db->transStatus() === false) {
                throw new \RuntimeException('DB transaction failed');
            }
            $db->transCommit();

            session()->setFlashdata('success', 'Berhasil insert data');
            return redirect()->to('/usulan/hibah');

        } catch (\Throwable $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal menyimpan: ' . $e->getMessage());
            return redirect()->to('/usulan/hibah');
        }
        
    }

    public function edit($id)
    {
        $row = $this->hibah_model->get_usulan_by_id($id);

        $data = [
            'url'                => site_url('usulan/hibah/update'),
            'button'             => 'Edit',
            'tittle'             => 'Edit Usulan Hibah',
            'id'                 => old('id', $row->id),
            'tahun'              => old('tahun', $row->tahun),
            'no_akta_hukum'      => old('no_akta_hukum', $row->no_akta_hukum),
            'nama_lembaga'       => old('nama_lembaga', $row->nama_lembaga),
            'apbd'               => old('apbd', $row->apbd),
            'perubahan_perbup_1' => old('perubahan_perbup_1', $row->perubahan_perbup_1),
            'perubahan_perbup_2' => old('perubahan_perbup_2', $row->perubahan_perbup_2),
            'papbd'              => old('papbd', $row->papbd)
        ];
        

        return view('usulan/hibah/form', $data);
    }

    public function update(){
        $userId = user()->id ?? null;

        $db  = \Config\Database::connect();
        $db->transBegin();

        $id = $this->request->getPost('id');

        try {
            $apbd = $this->request->getPost('apbd');
            $perubahan_perbup_1 = $this->request->getPost('perubahan_perbup_1');
            $perubahan_perbup_2 = $this->request->getPost('perubahan_perbup_2');
            $papbd = $this->request->getPost('papbd');

            $data = [
                'apbd'               => !empty($apbd) ? $this->parseNumber($apbd) : null,
                'perubahan_perbup_1' => !empty($perubahan_perbup_1) ? $this->parseNumber($perubahan_perbup_1) : null,
                'perubahan_perbup_2' => !empty($perubahan_perbup_2) ? $this->parseNumber($perubahan_perbup_2) : null,
                'papbd'              => !empty($papbd) ? $this->parseNumber($papbd) : null,
                'updated_by'         => $userId
            ];

            $db->table('tb_usulan_hibah')->where('id', $id)->update($data);

            if ($db->transStatus() === false) {
                throw new \RuntimeException('DB transaction failed');
            }
            $db->transCommit();

            session()->setFlashdata('success', 'Berhasil update data');
            return redirect()->to('/usulan/hibah');

        } catch (\Throwable $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal update: ' . $e->getMessage());
            return redirect()->to('/usulan/hibah');
        }
        
    }

    public function delete($id)
    {
        $db  = \Config\Database::connect();
        $db->transBegin();

        try {

            $row = $this->hibah_model->get_usulan_by_id($id);

            if($row){
                $db->table('tb_usulan_hibah')->where('id', $id)->delete();
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('DB transaction failed');
            }
            $db->transCommit();

            session()->setFlashdata('success', 'Berhasil hapus data');
            return redirect()->to('/usulan/hibah');

        } catch (\Throwable $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal hapus: ' . $e->getMessage());
            return redirect()->to('/usulan/hibah');
        }
    }

    private function parseNumber($value)
    {
        // Hapus semua titik (pemisah ribuan)
        $value = str_replace('.', '', $value);

        // Ubah koma menjadi titik (untuk desimal)
        $value = str_replace(',', '.', $value);

        return $value;
    }
}
