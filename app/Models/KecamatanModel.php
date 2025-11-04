<?php

namespace App\Models;

use CodeIgniter\Model;

class KecamatanModel extends Model
{
    protected $table = 'ms_kecamatan';
    protected $useTimestamps = true;
    // protected $allowedFields = ['email', 'username'];

    public function get_all($kab_id = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('ms_kecamatan mk');

        $builder->select('mk.*');
        
        if(!empty($kab_id)){
            $builder->where([
                'mk.fk_id_kabupaten' => $kab_id
            ]);
        }

        $builder->orderBy('mk.nama_kecamatan', 'ASC');

        $query = $builder->get();
        return $query->getResultArray();
    }
}
