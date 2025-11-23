<?php

namespace App\Controllers;

use Myth\Auth\Password;
use App\Models\KabupatenModel;
use App\Models\KecamatanModel;
use App\Models\DesaModel;
use App\Models\ProgramModel;
use App\Models\KegiatanModel;
use App\Models\SubKegiatanModel;
use \Dompdf\Dompdf;

class MasterBkk extends BaseController
{
    protected $desa_model;
    protected $kab_model;
    protected $kec_model;
    protected $program_model;
    protected $kegiatan_model;
    protected $sub_kegiatan_model;
    
    protected $dompdf;
    protected $is_opd;
    protected $kode_user;

    public function __construct()
    {
        $this->desa_model = new DesaModel();
        $this->kab_model = new KabupatenModel();
        $this->kec_model = new KecamatanModel();
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
            'tittle' => 'Master BKK',
            'ref_opd' => $this->desa_model->get_all_opd()
        ];
        
        return view('master/bkk/index', $data);
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
            $kodeOpd = $this->request->getPost('kode_opd');

            // mapping index kolom -> nama kolom di DB
            $orderCols = ['ms_desa.id','a.nama_kabupaten','b.nama_kecamatan', 'ms_desa.nama_desa'];
            $orderBy = $orderCols[$orderReq['column'] - 1] ?? 'ms_desa.id'; // -1 karena kolom nomor urut
            $orderDir = ($orderReq['dir'] ?? 'asc') === 'desc' ? 'DESC' : 'ASC';

            $recordsTotal    = $this->desa_model->count_all($kodeOpd);                          // total baris (tanpa search)
            $recordsFiltered = $this->desa_model->count_filtered($kodeOpd, $search);            // total setelah search
            $rows            = $this->desa_model->get_page($kodeOpd, $search, $orderBy, $orderDir, $length, $start);

