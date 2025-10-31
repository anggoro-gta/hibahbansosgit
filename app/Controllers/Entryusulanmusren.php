<?php

namespace App\Controllers;

use App\Models\mskecamatanModel;
use App\Models\mspriorModel;
use App\Models\msstatususulanModel;
use App\Models\msttdModel;
use App\Models\tbnomorttdModel;
use App\Models\tbttdModel;
use App\Models\tbusulanmusrenModel;
use \Dompdf\Dompdf;

class Entryusulanmusren extends BaseController
{
    protected $datakecamatan;
    protected $tbusulanmusren;
    protected $statususulan;
    protected $msprior;
    protected $msttd;
    protected $tblttd;
    protected $tblnomorttd;
    protected $tblusulan;
    protected $mskecamatan;

    protected $dompdf;

    public function __construct()
    {
        $this->datakecamatan = new mskecamatanModel();
        $this->tbusulanmusren = new tbusulanmusrenModel();
        $this->statususulan = new msstatususulanModel();
        $this->msprior = new mspriorModel();
        $this->msttd = new msttdModel();
        $this->tblttd = new tbttdModel();
        $this->tblnomorttd = new tbnomorttdModel();
        $this->tblusulan = new tbusulanmusrenModel();
        $this->mskecamatan = new mskecamatanModel();

        $this->dompdf = new Dompdf();
    }

    public function index()
    {
        $id_bidang = user()->kode_user;
        $nama_bidang = user()->fullname;

        $data_kecamatan = $this->datakecamatan->getkecamatanarray();

        $getlengh_datakecamatan = count($data_kecamatan);

        $arraykec = [];

        for ($i = 0; $i < $getlengh_datakecamatan; $i++) {
            $arraykec[$i]['id'] = $data_kecamatan[$i]['id'];
            $arraykec[$i]['nama_kecamatan'] = $data_kecamatan[$i]['nama_kecamatan'];
            $arraykec[$i]['jumlah_usulan'] = $this->tbusulanmusren->countusulan($id_bidang, $data_kecamatan[$i]['id']);
            if ($this->tbusulanmusren->countvalidasi($id_bidang, $data_kecamatan[$i]['id']) == NULL) {
                $arraykec[$i]['jumlah_validasi'] = 'Belum ada Usulan';
            } else {
                $arraykec[$i]['jumlah_validasi'] = $this->tbusulanmusren->countvalidasi($id_bidang, $data_kecamatan[$i]['id']);
            }
        }

        $data = [
            'tittle' => 'Usulan Musrenbang',
            'nama_bidang' => $nama_bidang,
            'datakecamatan' => $arraykec
        ];

        return view('musren/usulanindex', $data);
    }

    public function ambildarimasterttd($idkec)
    {
        $id_bidang = user()->kode_user;
        $id_kecamatan = $idkec;

        $nama_bidang = user()->fullname;

        // $nama_kecamatan = $this->datakecamatan->namakecamatan($id_kecamatan);

        $master_ttd = $this->msttd->getttd($id_bidang);

        $data = [
            'tittle' => 'Master TTD',
            'nama_bidang' => $nama_bidang,
            'idkec' => $id_kecamatan,
            'master_ttd' => $master_ttd
        ];

        return view('musren/ambildarimasterttdindex', $data);
    }

    public function masterttd()
    {
        $id_bidang = user()->kode_user;
        // $id_kecamatan = $id;

        $nama_bidang = user()->fullname;

        // $nama_kecamatan = $this->datakecamatan->namakecamatan($id_kecamatan);

        $master_ttd = $this->msttd->getttd($id_bidang);

        $data = [
            'tittle' => 'Master TTD',
            'nama_bidang' => $nama_bidang,
            'master_ttd' => $master_ttd
        ];

        return view('musren/masterttdindex', $data);
    }

    public function inputttdmaster()
    {
        $id_bidang = user()->kode_user;
        $nama_bidang = user()->fullname;

        $data = [
            'tittle' => 'Input TTD',
            'nama_bidang' => $nama_bidang,
            'id_bidang' => $id_bidang
        ];

        return view('musren/inputttdmaster', $data);
    }

