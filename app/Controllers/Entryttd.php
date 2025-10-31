<?php

namespace App\Controllers;

use App\Models\tbpenandatangananModel;
use \Dompdf\Dompdf;

class Entryttd extends BaseController
{
    protected $penandatanganan;

    protected $dompdf;

    public function __construct()
    {
        $this->penandatanganan = new tbpenandatangananModel();

        $this->dompdf = new Dompdf();
    }

    public function index()
    {
        $kode_bidang = user()->kode_user;

        $data_penandatanganan = $this->penandatanganan->getpenandatangananarray($kode_bidang);

        $data = [
            'tittle' => 'Penandatanganan Musren',
            'datapenandatanganan' => $data_penandatanganan
        ];

        return view('ttd/penandatangananindex', $data);
    }

    public function savepenandatanganan()
    {
        $kode_bidang = user()->kode_user;
        $nama = $_POST['send_nama'];
        $instansi = $_POST['send_instansi'];

        if ($nama == "-") {
            $nama = NULL;
            $this->penandatanganan->savepenandatanganan($nama, $instansi, $kode_bidang);
        } else {
            $this->penandatanganan->savepenandatanganan($nama, $instansi, $kode_bidang);
        }

        $databerhasil = "berhasil";

        $data = [
            'status_update' => $databerhasil
        ];

        echo json_encode($data);
    }

    public function deletepenandatanganan()
    {
        $id = $_POST['send_id'];

        $this->penandatanganan->deletepenandatanganan($id);

        $databerhasil = "berhasil";

        $data = [
            'status_update' => $databerhasil
        ];

        echo json_encode($data);
    }
}
