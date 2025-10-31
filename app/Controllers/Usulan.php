<?php

namespace App\Controllers;

use Myth\Auth\Password;
use App\Models\tbusulanmusrenModel;
use \Dompdf\Dompdf;

class Usulan extends BaseController
{
    protected $usulanModel;
    
    protected $dompdf;

    public function __construct()
    {
        $this->usulanModel = new tbusulanmusrenModel();

        $this->dompdf = new Dompdf();
    }

    public function index()
    {
        $is_opd = true;
        if (in_array('userkec', user()->getRoles(), true)) {
            $is_opd = false;
        }
        $data = [
            'tittle' => 'Data Usulan',
            'is_opd' => $is_opd
        ];
        
        return view('usulan/index', $data);
    }

    public function datatable()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['data'=>[]]);
        }

        $kode_user = user()->kode_user;
        $tahun = session('years');           // pastikan sudah dipilih
        if (!$tahun) {
            return $this->response->setJSON(['data'=>[]]);
        }

        if (in_array('useropd', user()->getRoles(), true)) 
        {
            $rows = $this->usulanModel->getusulanbyopd($kode_user, $tahun);
        }
        else if(in_array('userkec', user()->getRoles(), true))
        {
            $rows = $this->usulanModel->getusulanbykec($kode_user, $tahun);
        }

        // Bentuk array untuk DataTables (paling gampang: array of arrays)
        $data = [];
        $no = 1;
        foreach ($rows as $r) {
            $data[] = [
                'id'        => (int)($r['id'] ?? 0),
                'id_usulan' => $r['id_usulan'] ?? '-',
                'kecamatan' => $r['kecamatan'] ?? '-',
                'masalah'   => $r['masalah'] ?? '-',
                'alamat'    => $r['alamat'] ?? '-',
                'opd'       => $r['opd_tujuan'] ?? '-',
                'sipd'      => $r['sipd'] ?? '-',
                'anggaran'  => (float)($r['perkiraan_anggaran'] ?? 0),
            ];
        }

        // Jika CSRF aktif & regenerate, kirim token baru (opsional)
        $resp = ['data' => $data];
        if (function_exists('csrf_hash')) $resp['csrf'] = csrf_hash();

        return $this->response->setJSON($resp);
    }

    public function detailJson()
    {
        try {
            $this->response->setHeader('Content-Type', 'application/json');
            if (strtolower($this->request->getMethod()) !== 'post') {
                return $this->response->setJSON(['error' => 'Invalid method']);
            }

            $id = $this->request->getPost('id_usulan');
            $csrf = csrf_hash();

            // contoh ambil detail + join riwayat terakhir (opsional)
            $db = \Config\Database::connect();
            $row = $db->table('tb_usulan_musren u')
                ->where('u.id_usulan', $id)
                ->get()->getRowArray();

            return $this->response->setJSON(['csrf' => $csrf, 'data' => $row]);
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    public function show($id_usulan){

        $db = \Config\Database::connect();
        $row = $db->table('tb_usulan_musren u')
            ->where('u.id_usulan', $id_usulan)
            ->get()->getRowArray();

        $riwayat = $db->table('tb_riwayat_usulan')
            ->where('id_usulan_musren', $row['id'])
            ->get()->getRowArray();

        $foto = [];
        if (!empty($riwayat)) {
            // AMBIL SEMUA FOTO TERKAIT RIWAYAT INI
            $foto = $db->table('dokumen')
                    ->select('id, file_name, originale_name, url_name')
                    ->where('table_name', 'tb_riwayat_usulan')
                    ->where('table_id', $riwayat['id'])
                    ->get()->getResultArray();   // <-- penting: ResultArray
        }

        $is_opd = true;
        if (in_array('userkec', user()->getRoles(), true)) {
            $is_opd = false;
        }

        $data = [
            'tittle'  => 'Detail Usulan',
            'row'     => $row,
            'riwayat' => $riwayat,
            'foto'    => $foto,
            'is_opd'  => $is_opd
        ];

        return view('usulan/show', $data);
    }

    public function updateStatus()
    {
        $idUsulan  = $this->request->getPost('id_usulan_musren');
        $idRwy     = $this->request->getPost('id_riwayat'); // boleh kosong
        $status    = $this->request->getPost('status');
        $userId    = user()->id ?? null;

        $now = date('Y-m-d H:i:s');
        $db  = \Config\Database::connect();
        $db->transBegin();

        try {
            // ---------- INSERT / UPDATE riwayat ----------
            $riwayatTbl = $db->table('tb_riwayat_usulan');

            if (empty($idRwy)) {
                $dataR = [
                    'status'           => $status,
                    'id_usulan_musren' => $idUsulan,
                    'created_at'       => $now,
                    'created_by'       => $userId,
                ];
                $riwayatTbl->insert($dataR);
                // id riwayat yang baru
                $riwayatId = $db->insertID();
            } else {
                $dataR = [
                    'status'           => $status,
                    'id_usulan_musren' => $idUsulan,
                    'updated_at'       => $now,
                    'updated_by'       => $userId,
                ];
                $riwayatTbl->where('id', $idRwy)->update($dataR);
                $riwayatId = (int) $idRwy;

                // Jika status diubah menjadi 0, hapus semua foto terkait riwayat ini
                if ($status === '0') {
                    // Ambil semua baris dokumen untuk riwayat ini
                    $rows = $db->table('dokumen')
                        ->select('id, url_name')              // url_name contoh: "usulan/nama-file.jpg"
                        ->where('table_name', 'tb_riwayat_usulan')
                        ->where('table_id', $riwayatId)
                        ->get()
                        ->getResultArray();

                    if ($rows) {
                        foreach ($rows as $r) {
                            // Hapus file fisik kalau ada
                            // Jika saat upload Anda menyimpan di public/usulan/
                            $absPath = FCPATH . $r['url_name']; // hasil: .../public/usulan/xxx.jpg
                            if (is_file($absPath)) {
                                @unlink($absPath);            // @ untuk mencegah warning kalau file sudah tidak ada
                            }
                        }

                        // Hapus baris dokumen di DB
                        $db->table('dokumen')
                        ->where('table_name', 'tb_riwayat_usulan')
                        ->where('table_id', $riwayatId)
                        ->delete();
                    }
                }
            }

            // ---------- Upload foto (jika status=1) ----------
            if ($status === '1') {
                $files = $this->request->getFiles();
                $fotos = $files['foto'] ?? [];

                // hitung sudah ada di tabel dokumen
                $already = (int) $db->table('dokumen')
                    ->where('table_name', 'tb_riwayat_usulan')
                    ->where('table_id', $riwayatId)
                    ->countAllResults();

                $MAX = 3;
                $quota = max(0, $MAX - $already);
                if ($quota <= 0) {
                    // tidak terima foto baru jika sudah penuh
                    // (opsional: bisa dihapus dulu di halaman UI)
                    // lanjut tanpa error
                } else {
                    // pastikan folder ada
                    $dir = FCPATH . 'usulan/';
                    if (!is_dir($dir)) {
                        @mkdir($dir, 0775, true);
                    }

                    $rowsDoc = [];
                    $accepted = 0;

                    foreach ($fotos as $file) {
                        if ($accepted >= $quota) break;
                        if (!$file || !$file->isValid() || $file->hasMoved()) continue;

                        // validasi mime/ekstensi sederhana
                        $ext  = strtolower($file->getClientExtension() ?? '');
                        $mime = strtolower($file->getMimeType() ?? '');
                        if (!in_array($ext, ['jpg','jpeg','png','gif','webp'])) continue;
                        if (strpos($mime, 'image/') !== 0) continue;

                        $newName   = $file->getRandomName();
                        $origName  = $file->getClientName();

                        // simpan file fisik
                        $file->move($dir, $newName);

                        // path untuk diakses publik
                        $publicPath = 'usulan/' . $newName;                // tanpa domain
                        $diskName   = 'public';                            // bebas: penanda disk
                        $rowsDoc[] = [
                            'table_name'     => 'tb_riwayat_usulan',
                            'table_id'       => $riwayatId,
                            'file_name'      => $newName,
                            'originale_name' => $origName,   // pakai nama kolom persis seperti di tabel Anda
                            'disk_name'      => $diskName,
                            'url_name'       => $publicPath, // simpan path relatif
                            'created_at'     => $now,
                        ];
                        $accepted++;
                    }

                    if (!empty($rowsDoc)) {
                        $db->table('dokumen')->insertBatch($rowsDoc);
                    }
                }
            }

            // ---------- Sukses ----------
            if ($db->transStatus() === false) {
                throw new \RuntimeException('DB transaction failed');
            }
            $db->transCommit();

            return redirect()->back()->with('success', 'Update status berhasil');

        } catch (\Throwable $e) {
            $db->transRollback();
            // log_message('error', 'updateStatus error: {msg}', ['msg' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function deleteFoto()
    {
        if ($this->request->getMethod(true) !== 'POST' || !$this->request->isAJAX()) {
            return $this->response->setJSON(['ok' => false, 'msg' => 'Invalid request']);
        }

        $docId = (int) $this->request->getPost('doc_id');
        $db    = \Config\Database::connect();

        // ambil baris dokumen
        $doc = $db->table('dokumen')
            ->where('id', $docId)
            ->where('table_name', 'tb_riwayat_usulan') // safety: hanya konteks ini
            ->get()->getRowArray();

        if (!$doc) {
            return $this->response->setJSON(['ok' => false, 'msg' => 'Dokumen tidak ditemukan', 'csrf' => csrf_hash()]);
        }

        // hapus file fisik
        $abs = FCPATH . $doc['url_name']; // contoh: public/usulan/xxx.jpg
        if (is_file($abs)) { @unlink($abs); }

        // hapus row
        $db->table('dokumen')->where('id', $docId)->delete();

        return $this->response->setJSON(['ok' => true, 'csrf' => csrf_hash()]);
    }

    public function laporan()
    {
        $is_opd = true;
        if (in_array('userkec', user()->getRoles(), true)) {
            $is_opd = false;
        }
        $data = [
            'tittle' => 'Laporan Usulan',
            'is_opd' => $is_opd
        ];
        
        return view('usulan/laporan', $data);
    }

    public function datatableLaporan()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['data'=>[]]);
        }

        $kode_user = user()->kode_user;
        $tahun = session('years');           // pastikan sudah dipilih
        if (!$tahun) {
            return $this->response->setJSON(['data'=>[]]);
        }

        if (in_array('useropd', user()->getRoles(), true)) 
        {
            $rows = $this->usulanModel->getusulanbyopd($kode_user, $tahun);
        }
        else if(in_array('userkec', user()->getRoles(), true))
        {
            $rows = $this->usulanModel->getusulanbykec($kode_user, $tahun);
        }
        

        // Bentuk array untuk DataTables (paling gampang: array of arrays)
        $data = [];
        $no = 1;
        foreach ($rows as $r) {
            $data[] = [
                'id'                 => (int)($r['id'] ?? 0),
                'id_usulan'          => $r['id_usulan'] ?? '-',
                'kecamatan'          => $r['kecamatan'] ?? '-',
                'masalah'            => $r['masalah'] ?? '-',
                'alamat'             => $r['alamat'] ?? '-',
                'opd'                => $r['opd_tujuan'] ?? '-',
                'anggaran'           => (float)($r['perkiraan_anggaran'] ?? 0),
                'status_pelaksanaan' => $r['status_pelaksanaan'] ?? '0',
            ];
        }

        // Jika CSRF aktif & regenerate, kirim token baru (opsional)
        $resp = ['data' => $data];
        if (function_exists('csrf_hash')) $resp['csrf'] = csrf_hash();

        return $this->response->setJSON($resp);
    }

    public function exportPdf()
    {
        $kode_user = user()->kode_user;
        $instansi = user()->fullname;

        $tahun = session('years');

        if (in_array('useropd', user()->getRoles(), true)) 
        {
            $data_usulan = $this->usulanModel->getusulanbyopd($kode_user, $tahun);
        }
        else if(in_array('userkec', user()->getRoles(), true))
        {
            $data_usulan = $this->usulanModel->getusulanbykec($kode_user, $tahun);
        }

        $data = [
            'tittle'      => 'Laporan Usulan',
            'instansi'    => $instansi,
            'tahun'       => $tahun,
            'data_usulan' => $data_usulan
        ];

        $html =  view('usulan/export_pdf', $data);

        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper('folio', 'landscape');
        $this->dompdf->render();
        $this->dompdf->stream('Laporan Usulan_' . $instansi . '.pdf', array(
            "Attachment" => true
        ));
    }

    public function exportExcel()
    {
        try {
            $kode_user = user()->kode_user;
            $instansi  = user()->fullname;
            $tahun     = session('years');

            if (in_array('useropd', user()->getRoles(), true)) {
                $data_usulan = $this->usulanModel->getusulanbyopd($kode_user, $tahun);
            } elseif (in_array('userkec', user()->getRoles(), true)) {
                $data_usulan = $this->usulanModel->getusulanbykec($kode_user, $tahun);
            } else {
                $data_usulan = [];
            }

            $filename = 'Laporan Usulan_' . preg_replace('/[^\w\s\-_]/u','', $instansi) . '.xls';

            // Headers untuk HTML->XLS
            header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
            header('Content-Disposition: attachment; filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            header('Pragma: public');

            // Format Excel: "Rp 1.234.567"
            $fmtRupiah   = "mso-number-format:'\\0022Rp\\0022\\ #,##0'; text-align:right;";
            // Format teks (untuk Id Usulan agar 00123 tidak dipotong)
            $fmtAsText   = "mso-number-format:'\\@'";

            // Judul
            $judul = 'LAPORAN USULAN MUSRENBANG TAHUN '.$tahun.'<br>'.
                    htmlspecialchars(mb_strtoupper($instansi, 'UTF-8'));

            echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
            echo '<h4 style="text-align:center">'.$judul.'</h4>';

            // Sedikit CSS agar wrap & rapi
            echo '<style>
                    table { border-collapse: collapse; }
                    th, td { vertical-align: top; }
                    th { background-color:#CCCCCC; }
                    .wrap { mso-number-format:General; white-space:normal; }
                </style>';

            echo '<table border="1">';
            echo '<tr>
                    <th style="background-color:#CCCCCC;">No</th>
                    <th style="background-color:#CCCCCC;">Id Usulan</th>
                    <th style="background-color:#CCCCCC;">Kecamatan</th>
                    <th style="background-color:#CCCCCC;">Usulan</th>
                    <th style="background-color:#CCCCCC;">Lokasi</th>
                    <th style="background-color:#CCCCCC;">OPD</th>
                    <th style="background-color:#CCCCCC;">Anggaran</th>
                    <th style="background-color:#CCCCCC;">Status Pelaksanaan</th>
                </tr>';

            $no    = 1;

            foreach ($data_usulan as $item) {
                $anggaran = (int) ($item['perkiraan_anggaran'] ?? 0);

                $isDone       = (string)($item['status_pelaksanaan'] ?? '') === '1';
                $statusTeks   = $isDone ? 'Sudah' : 'Belum';
                $statusStyle  = $isDone
                    ? 'color:green;'
                    : 'color:red;';

                echo '<tr>';
                echo '<td>'.($no++).'</td>';
                echo '<td style="'.$fmtAsText.'">'.htmlspecialchars((string)$item['id_usulan']).'</td>';
                echo '<td class="wrap">'.htmlspecialchars($item['kecamatan']).'</td>';
                echo '<td class="wrap">'.htmlspecialchars($item['masalah']).'</td>';
                echo '<td class="wrap">'.htmlspecialchars($item['alamat']).'</td>';
                echo '<td class="wrap">'.htmlspecialchars($item['opd_tujuan']).'</td>';
                echo '<td style="'.$fmtRupiah.'">'.$anggaran.'</td>';
                echo '<td style="'.$statusStyle.' text-align: center">'.htmlspecialchars($statusTeks).'</td>';
                echo '</tr>';
            }

            echo '</table>';
            exit;
        } catch (\Throwable $th) {
            // Hindari output HTML lain saat header sudah dikirim
            header_remove();
            http_response_code(500);
            echo $th->getMessage();
        }
    }



}