    public function inputttd($id)
    {
        $id_bidang = user()->kode_user;
        $id_kecamatan = $id;

        $nama_kecamatan = $this->datakecamatan->namakecamatan($id_kecamatan);
        $nama_bidang = user()->fullname;

        $data = [
            'tittle' => 'Input TTD',
            'nama_kecamatan' => $nama_kecamatan,
            'nama_bidang' => $nama_bidang,
            'idkec' => $id_kecamatan,
            'id_bidang' => $id_bidang
        ];

        return view('musren/inputttd', $data);
    }

    public function savemasterttd()
    {
        $jenis = $this->request->getVar('jenis');
        $nama_penandatangan = $this->request->getVar('nama');
        $instansi = $this->request->getVar('instansi');
        $nip = $this->request->getVar('nip');

        $id_bidang =  $id_bidang = user()->kode_user;
        $nama_bidang = user()->fullname;

        //remove space on String
        // $nama_kecamatan_gandeng = str_replace(' ', '', $nama_kecamatan);
        $nama_bidang_gandeng = str_replace(' ', '', $nama_bidang);

        if ($this->request->getMethod() == "post") {

            $file_string = $this->request->getVar('signed');
            $image = explode(";base64,", $file_string);
            $image_type = explode("image/", $image[0]);
            $image_type_png = $image_type[1];
            $image_base64 = base64_decode($image[1]);
            $folderPath = ROOTPATH . 'public/imagesmasterttd/';
            $uniqname = $nama_bidang_gandeng . "-" . uniqid();
            $file = $folderPath . $uniqname . '.' . $image_type_png;
            $session = session();
            file_put_contents($file, $image_base64);

            $file_lokasi = "/imagesmasterttd/" . $uniqname . '.' . $image_type_png;

            $this->msttd->savettd($id_bidang, $jenis, $nama_penandatangan, $instansi, $nip, $file_lokasi);
        }

        return redirect()->to('/masterttd');
    }

    public function savettd()
    {
        $jenis = $this->request->getVar('jenis');
        $nama_penandatangan = $this->request->getVar('nama');
        $instansi = $this->request->getVar('instansi');
        $nip = $this->request->getVar('nip');

        $id_bidang =  $id_bidang = user()->kode_user;
        $id_kecamatan = $this->request->getVar('id_kecamatan');
        $nama_kecamatan = $this->datakecamatan->namakecamatan($id_kecamatan);
        $nama_bidang = user()->fullname;

        $ismaster = 0;

        //remove space on String
        $nama_kecamatan_gandeng = str_replace(' ', '', $nama_kecamatan);
        $nama_bidang_gandeng = str_replace(' ', '', $nama_bidang);

        if ($this->request->getMethod() == "post") {

            $file_string = $this->request->getVar('signed');
            $image = explode(";base64,", $file_string);
            $image_type = explode("image/", $image[0]);
            $image_type_png = $image_type[1];
            $image_base64 = base64_decode($image[1]);
            $folderPath = ROOTPATH . 'public/images/';
            $uniqname = $nama_bidang_gandeng . "-" . $nama_kecamatan_gandeng . uniqid();
            $file = $folderPath . $uniqname . '.' . $image_type_png;
            $session = session();
            file_put_contents($file, $image_base64);

            $file_lokasi = "/images/" . $uniqname . '.' . $image_type_png;

            $this->tblttd->savettd($id_bidang, $id_kecamatan, $jenis, $nama_penandatangan, $instansi, $nip, $file_lokasi, $ismaster);
        }

        return redirect()->to('/detailttd/' . $id_kecamatan);
    }

    public function saveambildarimasterttd($idkec, $id)
    {
        $id_bidang =  $id_bidang = user()->kode_user;
        $id_kecamatan = $idkec;

        $getmasterttd = $this->msttd->getttdbyid($id);

        $jenis = $getmasterttd[0]['jenis'];
        $nama_penandatangan = $getmasterttd[0]['nama_penandatangan'];
        $instansi = $getmasterttd[0]['instansi'];
        $nip = $getmasterttd[0]['nip'];
        $file_lokasi = $getmasterttd[0]['link_image'];

        $ismaster = 1;

        $this->tblttd->savettd($id_bidang, $id_kecamatan, $jenis, $nama_penandatangan, $instansi, $nip, $file_lokasi, $ismaster);

        return redirect()->to('/detailttd/' . $id_kecamatan);
    }

