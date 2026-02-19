<?php

namespace App\Controllers;

use App\Models\BansosModel;

class ViewUsulanBansos extends BaseController
{
    protected $bansos_model;

    public function __construct()
    {
        $this->bansos_model = new BansosModel();
    }

    public function index()
    {
        $data = [
            'tittle' => 'Master Bansos',
            'ref_opd' => $this->bansos_model->get_all_opd()
        ];

        return view('admin/viewusulan/viewusulanbansosindex', $data);
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
            $orderCols = ['b.nama', 'alamat_full', 'a.apbd', 'a.perubahan_perbup_1', 'a.perubahan_perbup_2', 'a.papbd', 'e.nama_opd'];
            $orderBy = $orderCols[$orderReq['column'] - 1] ?? 'a.id'; // -1 karena kolom nomor urut
            $orderDir = ($orderReq['dir'] ?? 'asc') === 'desc' ? 'DESC' : 'ASC';

            $recordsTotal    = $this->bansos_model->count_all_view_bansos($kodeOpd, $tahun);                          // total baris (tanpa search)
            $recordsFiltered = $this->bansos_model->count_filtered_view_bansos($kodeOpd, $tahun, $search);            // total setelah search
            $rows            = $this->bansos_model->get_page_view_bansos($kodeOpd, $tahun, $search, $orderBy, $orderDir, $length, $start);

            $data = [];
            foreach ($rows as $r) {
                $data[] = [
                    'nama'       => $r['nama'] . '<br><span class = "text-sm text-info">'
                        . $r['nik'] . '</span>',
                    'alamat'             => $r['alamat_full'],
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
