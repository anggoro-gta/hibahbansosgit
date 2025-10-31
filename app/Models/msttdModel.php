<?php

namespace App\Models;

use CodeIgniter\Model;

class msttdModel extends Model
{
    protected $table = 'ms_ttd';
    protected $useTimestamps = true;
    // protected $allowedFields = ['fr_id_visi', 'fr_id_misi', 'tujuanpd'];

    public function getttd($idbidang)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('ms_ttd');
        $builder->select('*');
        $array = ['fk_id_bidang' => $idbidang];
        $builder->where($array);
        $query = $builder->get();

        return $query->getResultArray();
    }

    public function getttdbyid($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('ms_ttd');
        $builder->select('*');
        $array = ['id' => $id];
        $builder->where($array);
        $query = $builder->get();

        return $query->getResultArray();
    }

    public function getlinkimage($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('ms_ttd');

        $builder->select('link_image');

        $array = ['id' => $id];
        $builder->where($array);
        $query = $builder->get();

        $total = $query->getResultArray();

        $hasil = $total[0]['link_image'];

        return $hasil;
    }

    public function savettd($fk_id_bidang, $jenis, $nama_penandatangan, $instansi, $nip, $link_image)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('ms_ttd');
        $data = [
            'fk_id_bidang'  => $fk_id_bidang,
            'jenis'  => $jenis,
            'nama_penandatangan'  => $nama_penandatangan,
            'instansi'  => $instansi,
            'nip'  => $nip,
            'link_image'  => $link_image,
        ];
        $builder->insert($data);
    }

    public function deletettd($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('ms_ttd');
        $builder->delete(['id' => $id]);
    }
}