    public function deletemasterttd()
    {
        $id = $_POST['send_id'];

        $data_link_image = $this->msttd->getlinkimage($id);
        $datalink_remove_first_char = preg_replace('/^./', '', $data_link_image);

        if (file_exists($datalink_remove_first_char)) {
            unlink($datalink_remove_first_char);
        }

        $this->msttd->deletettd($id);

        $databerhasil = "berhasil";

        $data = [
            'status_update' => $databerhasil
        ];

        echo json_encode($data);
    }

    public function deletettd()
    {
        $id = $_POST['send_id'];

        $get_is_master = $this->tblttd->getismaster($id);

        if ($get_is_master == '1') {
            $this->tblttd->deletettd($id);
        } else if ($get_is_master == '0') {
            $data_link_image = $this->tblttd->getlinkimage($id);
            $datalink_remove_first_char = preg_replace('/^./', '', $data_link_image);

            if (file_exists($datalink_remove_first_char)) {
                unlink($datalink_remove_first_char);
            }
            $this->tblttd->deletettd($id);
        }

        $databerhasil = "berhasil";

        $data = [
            'status_update' => $databerhasil
        ];

        echo json_encode($data);
    }

    public function detailusulan($id)
    {
        $id_bidang = user()->kode_user;

        $nama_bidang = user()->fullname;
        $nama_kecamatan = $this->datakecamatan->namakecamatan($id);

        $data_usulan = $this->tbusulanmusren->getusulan($id_bidang, $id);
        $countdatausulan = count($data_usulan);
        $arraydatausulan = [];

        for ($i = 0; $i < $countdatausulan; $i++) {
            $arraydatausulan[$i]['id'] = $data_usulan[$i]['id'];
            $arraydatausulan[$i]['id_usulan'] = $data_usulan[$i]['id_usulan'];
            $arraydatausulan[$i]['tgl_usul'] = $data_usulan[$i]['tgl_usul'];
            $arraydatausulan[$i]['pengusul'] = $data_usulan[$i]['pengusul'];
            $arraydatausulan[$i]['profil'] = $data_usulan[$i]['profil'];
            $arraydatausulan[$i]['kamus_usulan'] = $data_usulan[$i]['kamus_usulan'];
            $arraydatausulan[$i]['masalah'] = $data_usulan[$i]['masalah'];
            $arraydatausulan[$i]['alamat'] = $data_usulan[$i]['alamat'];
            $arraydatausulan[$i]['id_kecamatan'] = $data_usulan[$i]['id_kecamatan'];
            $arraydatausulan[$i]['kecamatan'] = $data_usulan[$i]['kecamatan'];
            $arraydatausulan[$i]['kelurahan'] = $data_usulan[$i]['kelurahan'];
            $arraydatausulan[$i]['id_bidang'] = $data_usulan[$i]['id_bidang'];
            $arraydatausulan[$i]['opd_tujuan'] = $data_usulan[$i]['opd_tujuan'];
            $arraydatausulan[$i]['status'] = $data_usulan[$i]['status'];
            $arraydatausulan[$i]['catatan'] = $data_usulan[$i]['catatan'];
            $arraydatausulan[$i]['count_validasi'] = $data_usulan[$i]['count_validasi'];
            $arraydatausulan[$i]['prior'] = $data_usulan[$i]['prior'];
            $arraydatausulan[$i]['perkiraan_anggaran'] = number_format($data_usulan[$i]['perkiraan_anggaran'], 0, ",", ".");
            $arraydatausulan[$i]['volume'] = $data_usulan[$i]['volume'];
        }

        $data = [
            'tittle' => 'Usulan Musrenbang',
            'nama_kecamatan' => $nama_kecamatan,
            'nama_bidang' => $nama_bidang,
            'data_usulan' => $arraydatausulan,
            'idmus' => $id
        ];

        return view('musren/detailusulan', $data);
    }

