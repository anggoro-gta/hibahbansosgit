<?php

namespace App\Models;

use CodeIgniter\Model;

class tbpenandatangananModel extends Model
{
    protected $table = 'tb_penandatanganan';
    protected $useTimestamps = true;

    public function getpenandatangananarray($kode_bidang)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tb_penandatanganan');
        $builder->select('*');
        $array = ['id_bidang' => $kode_bidang];
        $builder->where($array);
        $query = $builder->get();

        return $query->getResultArray();
    }

    public function savepenandatanganan($nama, $instansi, $kode_bidang)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tb_penandatanganan');
        $data = [
            'nama'  => $nama,
            'instansi'  => $instansi,
            'id_bidang' => $kode_bidang,
        ];
        $builder->insert($data);
    }

    public function deletepenandatanganan($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tb_penandatanganan');
        $builder->delete(['id' => $id]);
    }
}
