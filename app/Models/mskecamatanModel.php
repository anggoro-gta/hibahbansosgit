<?php

namespace App\Models;

use CodeIgniter\Model;

class mskecamatanModel extends Model
{
    protected $table = 'ms_kecamatan';
    protected $useTimestamps = true;

    public function getkecamatanarray()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('ms_kecamatan');
        $builder->select('*');
        $query = $builder->get();

        return $query->getResultArray();
    }

    public function getkecamatanarraybyid($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('ms_kecamatan');
        $builder->select('*');
        $array = ['id' => $id];
        $builder->where($array);
        $query = $builder->get();

        return $query->getResultArray();
    }

    public function namakecamatan($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('ms_kecamatan');

        $builder->select('nama_kecamatan');

        $array = ['id' => $id];
        $builder->where($array);
        $query = $builder->get();

        $total = $query->getResultArray();

        $hasil = $total[0]['nama_kecamatan'];

        return $hasil;
    }
}