    public function detailprior($id)
    {
        $id_bidang = user()->kode_user;

        $nama_bidang = user()->fullname;
        $nama_kecamatan = $this->datakecamatan->namakecamatan($id);

        $data_usulan = $this->tbusulanmusren->getusulan($id_bidang, $id);

        $data = [
            'tittle' => 'Usulan Musrenbang',
            'nama_kecamatan' => $nama_kecamatan,
            'nama_bidang' => $nama_bidang,
            'data_usulan' => $data_usulan,
            'idmus' => $id
        ];

        return view('musren/detailprior', $data);
    }

    public function detailttd($id)
    {
        $id_bidang = user()->kode_user;
        $id_kecamatan = $id;

        $nama_bidang = user()->fullname;

        $nama_kecamatan = $this->datakecamatan->namakecamatan($id_kecamatan);

        $data_ttd = $this->tblttd->getttd($id_bidang, $id_kecamatan);

        $data = [
            'tittle' => 'TTD BA',
            'nama_kecamatan' => $nama_kecamatan,
            'nama_bidang' => $nama_bidang,
            'data_ttd' => $data_ttd,
            'idkec' => $id_kecamatan
        ];

        return view('musren/detailttd', $data);
    }

    public function nomorttd($id)
    {
        $id_bidang = user()->kode_user;
        $id_kecamatan = $id;

        $nama_bidang = user()->fullname;

        $nama_kecamatan = $this->datakecamatan->namakecamatan($id_kecamatan);

        $data_nomorttd = $this->tblnomorttd->getnomorttd($id_bidang, $id_kecamatan);
        $count_data_nomorttd = count($data_nomorttd);

        $data = [
            'tittle' => 'NOMOR TTD BA',
            'nama_kecamatan' => $nama_kecamatan,
            'nama_bidang' => $nama_bidang,
            'data_nomorttd' => $data_nomorttd,
            'count_nomorttd' => $count_data_nomorttd,
            'idkec' => $id_kecamatan
        ];

        return view('musren/nomorttd', $data);
    }

    public function inputnomorttd($id)
    {
        $id_bidang = user()->kode_user;
        $id_kecamatan = $id;

        $nama_kecamatan = $this->datakecamatan->namakecamatan($id_kecamatan);
        $nama_bidang = user()->fullname;

        $data = [
            'tittle' => 'Input NOMOR TTD',
            'nama_kecamatan' => $nama_kecamatan,
            'nama_bidang' => $nama_bidang,
            'idkec' => $id_kecamatan,
            'id_bidang' => $id_bidang
        ];

        return view('musren/inputnomorttd', $data);
    }

    public function savenomorttd()
    {
        $nomor = $this->request->getVar('nomor');

        $id_bidang =  $id_bidang = user()->kode_user;
        $id_kecamatan = $this->request->getVar('id_kecamatan');
        $nama_kecamatan = $this->datakecamatan->namakecamatan($id_kecamatan);
        $nama_bidang = user()->fullname;

        $getdatanomorttd = $this->tblnomorttd->getnomorttd($id_bidang, $id_kecamatan);
        $count_getdatanomorttd = count($getdatanomorttd);

        if ($count_getdatanomorttd == 0) {
            $this->tblnomorttd->savenomorttd($id_bidang, $id_kecamatan, $nomor);
        }

        return redirect()->to('/nomorttd/' . $id_kecamatan);
    }

    public function deletenomorttd()
    {
        $id = $_POST['send_id'];

        $this->tblnomorttd->deletenomorttd($id);

        $databerhasil = "berhasil";

        $data = [
            'status_update' => $databerhasil
        ];

        echo json_encode($data);
    }

