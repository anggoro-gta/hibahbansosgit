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

class UsulanBansos extends BaseController
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
            'tittle' => 'Usulan Bansos'
        ];
        
        return view('usulan/bansos/index', $data);
    }

    public function datatable()
    {
        try {
            if (!$this->request->isAJAX()) {
                return $this->response->setStatusCode(400)->setJSON(['data'=>[]]);
            }

            $draw   = (int) $this->request->getPost('draw');
            $start  = (int) $this->request->getPost('start');   // offset
            $length = (int) $this->request->getPost('length');  // limit
            $search = $this->request->getPost('search')['value'] ?? '';
            $orderReq = $this->request->getPost('order')[0] ?? null; // column index & dir
            $kodeOpd = $this->kode_user;
            $tahun = $_SESSION['years'];

            // mapping index kolom -> nama kolom di DB
            $orderCols = ['b.nama', 'alamat_full', 'apbd', 'perubahan_perbup_1', 'perubahan_perbup_2', 'papbd'];
            $orderBy = $orderCols[$orderReq['column'] - 1] ?? 'a.id'; // -1 karena kolom nomor urut
            $orderDir = ($orderReq['dir'] ?? 'asc') === 'desc' ? 'DESC' : 'ASC';

            $recordsTotal    = $this->bansos_model->count_all_usulan($kodeOpd, $tahun);                          // total baris (tanpa search)
            $recordsFiltered = $this->bansos_model->count_filtered_usulan($kodeOpd, $tahun, $search);            // total setelah search
            $rows            = $this->bansos_model->get_page_usulan($kodeOpd, $tahun, $search, $orderBy, $orderDir, $length, $start);

            $data = [];
            foreach ($rows as $r) {
                
                $btn = ' <a href="'.base_url('usulan/bansos/edit/'.$r['id']).'" class="btn btn-sm btn-primary mb-1"><i class="fa fa-edit"></i></a>
                            <a href="'.base_url('usulan/bansos/delete/'.$r['id']).'" class="btn btn-sm btn-danger mb-1" onclick="return confirmDelete(\''.base_url('usulan/bansos/delete/'.$r['id']).'\')"><i class="fa fa-trash"></i></a>';

                $data[] = [
                    'nama'               => $r['nama'] ?? '-',
                    'alamat'             => $r['alamat_full'],
                    'apbd'               => $r['apbd'],
                    'perubahan_perbup_1' => $r['perubahan_perbup_1'],
                    'perubahan_perbup_2' => $r['perubahan_perbup_2'],
                    'papbd'              => $r['papbd'],
                    'action'             => $btn,
                ];
            }

            return $this->response->setJSON([
                'draw'            => $draw,
                'recordsTotal'    => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data'            => $data,
                'csrf'            => function_exists('csrf_hash') ? csrf_hash() : null,
            ]);
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    public function create()
    {
        if(!isset($_SESSION['years'])){
            return redirect()->to('/usulan/bansos');
        }
        
        $tahun = $_SESSION['years'];

        $data = [
            'url'    => site_url('usulan/bansos/store'),
            'button' => 'Tambah',
            'tittle' => 'Tambah Usulan Bansos',
            'ref_opd' => $this->bansos_model->get_all_opd()
        ];
        
        return view('usulan/bansos/form', $data);
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

            // contoh insert ke tb_usulan_bansos
            $tahun = $_SESSION['years'];

            $batchData = [];
            foreach ($ids as $msBansosId) {
                $batchData[] = [
                    'fk_ms_bansos_id' => $msBansosId,
                    'tahun'          => $tahun,
                    'created_by'     => $userId 
                ];
            }

            // insert batch
            $db->table('tb_usulan_bansos')->insertBatch($batchData);

            if ($db->transStatus() === false) {
                throw new \RuntimeException('DB transaction failed');
            }
            $db->transCommit();

            session()->setFlashdata('success', 'Berhasil insert data');
            return redirect()->to('/usulan/bansos');

        } catch (\Throwable $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal menyimpan: ' . $e->getMessage());
            return redirect()->to('/usulan/bansos');
        }
        
    }

    public function edit($id)
    {
        $row = $this->bansos_model->get_usulan_by_id($id);

        $data = [
            'url'                => site_url('usulan/bansos/update'),
            'button'             => 'Edit',
            'tittle'             => 'Edit Usulan Bansos',
            'id'                 => old('id', $row->id),
            'tahun'              => old('tahun', $row->tahun),
            'nik'                => old('nik', $row->nik),
            'nama'               => old('nama', $row->nama),
            'apbd'               => old('apbd', $row->apbd),
            'perubahan_perbup_1' => old('perubahan_perbup_1', $row->perubahan_perbup_1),
            'perubahan_perbup_2' => old('perubahan_perbup_2', $row->perubahan_perbup_2),
            'papbd'              => old('papbd', $row->papbd)
        ];
        

        return view('usulan/bansos/form', $data);
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

            $db->table('tb_usulan_bansos')->where('id', $id)->update($data);

            if ($db->transStatus() === false) {
                throw new \RuntimeException('DB transaction failed');
            }
            $db->transCommit();

            session()->setFlashdata('success', 'Berhasil update data');
            return redirect()->to('/usulan/bansos');

        } catch (\Throwable $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal update: ' . $e->getMessage());
            return redirect()->to('/usulan/bansos');
        }
        
    }

    public function delete($id)
    {
        $db  = \Config\Database::connect();
        $db->transBegin();

        try {

            $row = $this->bansos_model->get_usulan_by_id($id);

            if($row){
                $db->table('tb_usulan_bansos')->where('id', $id)->delete();
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('DB transaction failed');
            }
            $db->transCommit();

            session()->setFlashdata('success', 'Berhasil hapus data');
            return redirect()->to('/usulan/bansos');

        } catch (\Throwable $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal hapus: ' . $e->getMessage());
            return redirect()->to('/usulan/bansos');
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

    public function layakUsulanJson()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400);
        }

        $tahun = $_SESSION['years'];

        $db      = \Config\Database::connect();
        $builder = $db->table('ms_bansos a'); // sesuaikan nama tabel

        $builder->select('a.id, a.nama, a.alamat,
                        kab.nama_kabupaten, kec.nama_kecamatan, des.nama_desa,
                        a.nik, opd.nama_opd, opd.kode_opd')
                ->join('ms_kabupaten kab', 'a.fk_kabupaten_id = kab.id', 'left')
                ->join('ms_kecamatan kec', 'a.fk_kecamatan_id = kec.id', 'left')
                ->join('ms_desa des', 'a.fk_desa_id = des.id', 'left')
                ->join('ms_opd opd', 'a.kode_opd = opd.kode_opd', 'left');

        $builder->where("
            NOT EXISTS (
                SELECT 1
                FROM tb_usulan_bansos u
                WHERE u.fk_ms_bansos_id = a.id
                AND u.tahun IN ('$tahun')
            )
        ", null, false);

        // filter kode_opd dari select
        $kodeOpd = $this->request->getPost('kode_opd');
        if ($kodeOpd && $kodeOpd !== 'all') {
            $builder->where('opd.kode_opd', $kodeOpd);
        }

        // parameter DataTables
        $draw   = (int) $this->request->getPost('draw');
        $start  = (int) $this->request->getPost('start');
        $length = (int) $this->request->getPost('length');
        $search = $this->request->getPost('search')['value'] ?? '';

        // total sebelum filter
        $totalQuery = clone $builder;
        $recordsTotal = $totalQuery->countAllResults(false);

        // search global
        if ($search !== '') {
            $builder->groupStart()
                    ->like('a.nama', $search)
                    ->orLike('a.nik', $search)
                    ->orLike('a.alamat', $search)
                    ->orLike('kab.nama_kabupaten', $search)
                    ->orLike('kec.nama_kecamatan', $search)
                    ->orLike('des.nama_desa', $search)
                    ->orLike('opd.nama_opd', $search)
                    ->groupEnd();
        }

        // total setelah filter
        $filteredQuery   = clone $builder;
        $recordsFiltered = $filteredQuery->countAllResults(false);

        // paging
        if ($length != -1) {
            $builder->limit($length, $start);
        }

        $rows = $builder->get()->getResultArray();

        $data = [];
        foreach ($rows as $row) {
            $data[] = [
                'id'       => $row['id'],
                'nama'     => $row['nama'] . '<br><span class = "text-sm text-info">'
                            . $row['nama_kabupaten'].', '.$row['nama_kecamatan'].', '
                            . $row['nama_desa'].', '.$row['alamat'].'</span>',
                'nik'      => $row['nik'],
                'nama_opd' => $row['nama_opd']
            ];
        }

        return $this->response->setJSON([
            'draw'            => $draw,
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data,
        ]);
    }
}