            $data = [];
            foreach ($rows as $r) {
                $btn = '<button type="button" class="btn btn-sm btn-info mb-1 btn-detail" data-id="'.$r['id'].'" title="Taging Nomenklatur"><i class="fa fa-eye"></i></button>';
                if ($this->kode_user == $r['kode_opd']) {
                    $btn .= ' <a href="'.base_url('master/bkk/edit/'.$r['id']).'" class="btn btn-sm btn-primary mb-1"><i class="fa fa-edit"></i></a>
                            <a href="'.base_url('master/bkk/delete/'.$r['id']).'" class="btn btn-sm btn-danger mb-1" onclick="return confirmDelete(\''.base_url('master/bkk/delete/'.$r['id']).'\')"><i class="fa fa-trash"></i></a>';
                }

                $data[] = [
                    'nama_kabupaten' => $r['nama_kabupaten'] ?? '-',
                    'nama_kecamatan' => $r['nama_kecamatan'] ?? '-',
                    'nama_desa'      => $r['nama_desa'],
                    'action'         => $btn,
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
        $data = [
            'url'              => site_url('master/bkk/store'),
            'button'           => 'Tambah',
            'tittle'           => 'Tambah Master BKK',
            'id'               => old('id'),
            'nama_desa'        => old('nama_desa'),
            'kabupaten'        => old('kabupaten'),
            'kecamatan'        => old('kecamatan'),
            'program'          => old('program'),
            'kegiatan'         => old('kegiatan'),
            'sub_kegiatan'     => old('sub_kegiatan'),
            'ref_kabupaten'    => $this->kab_model->get_all(),
            'ref_program'      => $this->program_model->get_all($this->kode_user),
            'ref_kecamatan'    => [],
            'ref_kegiatan'     => [],
            'ref_sub_kegiatan' => []
        ];
        
        return view('master/bkk/form', $data);
    }

    public function store(){
        $userId = user()->id ?? null;

        $now = date('Y-m-d H:i:s');
        $db  = \Config\Database::connect();
        $db->transBegin();

        try {
            $data = [
                'nama_desa'          => $this->request->getPost('nama_desa'),
                'fk_id_kabupaten'    => $this->request->getPost('kabupaten'),
                'fk_id_kecamatan'    => $this->request->getPost('kecamatan'),
                'fk_program_id'      => $this->request->getPost('program'),
                'fk_kegiatan_id'     => $this->request->getPost('kegiatan'),
                'fk_sub_kegiatan_id' => $this->request->getPost('sub_kegiatan'),
                'kode_opd'           => $this->kode_user,
                'created_at'         => $now,
                'created_by'         => $userId
            ];

            $db->table('ms_desa')->insert($data);

            if ($db->transStatus() === false) {
                throw new \RuntimeException('DB transaction failed');
            }
            $db->transCommit();

            session()->setFlashdata('success', 'Berhasil insert data');
            return redirect()->to('/master/bkk');

        } catch (\Throwable $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal menyimpan: ' . $e->getMessage());
            return redirect()->to('/master/bkk');
        }
        
    }

    public function edit($id)
    {
        $row = $this->desa_model->get_by_id($id);

        $data = [
            'url'              => site_url('master/bkk/update'),
            'button'           => 'Edit',
            'tittle'           => 'Edit Master BKK',
            'id'               => old('id', $row->id),
            'nama_desa'        => old('nama_desa', $row->nama_desa),
            'kabupaten'        => old('kabupaten', $row->fk_id_kabupaten),
            'kecamatan'        => old('kecamatan', $row->fk_id_kecamatan),
            'program'          => old('program', $row->fk_program_id),
            'kegiatan'         => old('kegiatan', $row->fk_kegiatan_id),
            'sub_kegiatan'     => old('sub_kegiatan', $row->fk_sub_kegiatan_id),
            'ref_kabupaten'    => $this->kab_model->get_all(),
            'ref_program'      => $this->program_model->get_all($this->kode_user),
            'ref_kecamatan'    => $this->kec_model->get_all($row->fk_id_kabupaten),
            'ref_kegiatan'     => $this->kegiatan_model->get_all($row->fk_program_id),
            'ref_sub_kegiatan' => $this->sub_kegiatan_model->get_all($row->fk_kegiatan_id)
        ];
        

        return view('master/bkk/form', $data);
    }

    public function update(){
        $userId = user()->id ?? null;

        $now = date('Y-m-d H:i:s');
        $db  = \Config\Database::connect();
        $db->transBegin();

        $id = $this->request->getPost('id');

        try {
            $data = [
                'nama_desa'          => $this->request->getPost('nama_desa'),
                'fk_id_kabupaten'    => $this->request->getPost('kabupaten'),
                'fk_id_kecamatan'    => $this->request->getPost('kecamatan'),
                'fk_program_id'      => $this->request->getPost('program'),
                'fk_kegiatan_id'     => $this->request->getPost('kegiatan'),
                'fk_sub_kegiatan_id' => $this->request->getPost('sub_kegiatan'),
                'updated_at'         => $now,
                'updated_by'         => $userId
            ];

            $db->table('ms_desa')->where('id', $id)->update($data);

            if ($db->transStatus() === false) {
                throw new \RuntimeException('DB transaction failed');
            }
            $db->transCommit();

            session()->setFlashdata('success', 'Berhasil update data');
            return redirect()->to('/master/bkk');

        } catch (\Throwable $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal update: ' . $e->getMessage());
            return redirect()->to('/master/bkk');
        }
        
    }

    public function delete($id)
    {
        $db  = \Config\Database::connect();
        $db->transBegin();

        try {

            $row = $this->desa_model->get_by_id($id);

            if($row){
                $db->table('ms_desa')->where('id', $id)->delete();
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('DB transaction failed');
            }
            $db->transCommit();

            session()->setFlashdata('success', 'Berhasil hapus data');
            return redirect()->to('/master/bkk');

        } catch (\Throwable $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal hapus: ' . $e->getMessage());
            return redirect()->to('/master/bkk');
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

            $row = $this->desa_model->get_by_id($id);

            return $this->response->setJSON(['csrf' => $csrf, 'data' => $row]);
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }
}