    public function saveupdatestatususulan()
    {
        $count_validasi = 1;
        $id = $_POST['id_data'];
        $status = $_POST['status_data'];
        $catatan = $_POST['catatan_data'];
        $perkiraan_anggaran = $_POST['perkiraan_anggaran_data'];
        $volume = $_POST['volume_data'];

        if ($catatan == "") {
            $data = [
                'status_update' => "kosong"
            ];

            echo json_encode($data);
        } else {

            if ($status == 99) {
                $count_validasi = 0;
                $this->tbusulanmusren->updatestatususulan($id, $status, $catatan, $count_validasi, $perkiraan_anggaran, $volume);

                $data = [
                    'status_update' => "berhasil"
                ];

                echo json_encode($data);
            } else {
                $this->tbusulanmusren->updatestatususulan($id, $status, $catatan, $count_validasi, $perkiraan_anggaran, $volume);

                $data = [
                    'status_update' => "berhasil"
                ];

                echo json_encode($data);
            }
        }
    }

    public function saveupdateprior()
    {
        $id = $_POST['id_data'];
        $prior = $_POST['prior'];

        // $status = $_POST['status_data'];
        // $catatan = $_POST['catatan_data'];

        $this->tbusulanmusren->updateprior($id, $prior);

        $data = [
            'status_update' => "berhasil"
        ];

        echo json_encode($data);
    }

    public function apigetdatamusren()
    {
        $id = $_POST['id_data'];
        $array_datamusren = [];

        $datamusren = $this->tbusulanmusren->getusulanbyid($id);

        $array_datamusren[0]['id'] = $datamusren[0]['id'];
        $array_datamusren[0]['id_usulan'] = $datamusren[0]['id_usulan'];
        $array_datamusren[0]['tgl_usul'] = $datamusren[0]['tgl_usul'];
        $array_datamusren[0]['pengusul'] = $datamusren[0]['pengusul'];
        $array_datamusren[0]['profil'] = $datamusren[0]['profil'];
        $array_datamusren[0]['kamus_usulan'] = $datamusren[0]['kamus_usulan'];
        $array_datamusren[0]['masalah'] = $datamusren[0]['masalah'];
        $array_datamusren[0]['alamat'] = $datamusren[0]['alamat'];
        $array_datamusren[0]['id_kecamatan'] = $datamusren[0]['id_kecamatan'];
        $array_datamusren[0]['kecamatan'] = $datamusren[0]['kecamatan'];
        $array_datamusren[0]['kelurahan'] = $datamusren[0]['kelurahan'];
        $array_datamusren[0]['id_bidang'] = $datamusren[0]['id_bidang'];
        $array_datamusren[0]['opd_tujuan'] = $datamusren[0]['opd_tujuan'];
        $array_datamusren[0]['status'] = $datamusren[0]['status'];
        $array_datamusren[0]['catatan'] = $datamusren[0]['catatan'];
        $array_datamusren[0]['count_validasi'] = $datamusren[0]['count_validasi'];
        $array_datamusren[0]['prior'] = $datamusren[0]['prior'];
        $array_datamusren[0]['perkiraan_anggaran'] = number_format($datamusren[0]['perkiraan_anggaran'], 0, ",", "");
        $array_datamusren[0]['volume'] = $datamusren[0]['volume'];

        $data = [
            'getdatamusren' => $array_datamusren
        ];

        echo json_encode($data);
    }

    public function apigetdataprior()
    {
        $id = $_POST['id_data'];

        $data = [
            'getdatamusren' => $this->tbusulanmusren->getusulanbyid($id)
        ];

        echo json_encode($data);
    }



    public function apigetstatususulan()
    {
        $get_term_status = $this->request->getVar('searchTerm');
        $data = [];

        if ($get_term_status) {
            $list_status = $this->statususulan->select('id,status')->like('status', $get_term_status)->orderBy('id')->findAll();
        } else {
            $list_status = $this->statususulan->select('id,status')->orderBy('id')->findAll();
        }

        foreach ($list_status as $value) {
            $data[] = [
                'id' => $value['id'],
                'text' => $value['status']
            ];
        }

        $response['data'] = $data;
        return $this->response->setJSON($response);
    }

