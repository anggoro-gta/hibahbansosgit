<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\IOFactory; 
use App\Models\HibahModel;
use App\Models\BansosModel;

class ImportExcel extends BaseController
{
    protected $hibah_model;
    protected $bansos_model;

    public function __construct()
    {
        $this->hibah_model = new HibahModel();
        $this->bansos_model = new BansosModel();
    }

    public function index_hibah()
    {
        $data['tittle'] = 'Import Hibah';
        return view('import/hibah', $data);
    }

    public function doImportHibah()
    {
        try {
            $file = $this->request->getFile('excel');

            if (! $file->isValid()) {
                return redirect()->back()->with('error', 'File tidak valid');
            }

            // ambil path temp
            $path = $file->getTempName();

            // load spreadsheet
            $spreadsheet = IOFactory::load($path);
            $sheet       = $spreadsheet->getActiveSheet();
            $rows        = $sheet->toArray();

            
            $db  = \Config\Database::connect();

            foreach ($rows as $i => $row) {
                if ($i === 0) {
                    // baris pertama header
                    continue;
                }

                $data = [
                    'fk_kabupaten_id'    => $row[0] ?? null,
                    'fk_kecamatan_id'    => $row[1] ?? null,
                    'fk_desa_id'         => $row[2] ?? null,
                    'fk_program_id'      => $row[3] ?? null,
                    'fk_kegiatan_id'     => $row[4] ?? null,
                    'fk_sub_kegiatan_id' => $row[5] ?? null,
                    'tgl_berdiri'        => date('Y-m-d', strtotime($row[6])) ?? null,
                    'no_akta_hukum'      => $row[7] ?? null,
                    'nama_lembaga'       => $row[8] ?? null,
                    'alamat'             => $row[9] ?? null,
                    'kode_opd'           => $row[10] ?? null,
                    'created_at'         => date('Y-m-d H:i:s'),
                    'created_by'         => user()->id ?? null
                ];

                $no_akta = $row[7];

                $query = $this->hibah_model->cek_no_akta($no_akta);

                if($query==false){
                    $db->table('ms_hibah')->insert($data);
                }
            }

            session()->setFlashdata('success', 'Berhasil import data');
            return redirect()->to('/master/hibah');
        } catch (\Throwable $e) {
            session()->setFlashdata('error', 'Gagal import: ' . $e->getMessage());
            return redirect()->to('/master/hibah');
        }
    }

    public function index_bansos()
    {
        $data['tittle'] = 'Import Bansos';
        return view('import/bansos', $data);
    }

    public function doImportBansos()
    {
        try {
            $file = $this->request->getFile('excel');

            if (! $file->isValid()) {
                return redirect()->back()->with('error', 'File tidak valid');
            }

            // ambil path temp
            $path = $file->getTempName();

            // load spreadsheet
            $spreadsheet = IOFactory::load($path);
            $sheet       = $spreadsheet->getActiveSheet();
            $rows        = $sheet->toArray();

            
            $db  = \Config\Database::connect();

            foreach ($rows as $i => $row) {
                if ($i === 0) {
                    // baris pertama header
                    continue;
                }

                $data = [
                    'fk_kabupaten_id'    => $row[0] ?? null,
                    'fk_kecamatan_id'    => $row[1] ?? null,
                    'fk_desa_id'         => $row[2] ?? null,
                    'fk_program_id'      => $row[3] ?? null,
                    'fk_kegiatan_id'     => $row[4] ?? null,
                    'fk_sub_kegiatan_id' => $row[5] ?? null,
                    'nik'                => $row[6] ?? null,
                    'nama'               => $row[7] ?? null,
                    'alamat'             => $row[8] ?? null,
                    'kode_opd'           => $row[9] ?? null,
                    'created_at'         => date('Y-m-d H:i:s'),
                    'created_by'         => user()->id ?? null
                ];

                $nik = $row[6];

                $query = $this->bansos_model->cek_nik($nik);

                if($query==false){
                    $db->table('ms_bansos')->insert($data);
                }
            }

            session()->setFlashdata('success', 'Berhasil import data');
            return redirect()->to('/master/bansos');
        } catch (\Throwable $e) {
            session()->setFlashdata('error', 'Gagal import: ' . $e->getMessage());
            return redirect()->to('/master/bansos');
        }
    }
}
