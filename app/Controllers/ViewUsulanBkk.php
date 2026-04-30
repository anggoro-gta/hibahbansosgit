<?php

namespace App\Controllers;

use App\Models\BkkModel;

class ViewUsulanBkk extends BaseController
{
    protected $bkk_model;

    public function __construct()
    {
        $this->bkk_model = new BkkModel();
    }

    public function index()
    {
        $data = [
            'tittle' => 'Master Bkk',
            'ref_opd' => $this->bkk_model->get_all_opd()
        ];

        return view('admin/viewusulan/viewusulanbkkindex', $data);
    }

    public function datatable()
    {
        try {
            if (!$this->request->isAJAX()) {
                return $this->response->setStatusCode(400)->setJSON(['data' => []]);
            }

            $draw   = (int) $this->request->getPost('draw');
            $start  = (int) $this->request->getPost('start');   // offset
            $length = (int) $this->request->getPost('length');  // limit
            $search = $this->request->getPost('search')['value'] ?? '';
            $orderReq = $this->request->getPost('order')[0] ?? null; // column index & dir
            $kodeOpd = $this->request->getPost('kode_opd');
            $tahun = $_SESSION['years'];

            // mapping index kolom -> nama kolom di DB
            $orderCols = ['d.nama_kecamatan','c.nama_kabupaten','b.nama_desa', 'a.apbd', 'a.perubahan_perbup_1', 'a.perubahan_perbup_2', 'a.papbd', 'e.fullname'];
            $orderBy = $orderCols[$orderReq['column'] - 1] ?? 'a.id'; // -1 karena kolom nomor urut
            $orderDir = ($orderReq['dir'] ?? 'asc') === 'desc' ? 'DESC' : 'ASC';

            $recordsTotal    = $this->bkk_model->count_all_view_bkk($kodeOpd, $tahun);                          // total baris (tanpa search)
            $recordsFiltered = $this->bkk_model->count_filtered_view_bkk($kodeOpd, $tahun, $search);            // total setelah search
            $rows            = $this->bkk_model->get_page_view_bkk($kodeOpd, $tahun, $search, $orderBy, $orderDir, $length, $start);

            $data = [];
            foreach ($rows as $r) {
                $data[] = [
                    'nama_desa'       => $r['nama_desa'],                   
                    'apbd'               => $r['apbd'],
                    'perubahan_perbup_1' => $r['perubahan_perbup_1'],
                    'perubahan_perbup_2' => $r['perubahan_perbup_2'],
                    'papbd'              => $r['papbd'],
                    'opd'                => $r['fullname'],
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
}