    public function apigetprior()
    {
        $get_term_prior = $this->request->getVar('searchTerm');
        $data = [];

        if ($get_term_prior) {
            $list_prior = $this->msprior->select('id,prior')->like('prior', $get_term_prior)->orderBy('id')->findAll();
        } else {
            $list_prior = $this->msprior->select('id,prior')->orderBy('id')->findAll();
        }

        foreach ($list_prior as $value) {
            $data[] = [
                'id' => $value['id'],
                'text' => $value['prior']
            ];
        }

        $response['data'] = $data;
        return $this->response->setJSON($response);
    }

    // DISINI SEMUA FUNCTION UNTUK PRINT BERITA ACARA
    public function printbasidangkelompok($id)
    {
        $id_bidang = user()->kode_user;
        $id_kecamatan = $id;

        $nama_bidang = user()->fullname;
        $kode_bidang = user()->kode_user;
        $nama_kecamatan = $this->datakecamatan->namakecamatan($id_kecamatan);

        $data_ttd = $this->tblttd->getttd($id_bidang, $id_kecamatan);

        $getdatanomorttd = $this->tblnomorttd->getnomorttd($id_bidang, $id_kecamatan);
        $count_getdatanomorttd = count($getdatanomorttd);

        $data = [
            'tittle' => 'Print BA',
            'nama_bidang' => $nama_bidang,
            'nama_kecamatan' => $nama_kecamatan,
            'kode_bidang' => $kode_bidang,
            'data_ttd' => $data_ttd,
            'datattd' => $getdatanomorttd,
            'count_datattd' => $count_getdatanomorttd
        ];

        return view('musren/printbasidangkelompok', $data);
    }

    public function printlampiransidangkelompok($id)
    {
        $id_kecamatan = $id;
        $nama_kecamatan = $this->datakecamatan->namakecamatan($id_kecamatan);
        $id_bidang = user()->kode_user;
        $nama_bidang = user()->fullname;
        $getusulan = $this->tblusulan->getusulanakomodir($id_bidang, $id_kecamatan, 1);
        $getkcamatan = $this->mskecamatan->getkecamatanarraybyid($id_kecamatan);

        $countdatausulan = count($getusulan);
        $arraydatausulan = [];

        for ($i = 0; $i < $countdatausulan; $i++) {
            $arraydatausulan[$i]['id'] = $getusulan[$i]['id'];
            $arraydatausulan[$i]['id_usulan'] = $getusulan[$i]['id_usulan'];
            $arraydatausulan[$i]['tgl_usul'] = $getusulan[$i]['tgl_usul'];
            $arraydatausulan[$i]['pengusul'] = $getusulan[$i]['pengusul'];
            $arraydatausulan[$i]['profil'] = $getusulan[$i]['profil'];
            $arraydatausulan[$i]['kamus_usulan'] = $getusulan[$i]['kamus_usulan'];
            $arraydatausulan[$i]['masalah'] = $getusulan[$i]['masalah'];
            $arraydatausulan[$i]['alamat'] = $getusulan[$i]['alamat'];
            $arraydatausulan[$i]['id_kecamatan'] = $getusulan[$i]['id_kecamatan'];
            $arraydatausulan[$i]['kecamatan'] = $getusulan[$i]['kecamatan'];
            $arraydatausulan[$i]['kelurahan'] = $getusulan[$i]['kelurahan'];
            $arraydatausulan[$i]['id_bidang'] = $getusulan[$i]['id_bidang'];
            $arraydatausulan[$i]['opd_tujuan'] = $getusulan[$i]['opd_tujuan'];
            $arraydatausulan[$i]['status'] = $getusulan[$i]['status'];
            $arraydatausulan[$i]['catatan'] = $getusulan[$i]['catatan'];
            $arraydatausulan[$i]['count_validasi'] = $getusulan[$i]['count_validasi'];
            $arraydatausulan[$i]['prior'] = $getusulan[$i]['prior'];
            $arraydatausulan[$i]['perkiraan_anggaran'] = number_format($getusulan[$i]['perkiraan_anggaran'], 0, ",", ".");
            $arraydatausulan[$i]['volume'] = $getusulan[$i]['volume'];
        }

        $getdatanomorttd = $this->tblnomorttd->getnomorttd($id_bidang, $id_kecamatan);
        $count_getdatanomorttd = count($getdatanomorttd);

        $data = [
            'tittle' => 'print lampiran',
            'id_bidang' => $id_bidang,
            'datausulan' => $arraydatausulan,
            'datakecamatan' => $getkcamatan,
            'nomorttd' => $getdatanomorttd,
            'count_nomorttd' => $count_getdatanomorttd
        ];

        // return view('print/tujuanpdviewprint', $data);

        $html =  view('musren/printlampiranbasdgkelompok', $data);

        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper('folio', 'landscape');
        $this->dompdf->render();
        $this->dompdf->stream('I_lampiran_BA_' . $nama_bidang . '_' . $nama_kecamatan . '.pdf', array(
            "Attachment" => true
        ));
    }

