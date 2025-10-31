<?php

namespace App\Models;

use CodeIgniter\Model;

class tbttdModel extends Model
{
    protected $table = 'tb_ttd';
    protected $useTimestamps = true;
    // protected $allowedFields = ['fr_id_visi', 'fr_id_misi', 'tujuanpd'];

    public function getttd($idbidang, $idkecamatan)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tb_ttd');
        $builder->select('*');
        $array = ['fk_id_bidang' => $idbidang, 'fk_id_kecamatan' => $idkecamatan];
        $builder->where($array);
        $query = $builder->get();

        return $query->getResultArray();
    }

    public function getismaster($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tb_ttd');

        $builder->select('is_master');

        $array = ['id' => $id];
        $builder->where($array);
        $query = $builder->get();

        $total = $query->getResultArray();

        $hasil = $total[0]['is_master'];

        return $hasil;
    }

    public function getlinkimage($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tb_ttd');

        $builder->select('link_image');

        $array = ['id' => $id];
        $builder->where($array);
        $query = $builder->get();

        $total = $query->getResultArray();

        $hasil = $total[0]['link_image'];

        return $hasil;
    }

    public function savettd($fk_id_bidang, $fk_id_kecamatan, $jenis, $nama_penandatangan, $instansi, $nip, $link_image, $is_master)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tb_ttd');
        $data = [
            'fk_id_bidang'  => $fk_id_bidang,
            'fk_id_kecamatan'  => $fk_id_kecamatan,
            'jenis'  => $jenis,
            'nama_penandatangan'  => $nama_penandatangan,
            'instansi'  => $instansi,
            'nip'  => $nip,
            'link_image'  => $link_image,
            'is_master' => $is_master,
        ];
        $builder->insert($data);
    }

    public function deletettd($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tb_ttd');
        $builder->delete(['id' => $id]);
    }
}
