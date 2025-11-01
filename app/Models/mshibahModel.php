<?php

namespace App\Models;

use CodeIgniter\Model;

class mshibahModel extends Model
{
    protected $table = 'ms_hibah';
    protected $useTimestamps = true;
    // protected $allowedFields = ['email', 'username'];

    public function getallhibah()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('ms_hibah mh');

        $builder->select('mh.*,	mk.nama_kabupaten, k.nama_kecamatan, d.nama_desa');
        $builder->join('ms_kabupaten mk', 'mh.fk_kabupaten_id = mk.id');
        $builder->join('ms_kecamatan k', 'mh.fk_kecamatan_id = k.id');
        $builder->join('ms_desa d', 'mh.fk_desa_id = d.id');

        $builder->orderBy('mh.id', 'ASC');

        $query = $builder->get();
        return $query->getResultArray();
    }
}
