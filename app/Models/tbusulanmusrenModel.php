<?php

namespace App\Models;

use CodeIgniter\Model;
use PhpParser\Builder\Function_;

class tbusulanmusrenModel extends Model
{
    protected $table = 'tb_usulan_musren';
    protected $useTimestamps = true;
    // protected $allowedFields = ['fr_id_visi', 'fr_id_misi', 'tujuanpd'];

    public function gettotalusulanbytahun($tahun)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tb_usulan_musren');
        $builder->select('*');
        $array = ['tahun' => $tahun];
        $builder->where($array);
        $query = $builder->get();

        return $query->getResultArray();
    }

    public function gettotalusulanakomodirbytahun($tahun)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tb_usulan_musren');
        $builder->select('*');
        $array = ['tahun' => $tahun, 'status' => 1];
        $builder->where($array);
        $query = $builder->get();

        return $query->getResultArray();
    }

    public function gettotalusulantdkakomodirbytahun($tahun)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tb_usulan_musren');
        $builder->select('*');
        $array = ['tahun' => $tahun, 'status' => 0];
        $builder->where($array);
        $query = $builder->get();

        return $query->getResultArray();
    }

    public function gettotalusulanblmprosesbytahun($tahun)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tb_usulan_musren');
        $builder->select('*');
        $array = ['tahun' => $tahun, 'status' => 99];
        $builder->where($array);
        $query = $builder->get();

        return $query->getResultArray();
    }

    public function getusulanbyidbidang($idbidang)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tb_usulan_musren');
        $builder->select('*');
        $array = ['id_bidang' => $idbidang];
        $builder->where($array);
        $builder->orderBy('id_kecamatan', 'ASC');
        $query = $builder->get();

        return $query->getResultArray();
    }

    public function getusulan($idbidang, $idkecamatan)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tb_usulan_musren');
        $builder->select("*");
        $array = ['id_bidang' => $idbidang, 'id_kecamatan' => $idkecamatan];
        $builder->where($array);
        $builder->orderBy('prior', 'DESC');
        $query = $builder->get();

        return $query->getResultArray();
    }

    public function getusulanakomodir($idbidang, $idkecamatan, $status)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tb_usulan_musren');
        $builder->select('*');
        $array = ['id_bidang' => $idbidang, 'id_kecamatan' => $idkecamatan, 'status' => $status];
        $builder->where($array);
        $query = $builder->get();

        return $query->getResultArray();
    }

    public function getusulantdkakomodir($idbidang, $idkecamatan, $status)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tb_usulan_musren');
        $builder->select('*');
        $array = ['id_bidang' => $idbidang, 'id_kecamatan' => $idkecamatan, 'status' => $status];
        $builder->where($array);
        $query = $builder->get();

        return $query->getResultArray();
    }

    public function getusulanbyid($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tb_usulan_musren');
        $builder->select('*');
        $array = ['id' => $id];
        $builder->where($array);
        $query = $builder->get();

        return $query->getResultArray();
    }

    public function getusulanbyopd($opd_id, $tahun)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tb_usulan_musren u');

        $builder->select('u.*, r.id AS id_riwayat, r.status  AS status_pelaksanaan');
        $builder->join('tb_riwayat_usulan r', 'r.id_usulan_musren = u.id', 'left');

        $builder->where([
            'u.kode_opd' => $opd_id,
            'u.status'   => '1',
            'u.tahun'    => $tahun,
        ]);

        $builder->orderBy('u.id_usulan', 'ASC');

        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getusulanbykec($kec_id, $tahun)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tb_usulan_musren u');

        $builder->select('u.*, r.id AS id_riwayat, r.status AS status_pelaksanaan');
        $builder->join('tb_riwayat_usulan r', 'r.id_usulan_musren = u.id', 'left');

        $builder->where([
            'u.kode_kec' => $kec_id,
            'u.status'   => '1',
            'u.tahun'    => $tahun,
        ]);

        $builder->orderBy('u.id_usulan', 'ASC');

        $query = $builder->get();
        return $query->getResultArray();
    }


    public function countusulan($idbidang, $idkecamatan)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tb_usulan_musren');

        $builder->selectCount('id_usulan');

        $array = ['id_bidang' => $idbidang, 'id_kecamatan' => $idkecamatan];
        $builder->where($array);
        $query = $builder->get();

        $total = $query->getResultArray();

        $count = count($total);

        $dataresults = 0;
        for ($i = 0; $i < $count; $i++) {
            $dataresults = $total[$i]['id_usulan'];
        }
        return $dataresults;
    }

    public function countvalidasi($idbidang, $idkecamatan)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tb_usulan_musren');

        $builder->selectSum('count_validasi');

        $array = ['id_bidang' => $idbidang, 'id_kecamatan' => $idkecamatan];
        $builder->where($array);
        $query = $builder->get();

        $total = $query->getResultArray();

        $hasil = $total[0]['count_validasi'];

        // $count = count($total);

        // $dataresults = 0;
        // for ($i = 0; $i < $count; $i++) {
        //     $dataresults = $total[$i]['count_validasi'];
        // }
        return $hasil;
    }

    public function updatestatususulan($id, $status, $catatan, $countvalidasi, $perkiraan_anggaran, $volume)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tb_usulan_musren');
        $data = [
            'status'  => $status,
            'catatan' => $catatan,
            'count_validasi' => $countvalidasi,
            'perkiraan_anggaran' => $perkiraan_anggaran,
            'volume' => $volume,
        ];

        $builder->where('id', $id);
        $builder->update($data);
    }

    public function updateprior($id, $prior)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tb_usulan_musren');
        $data = [
            'prior'  => $prior,
        ];

        $builder->where('id', $id);
        $builder->update($data);
    }
}
