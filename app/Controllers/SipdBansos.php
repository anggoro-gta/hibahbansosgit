<?php

namespace App\Controllers;

use App\Models\BansosModel;
use \Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class SipdBansos extends BaseController
{
    protected $bansos_model;
    
    protected $dompdf;
    protected $is_opd;
    protected $kode_user;

    public function __construct()
    {
        $this->bansos_model = new BansosModel();
    }

    public function index()
    {
        $tahun = $_SESSION['years'] ?? null;
        $jml_usulan = $this->bansos_model->get_all_usulan(null, $tahun, '')->countAllResults();

        $data = [
            'tittle' => 'SIPD Bansos',
            'jml_usulan' => $jml_usulan
        ];
        
        return view('sipd/bansos/form', $data);
    }

    public function exportExcel()
    {
        try {
            $pemaketan = $this->request->getPost('pemaketan_sipd');
            $keterangan = $this->request->getPost('keterangan_sipd');
            $jenis_anggaran = $this->request->getPost('jenis_anggaran');

            // Ambil data dari database atau array
            $usulan = $this->bansos_model->get_all_usulan(null, $_SESSION['years'], '')->get()->getResultArray();
            // echo var_dump($usulan);die();
            
            // Buat instance Spreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Tentukan header kolom
            $sheet->setCellValue('A1', 'Pemaketan/pengelompokan');
            $sheet->setCellValue('B1', 'Keterangan');
            $sheet->setCellValue('C1', 'nama');
            $sheet->setCellValue('D1', 'nik');
            $sheet->setCellValue('E1', 'kecamatan');
            $sheet->setCellValue('F1', 'kelurahan/desa');
            $sheet->setCellValue('G1', 'alamat');
            $sheet->setCellValue('H1', 'nilai');

            // Isi data ke baris setelah header
            $row = 2;
            foreach ($usulan as $index => $data) {
            $nilai = 0;
            if ($jenis_anggaran == 'apbd') {
                $nilai = (float) $data['apbd'];
            } else if ($jenis_anggaran == 'perubahan_perbup_1') {
                $nilai = (float) $data['perubahan_perbup_1'];
            } else if ($jenis_anggaran == 'perubahan_perbup_2') {
                $nilai = (float) $data['perubahan_perbup_2'];
            } else if ($jenis_anggaran == 'papbd') {
                $nilai = (float) $data['papbd'];
            }

            // Menulis data ke Excel
            $sheet->setCellValue('A' . $row, $pemaketan);
            $sheet->setCellValue('B' . $row, $keterangan);
            $sheet->setCellValue('C' . $row, $data['nama']);
            $sheet->setCellValueExplicit('D' . $row, (string) $data['nik'], DataType::TYPE_STRING);
            $sheet->setCellValue('E' . $row, $data['kode_ref_sipd_kec']);
            $sheet->setCellValue('F' . $row, $data['kode_ref_sipd_desa']);
            $sheet->setCellValue('G' . $row, $data['alamat']);
            $sheet->setCellValueExplicit('H' . $row, $nilai, DataType::TYPE_NUMERIC);  // Menulis angka sebagai numeric
            $row++;
        }

            // Set header untuk download file
            $writer = new Xlsx($spreadsheet);
            $filename = 'Data_SIPD_Bansos_' . $_SESSION['years'] . '_' . time();

            // Download file
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
        } catch (PhpSpreadsheet\Exception $e) {
            echo 'PhpSpreadsheet error: ', $e->getMessage();
        } catch (Exception $e) {
            echo 'General error: ', $e->getMessage();
        }
    }

}
