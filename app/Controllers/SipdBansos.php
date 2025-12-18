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
            $pemaketan = strip_tags($this->request->getPost('pemaketan_sipd'));
            $keterangan = strip_tags($this->request->getPost('keterangan_sipd'));
            $jenis_anggaran = $this->request->getPost('jenis_anggaran');

            // Ambil data dari database atau array
            $usulan = $this->bansos_model->get_all_usulan(null, $_SESSION['years'], '')->get()->getResultArray();

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
            foreach ($usulan as $data) {
                $pemaketan_cleaned = strip_tags($pemaketan);
                $keterangan_cleaned = strip_tags($keterangan);
                $nama_cleaned = strip_tags($data['nama']);
                $nik_cleaned = strip_tags($data['nik']);
                $kecamatan_cleaned = strip_tags($data['kode_ref_sipd_kec']);
                $kelurahan_cleaned = strip_tags($data['kode_ref_sipd_desa']);
                $alamat_cleaned = strip_tags($data['alamat']);
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
                $nilai_cleaned = (float) $nilai;

                // Menulis data ke Excel
                $sheet->setCellValue('A' . $row, $pemaketan_cleaned);
                $sheet->setCellValue('B' . $row, $keterangan_cleaned);
                $sheet->setCellValue('C' . $row, $nama_cleaned);
                $sheet->setCellValueExplicit('D' . $row, (string)$nik_cleaned, DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('E' . $row, (string)$kecamatan_cleaned, DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('F' . $row, (string)$kelurahan_cleaned, DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('G' . $row, $alamat_cleaned, DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('H' . $row, $nilai_cleaned, DataType::TYPE_NUMERIC);
                $row++;
            }

            // Atur lebar kolom secara otomatis
            foreach (range('A', 'H') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
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
