<?php

namespace App\Models;

use CodeIgniter\Model;

class tbnomorttdModel extends Model
{
    protected $table = 'tb_nomorttd';
    protected $useTimestamps = true;
    // protected $allowedFields = ['fr_id_visi', 'fr_id_misi', 'tujuanpd'];

    public function getnomorttd($idbidang, $idkecamatan)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tb_nomorttd');
        $builder->select('*');
        $array = ['fk_id_bidang' => $idbidang, 'fk_id_kecamatan' => $idkecamatan];
        $builder->where($array);
        $query = $builder->get();

        return $query->getResultArray();
    }

    public function savenomorttd($fk_id_bidang, $fk_id_kecamatan, $nomor)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tb_nomorttd');
        $data = [
            'fk_id_bidang'  => $fk_id_bidang,
            'fk_id_kecamatan'  => $fk_id_kecamatan,
            'nomor'  => $nomor,
        ];
        $builder->insert($data);
    }

    public function deletenomorttd($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tb_nomorttd');
        $builder->delete(['id' => $id]);
    }
}
