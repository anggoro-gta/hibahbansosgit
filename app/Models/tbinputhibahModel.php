<?php

namespace App\Models;

use CodeIgniter\Model;

class tbinputhibahModel extends Model
{
    protected $table = 'tb_inputhibah';
    protected $useTimestamps = true;
    // protected $allowedFields = ['email', 'username'];

    public function getalldatainputhibah()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tb_inputhibah ti');

        $builder->select('ti.*, mk.nama_kabupaten, k.nama_kecamatan, d.nama_desa');
        // mk.nama_kabupaten, k.nama_kecamatan, d.nama_desa
        $builder->join('ms_hibah h', 'ti.fk_id_hibah = h.id');
        $builder->join('ms_kabupaten mk', 'ti.fk_id_kabupaten = mk.id');
        $builder->join('ms_kecamatan k', 'ti.fk_id_kecamatan = k.id');
        $builder->join('ms_desa d', 'ti.fk_id_desa = d.id');

        $builder->orderBy('ti.id', 'ASC');

        $query = $builder->get();
        return $query->getResultArray();
    }
}
