<?php

namespace App\Controllers;

use App\Models\msbidangbappedaModel;
use App\Models\mskecamatanModel;
use App\Models\tbpenandatangananModel;
use App\Models\tbusulanmusrenModel;
use \Dompdf\Dompdf;

class Cetakmusren extends BaseController
{
    protected $bidangbappeda;
    protected $penandatanganan;
    protected $usulanmuren;
    protected $kecamatan;
    protected $dompdf;

    public function __construct()
    {
        $this->bidangbappeda = new msbidangbappedaModel();
        $this->penandatanganan = new tbpenandatangananModel();
        $this->usulanmuren = new tbusulanmusrenModel();
        $this->kecamatan = new mskecamatanModel();
        $this->dompdf = new Dompdf();
    }

    public function index()
    {
        $id_bidang = user()->kode_user;

        $getnamabidang =  $this->bidangbappeda->namabidang($id_bidang);
        $getdatapenandatangan = $this->penandatanganan->getpenandatangananarray($id_bidang);

        $data = [
            'tittle' => 'Print BA',
            'namabidang' => $getnamabidang,
            'penandatanganan' => $getdatapenandatangan,
        ];

        return view('musren/printba', $data);
    }

    public function printlamiran()
    {
        $id_bidang = user()->kode_user;
        $namabidang = $this->bidangbappeda->namabidang($id_bidang);
        $getusulan = $this->usulanmuren->getusulanbyidbidang($id_bidang);
        $getkcamatan = $this->kecamatan->getkecamatanarray();

        $data = [
            'tittle' => 'print lampiran',
            'namabidang' => $namabidang,
            'datausulan' => $getusulan,
            'datakecamatan' => $getkcamatan,
        ];        

        // return view('print/tujuanpdviewprint', $data);

        $html =  view('musren/printlampiranba', $data);

        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper('folio', 'landscape');
        $this->dompdf->render();
        $this->dompdf->stream('lampiran_BA_' . $namabidang . '.pdf', array(
            "Attachment" => true
        ));
    }
}
