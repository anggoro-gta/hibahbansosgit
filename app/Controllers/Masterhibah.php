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

class MasterHibah extends BaseController
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
            'tittle' => 'Master Hibah',
            'ref_opd' => $this->hibah_model->get_all_opd()
        ];
        
        return view('master/hibah/index', $data);
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
            $orderCols = ['mh.tgl_berdiri','mh.nama_lembaga','mh.no_akta_hukum','alamat_full', 'nama_opd'];
            $orderBy = $orderCols[$orderReq['column'] - 1] ?? 'mh.id'; // -1 karena kolom nomor urut
            $orderDir = ($orderReq['dir'] ?? 'asc') === 'desc' ? 'DESC' : 'ASC';

            $recordsTotal    = $this->hibah_model->count_all($kodeOpd);                          // total baris (tanpa search)
            $recordsFiltered = $this->hibah_model->count_filtered($kodeOpd, $search);            // total setelah search
            $rows            = $this->hibah_model->get_page($kodeOpd, $search, $orderBy, $orderDir, $length, $start);

            $data = [];
            foreach ($rows as $r) {
                $btn = '<button type="button" class="btn btn-sm btn-info mb-1 btn-detail" data-id="'.$r['id'].'" title="Taging Nomenklatur"><i class="fa fa-eye"></i></button>';
                $btn .= ' <button type="button" class="btn btn-sm btn-warning mb-1 btn-history" data-id="'.$r['id'].'" title="History"><i class="fa fa-list"></i></button>';
                if ($this->kode_user == $r['kode_opd']) {
                    $btn .= ' <a href="'.base_url('master/hibah/edit/'.$r['id']).'" class="btn btn-sm btn-primary mb-1"><i class="fa fa-edit"></i></a>
                            <a href="'.base_url('master/hibah/delete/'.$r['id']).'" class="btn btn-sm btn-danger mb-1" onclick="return confirmDelete(\''.base_url('master/hibah/delete/'.$r['id']).'\')"><i class="fa fa-trash"></i></a>';
                }

                $data[] = [
                    'tgl_berdiri'   => date('d-m-Y', strtotime($r['tgl_berdiri'])),
                    'nama_lembaga'  => $r['nama_lembaga'] ?? '-',
                    'no_akta_hukum' => $r['no_akta_hukum'] ?? '-',
                    'alamat'        => $r['alamat_full'],
                    'nama_opd'        => $r['nama_opd'],
                    'action'        => $btn,
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
            'url'              => site_url('master/hibah/store'),
            'button'           => 'Tambah',
            'tittle'           => 'Tambah Master Hibah',
            'id'               => old('id'),
            'tgl_berdiri'      => old('tgl_berdiri'),
            'no_akta_hukum'    => old('no_akta_hukum'),
            'nama_lembaga'     => old('nama_lembaga'),
            'kabupaten'        => old('kabupaten'),
            'kecamatan'        => old('kecamatan'),
            'desa'             => old('desa'),
            'alamat'           => old('alamat'),
            'program'          => old('program'),
            'kegiatan'         => old('kegiatan'),
            'sub_kegiatan'     => old('sub_kegiatan'),
            'ref_kabupaten'    => $this->kab_model->get_all(),
            'ref_program'      => $this->program_model->get_all($this->kode_user),
            'ref_kecamatan'    => [],
            'ref_desa'         => [],
            'ref_kegiatan'     => [],
            'ref_sub_kegiatan' => []
        ];
        
        return view('master/hibah/form', $data);
    }

    public function store(){
        $userId = user()->id ?? null;

        $now = date('Y-m-d H:i:s');
        $db  = \Config\Database::connect();
        $db->transBegin();

        try {
            $data = [
                'tgl_berdiri'        => date('Y-m-d', strtotime($this->request->getPost('tgl_berdiri'))),
                'no_akta_hukum'      => $this->request->getPost('no_akta_hukum'),
                'nama_lembaga'       => $this->request->getPost('nama_lembaga'),
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

            $db->table('ms_hibah')->insert($data);

            $lastId = $db->insertID();

            $file_1 = $this->request->getFile('file_1');
            $file_2 = $this->request->getFile('file_2');
            $file_3 = $this->request->getFile('file_3');
            $file_4 = $this->request->getFile('file_4');

            $dir = FCPATH . 'upload/hibah/';
            if (!is_dir($dir)) {
                @mkdir($dir, 0775, true);
            }

            $rowsDoc = [];

            // helper kecil
            $allowedExt = ['jpg','jpeg','png','pdf']; // tambahkan kalau mau pdf

            if ($file_1 && $file_1->isValid() && !$file_1->hasMoved()) {
                $ext  = strtolower($file_1->getClientExtension() ?? '');
                $mime = strtolower($file_1->getMimeType() ?? '');

                // ijinkan kalau ekstensi boleh ATAU mime image/pdf
                if (in_array($ext, $allowedExt) || strpos($mime, 'image/') === 0 || $mime === 'application/pdf') {

                    $newName  = $file_1->getRandomName();
                    $origName = $file_1->getClientName();

                    // simpan fisik
                    $file_1->move($dir, $newName);

                    $rowsDoc[] = [
                        'table_name'     => 'ms_hibah_akta',  // atau ms_hibah_akta kalau memang begitu
                        'table_id'       => $lastId,
                        'file_name'      => $newName,
                        'originale_name'  => $origName,
                        'disk_name'      => 'public',
                        'url_name'       => 'upload/hibah/' . $newName,
                        'created_at'     => $now,
                    ];
                }
            }

            if ($file_2 && $file_2->isValid() && !$file_2->hasMoved()) {
                $ext  = strtolower($file_2->getClientExtension() ?? '');
                $mime = strtolower($file_2->getMimeType() ?? '');

                // ijinkan kalau ekstensi boleh ATAU mime image/pdf
                if (in_array($ext, $allowedExt) || strpos($mime, 'image/') === 0 || $mime === 'application/pdf') {

                    $newName  = $file_2->getRandomName();
                    $origName = $file_2->getClientName();

                    // simpan fisik
                    $file_2->move($dir, $newName);

                    $rowsDoc[] = [
                        'table_name'     => 'ms_hibah_bukti_lapor',  // atau ms_hibah_akta kalau memang begitu
                        'table_id'       => $lastId,
                        'file_name'      => $newName,
                        'originale_name'  => $origName,
                        'disk_name'      => 'public',
                        'url_name'       => 'upload/hibah/' . $newName,
                        'created_at'     => $now,
                    ];
                }
            }

            if ($file_3 && $file_3->isValid() && !$file_3->hasMoved()) {
                $ext  = strtolower($file_3->getClientExtension() ?? '');
                $mime = strtolower($file_3->getMimeType() ?? '');

                // ijinkan kalau ekstensi boleh ATAU mime image/pdf
                if (in_array($ext, $allowedExt) || strpos($mime, 'image/') === 0 || $mime === 'application/pdf') {

                    $newName  = $file_3->getRandomName();
                    $origName = $file_3->getClientName();

                    // simpan fisik
                    $file_3->move($dir, $newName);

                    $rowsDoc[] = [
                        'table_name'     => 'ms_hibah_npwp',  // atau ms_hibah_akta kalau memang begitu
                        'table_id'       => $lastId,
                        'file_name'      => $newName,
                        'originale_name'  => $origName,
                        'disk_name'      => 'public',
                        'url_name'       => 'upload/hibah/' . $newName,
                        'created_at'     => $now,
                    ];
                }
            }

            if ($file_4 && $file_4->isValid() && !$file_4->hasMoved()) {
                $ext  = strtolower($file_4->getClientExtension() ?? '');
                $mime = strtolower($file_4->getMimeType() ?? '');

                // ijinkan kalau ekstensi boleh ATAU mime image/pdf
                if (in_array($ext, $allowedExt) || strpos($mime, 'image/') === 0 || $mime === 'application/pdf') {

                    $newName  = $file_4->getRandomName();
                    $origName = $file_4->getClientName();

                    // simpan fisik
                    $file_4->move($dir, $newName);

                    $rowsDoc[] = [
                        'table_name'     => 'ms_hibah_domisili',  // atau ms_hibah_akta kalau memang begitu
                        'table_id'       => $lastId,
                        'file_name'      => $newName,
                        'originale_name'  => $origName,
                        'disk_name'      => 'public',
                        'url_name'       => 'upload/hibah/' . $newName,
                        'created_at'     => $now,
                    ];
                }
            }

            if (!empty($rowsDoc)) {
                $db->table('dokumen')->insertBatch($rowsDoc);
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('DB transaction failed');
            }
            $db->transCommit();

            session()->setFlashdata('success', 'Berhasil insert data');
            return redirect()->to('/master/hibah');

        } catch (\Throwable $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal menyimpan: ' . $e->getMessage());
            return redirect()->to('/master/hibah');
        }
        
    }

    public function edit($id)
    {
        $db  = \Config\Database::connect();
        $row = $this->hibah_model->get_by_id($id);

        $data = [
            'url'              => site_url('master/hibah/update'),
            'button'           => 'Edit',
            'tittle'           => 'Edit Master Hibah',
            'id'               => old('id', $row->id),
            'tgl_berdiri'      => old('tgl_berdiri', date('Y-m-d', strtotime($row->tgl_berdiri))),
            'no_akta_hukum'    => old('no_akta_hukum', $row->no_akta_hukum),
            'nama_lembaga'     => old('nama_lembaga', $row->nama_lembaga),
            'kabupaten'        => old('kabupaten', $row->fk_kabupaten_id),
            'kecamatan'        => old('kecamatan', $row->fk_kecamatan_id),
            'desa'             => old('desa', $row->fk_desa_id),
            'alamat'           => old('alamat', $row->alamat),
            'program'          => old('program', $row->fk_program_id),
            'kegiatan'         => old('kegiatan', $row->fk_kegiatan_id),
            'sub_kegiatan'     => old('sub_kegiatan', $row->fk_sub_kegiatan_id),
            'ref_kabupaten'    => $this->kab_model->get_all(),
            'ref_program'      => $this->program_model->get_all($this->kode_user),
            'ref_kecamatan'    => $this->kec_model->get_all($row->fk_kabupaten_id),
            'ref_desa'         => $this->desa_model->get_all($row->fk_kecamatan_id),
            'ref_kegiatan'     => $this->kegiatan_model->get_all($row->fk_program_id),
            'ref_sub_kegiatan' => $this->sub_kegiatan_model->get_all($row->fk_kegiatan_id),
            'file_1'           => $this->hibah_model->get_dokumen('ms_hibah_akta', $id),
            'file_2'           => $this->hibah_model->get_dokumen('ms_hibah_bukti_lapor', $id),
            'file_3'           => $this->hibah_model->get_dokumen('ms_hibah_npwp', $id),
            'file_4'           => $this->hibah_model->get_dokumen('ms_hibah_domisili', $id)
        ];

        return view('master/hibah/form', $data);
    }

    public function update(){
        $userId = user()->id ?? null;

        $now = date('Y-m-d H:i:s');
        $db  = \Config\Database::connect();
        $db->transBegin();

        $id = $this->request->getPost('id');

        try {
            $data = [
                'tgl_berdiri'        => date('Y-m-d', strtotime($this->request->getPost('tgl_berdiri'))),
                'no_akta_hukum'      => $this->request->getPost('no_akta_hukum'),
                'nama_lembaga'       => $this->request->getPost('nama_lembaga'),
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

            $db->table('ms_hibah')->where('id', $id)->update($data);

            $file_1 = $this->request->getFile('file_1');
            $file_2 = $this->request->getFile('file_2');
            $file_3 = $this->request->getFile('file_3');
            $file_4 = $this->request->getFile('file_4');

            $dir = FCPATH . 'upload/hibah/';
            if (!is_dir($dir)) {
                @mkdir($dir, 0775, true);
            }

            $rowsDoc = [];

            // helper kecil
            $allowedExt = ['jpg','jpeg','png','pdf']; // tambahkan kalau mau pdf

            if ($file_1 && $file_1->isValid() && !$file_1->hasMoved()) {

                $old_file_1 = $this->hibah_model->get_dokumen('ms_hibah_akta', $id);

                if(!empty($old_file_1)){
                    $absPath = FCPATH . $old_file_1->url_name; // hasil: .../public/usulan/xxx.jpg
                    if (is_file($absPath)) {
                        @unlink($absPath);            // @ untuk mencegah warning kalau file sudah tidak ada
                    }

                    // Hapus baris dokumen di DB
                    $db->table('dokumen')
                    ->where('table_name', 'ms_hibah_akta')
                    ->where('table_id', $id)
                    ->delete();
                }

                $ext  = strtolower($file_1->getClientExtension() ?? '');
                $mime = strtolower($file_1->getMimeType() ?? '');

                // ijinkan kalau ekstensi boleh ATAU mime image/pdf
                if (in_array($ext, $allowedExt) || strpos($mime, 'image/') === 0 || $mime === 'application/pdf') {

                    $newName  = $file_1->getRandomName();
                    $origName = $file_1->getClientName();

                    // simpan fisik
                    $file_1->move($dir, $newName);

                    $rowsDoc[] = [
                        'table_name'     => 'ms_hibah_akta',  // atau ms_hibah_akta kalau memang begitu
                        'table_id'       => $id,
                        'file_name'      => $newName,
                        'originale_name'  => $origName,
                        'disk_name'      => 'public',
                        'url_name'       => 'upload/hibah/' . $newName,
                        'created_at'     => $now,
                    ];
                }
            }

            if ($file_2 && $file_2->isValid() && !$file_2->hasMoved()) {

                $old_file_2 = $this->hibah_model->get_dokumen('ms_hibah_bukti_lapor', $id);

                if(!empty($old_file_2)){
                    $absPath = FCPATH . $old_file_2->url_name; // hasil: .../public/usulan/xxx.jpg
                    if (is_file($absPath)) {
                        @unlink($absPath);            // @ untuk mencegah warning kalau file sudah tidak ada
                    }

                    // Hapus baris dokumen di DB
                    $db->table('dokumen')
                    ->where('table_name', 'ms_hibah_bukti_lapor')
                    ->where('table_id', $id)
                    ->delete();
                }

                $ext  = strtolower($file_2->getClientExtension() ?? '');
                $mime = strtolower($file_2->getMimeType() ?? '');

                // ijinkan kalau ekstensi boleh ATAU mime image/pdf
                if (in_array($ext, $allowedExt) || strpos($mime, 'image/') === 0 || $mime === 'application/pdf') {

                    $newName  = $file_2->getRandomName();
                    $origName = $file_2->getClientName();

                    // simpan fisik
                    $file_2->move($dir, $newName);

                    $rowsDoc[] = [
                        'table_name'     => 'ms_hibah_bukti_lapor',  // atau ms_hibah_akta kalau memang begitu
                        'table_id'       => $id,
                        'file_name'      => $newName,
                        'originale_name'  => $origName,
                        'disk_name'      => 'public',
                        'url_name'       => 'upload/hibah/' . $newName,
                        'created_at'     => $now,
                    ];
                }
            }

            if ($file_3 && $file_3->isValid() && !$file_3->hasMoved()) {

                $old_file_3 = $this->hibah_model->get_dokumen('ms_hibah_npwp', $id);

                if(!empty($old_file_3)){
                    $absPath = FCPATH . $old_file_3->url_name; // hasil: .../public/usulan/xxx.jpg
                    if (is_file($absPath)) {
                        @unlink($absPath);            // @ untuk mencegah warning kalau file sudah tidak ada
                    }

                    // Hapus baris dokumen di DB
                    $db->table('dokumen')
                    ->where('table_name', 'ms_hibah_npwp')
                    ->where('table_id', $id)
                    ->delete();
                }

                $ext  = strtolower($file_3->getClientExtension() ?? '');
                $mime = strtolower($file_3->getMimeType() ?? '');

                // ijinkan kalau ekstensi boleh ATAU mime image/pdf
                if (in_array($ext, $allowedExt) || strpos($mime, 'image/') === 0 || $mime === 'application/pdf') {

                    $newName  = $file_3->getRandomName();
                    $origName = $file_3->getClientName();

                    // simpan fisik
                    $file_3->move($dir, $newName);

                    $rowsDoc[] = [
                        'table_name'     => 'ms_hibah_npwp',  // atau ms_hibah_akta kalau memang begitu
                        'table_id'       => $id,
                        'file_name'      => $newName,
                        'originale_name'  => $origName,
                        'disk_name'      => 'public',
                        'url_name'       => 'upload/hibah/' . $newName,
                        'created_at'     => $now,
                    ];
                }
            }

            if ($file_4 && $file_4->isValid() && !$file_4->hasMoved()) {

                $old_file_4 = $this->hibah_model->get_dokumen('ms_hibah_domisili', $id);

                if(!empty($old_file_4)){
                    $absPath = FCPATH . $old_file_4->url_name; // hasil: .../public/usulan/xxx.jpg
                    if (is_file($absPath)) {
                        @unlink($absPath);            // @ untuk mencegah warning kalau file sudah tidak ada
                    }

                    // Hapus baris dokumen di DB
                    $db->table('dokumen')
                    ->where('table_name', 'ms_hibah_domisili')
                    ->where('table_id', $id)
                    ->delete();
                }

                $ext  = strtolower($file_4->getClientExtension() ?? '');
                $mime = strtolower($file_4->getMimeType() ?? '');

                // ijinkan kalau ekstensi boleh ATAU mime image/pdf
                if (in_array($ext, $allowedExt) || strpos($mime, 'image/') === 0 || $mime === 'application/pdf') {

                    $newName  = $file_4->getRandomName();
                    $origName = $file_4->getClientName();

                    // simpan fisik
                    $file_4->move($dir, $newName);

                    $rowsDoc[] = [
                        'table_name'     => 'ms_hibah_domisili',  // atau ms_hibah_akta kalau memang begitu
                        'table_id'       => $id,
                        'file_name'      => $newName,
                        'originale_name'  => $origName,
                        'disk_name'      => 'public',
                        'url_name'       => 'upload/hibah/' . $newName,
                        'created_at'     => $now,
                    ];
                }
            }

            if (!empty($rowsDoc)) {
                $db->table('dokumen')->insertBatch($rowsDoc);
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('DB transaction failed');
            }
            $db->transCommit();

            session()->setFlashdata('success', 'Berhasil update data');
            return redirect()->to('/master/hibah');

        } catch (\Throwable $e) {
            echo $e->getMessage();
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal update: ' . $e->getMessage());
            // return redirect()->to('/master/hibah');
        }
        
    }

    public function delete($id)
    {
        $db  = \Config\Database::connect();
        $db->transBegin();

        try {

            $row = $this->hibah_model->get_by_id($id);

            $file_1 = $this->hibah_model->get_dokumen('ms_hibah_akta', $id);
            $file_2 = $this->hibah_model->get_dokumen('ms_hibah_bukti_lapor', $id);
            $file_3 = $this->hibah_model->get_dokumen('ms_hibah_npwp', $id);
            $file_4 = $this->hibah_model->get_dokumen('ms_hibah_domisili', $id);

            if(!empty($file_1)){
                $absPath = FCPATH . $file_1->url_name; // hasil: .../public/usulan/xxx.jpg
                if (is_file($absPath)) {
                    @unlink($absPath);            // @ untuk mencegah warning kalau file sudah tidak ada
                }

                // Hapus baris dokumen di DB
                $db->table('dokumen')
                ->where('table_name', 'ms_hibah_akta')
                ->where('table_id', $id)
                ->delete();
            }

            if(!empty($file_2)){
                $absPath = FCPATH . $file_2->url_name; // hasil: .../public/usulan/xxx.jpg
                if (is_file($absPath)) {
                    @unlink($absPath);            // @ untuk mencegah warning kalau file sudah tidak ada
                }

                // Hapus baris dokumen di DB
                $db->table('dokumen')
                ->where('table_name', 'ms_hibah_bukti_lapor')
                ->where('table_id', $id)
                ->delete();
            }

            if(!empty($file_3)){
                $absPath = FCPATH . $file_3->url_name; // hasil: .../public/usulan/xxx.jpg
                if (is_file($absPath)) {
                    @unlink($absPath);            // @ untuk mencegah warning kalau file sudah tidak ada
                }

                // Hapus baris dokumen di DB
                $db->table('dokumen')
                ->where('table_name', 'ms_hibah_npwp')
                ->where('table_id', $id)
                ->delete();
            }

            if(!empty($file_4)){
                $absPath = FCPATH . $file_4->url_name; // hasil: .../public/usulan/xxx.jpg
                if (is_file($absPath)) {
                    @unlink($absPath);            // @ untuk mencegah warning kalau file sudah tidak ada
                }

                // Hapus baris dokumen di DB
                $db->table('dokumen')
                ->where('table_name', 'ms_hibah_domisili')
                ->where('table_id', $id)
                ->delete();
            }

            if($row){
                $db->table('ms_hibah')->where('id', $id)->delete();
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('DB transaction failed');
            }
            $db->transCommit();

            session()->setFlashdata('success', 'Berhasil hapus data');
            return redirect()->to('/master/hibah');

        } catch (\Throwable $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal hapus: ' . $e->getMessage());
            return redirect()->to('/master/hibah');
        }
    }

    public function cekNoAkta()
    {
        try {
            // Ambil NIK dari request POST
            $no_akta = $this->request->getPost('no_akta');
            $id = $this->request->getPost('id') ?? null;

            $query = $this->hibah_model->cek_no_akta($no_akta, $id);

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

            $row = $this->hibah_model->get_by_id($id);

            return $this->response->setJSON(['csrf' => $csrf, 'data' => $row]);
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    public function historyUsulanJson()
    {
        try {
            $this->response->setHeader('Content-Type', 'application/json');
            if (strtolower($this->request->getMethod()) !== 'post') {
                return $this->response->setJSON(['error' => 'Invalid method']);
            }

            $id = $this->request->getPost('id');
            $csrf = csrf_hash();

            $row = $this->hibah_model->get_by_id($id);

            $res = $this->hibah_model->get_usulan_by_ms_hibah_id($id)->get()->getResultArray();

            $data = [];
            $no   = 1;
            foreach ($res as $r) {
                $data[] = [
                    $no++,
                    esc($r['tahun']),
                    esc($r['nama_opd']),
                ];
            }

            return $this->response->setJSON(['csrf' => $csrf, 'data' => $data, 'detail' => $row]);
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }
}