    public function printlampiransidangkelompokx($id)
    {
        $id_kecamatan = $id;
        $nama_kecamatan = $this->datakecamatan->namakecamatan($id_kecamatan);
        $id_bidang = user()->kode_user;
        $nama_bidang = user()->fullname;
        $getusulan = $this->tblusulan->getusulantdkakomodir($id_bidang, $id_kecamatan, 0);
        $getkcamatan = $this->mskecamatan->getkecamatanarraybyid($id_kecamatan);

        $countdatausulan = count($getusulan);
        $arraydatausulan = [];

        for ($i = 0; $i < $countdatausulan; $i++) {
            $arraydatausulan[$i]['id'] = $getusulan[$i]['id'];
            $arraydatausulan[$i]['id_usulan'] = $getusulan[$i]['id_usulan'];
            $arraydatausulan[$i]['tgl_usul'] = $getusulan[$i]['tgl_usul'];
            $arraydatausulan[$i]['pengusul'] = $getusulan[$i]['pengusul'];
            $arraydatausulan[$i]['profil'] = $getusulan[$i]['profil'];
            $arraydatausulan[$i]['kamus_usulan'] = $getusulan[$i]['kamus_usulan'];
            $arraydatausulan[$i]['masalah'] = $getusulan[$i]['masalah'];
            $arraydatausulan[$i]['alamat'] = $getusulan[$i]['alamat'];
            $arraydatausulan[$i]['id_kecamatan'] = $getusulan[$i]['id_kecamatan'];
            $arraydatausulan[$i]['kecamatan'] = $getusulan[$i]['kecamatan'];
            $arraydatausulan[$i]['kelurahan'] = $getusulan[$i]['kelurahan'];
            $arraydatausulan[$i]['id_bidang'] = $getusulan[$i]['id_bidang'];
            $arraydatausulan[$i]['opd_tujuan'] = $getusulan[$i]['opd_tujuan'];
            $arraydatausulan[$i]['status'] = $getusulan[$i]['status'];
            $arraydatausulan[$i]['catatan'] = $getusulan[$i]['catatan'];
            $arraydatausulan[$i]['count_validasi'] = $getusulan[$i]['count_validasi'];
            $arraydatausulan[$i]['prior'] = $getusulan[$i]['prior'];
            $arraydatausulan[$i]['perkiraan_anggaran'] = number_format($getusulan[$i]['perkiraan_anggaran'], 0, ",", ".");
            $arraydatausulan[$i]['volume'] = $getusulan[$i]['volume'];
        }

        $getdatanomorttd = $this->tblnomorttd->getnomorttd($id_bidang, $id_kecamatan);
        $count_getdatanomorttd = count($getdatanomorttd);

        $data = [
            'tittle' => 'print lampiran',
            'id_bidang' => $id_bidang,
            'datausulan' => $arraydatausulan,
            'datakecamatan' => $getkcamatan,
            'nomorttd' => $getdatanomorttd,
            'count_nomorttd' => $count_getdatanomorttd
        ];

        // return view('print/tujuanpdviewprint', $data);

        $html =  view('musren/printlampiranbasdgkelompokx', $data);

        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper('folio', 'landscape');
        $this->dompdf->render();
        $this->dompdf->stream('II_lampiran_BA_' . $nama_bidang . '_' . $nama_kecamatan . '.pdf', array(
            "Attachment" => true
        ));
    }

    // END DISINI SEMUA FUNCTION UNTUK PRINT BERITA ACARA
}
