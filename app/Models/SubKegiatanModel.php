<?php

namespace App\Models;

use CodeIgniter\Model;

class SubKegiatanModel extends Model
{
    protected $table = 'ms_sub_kegiatan';
    protected $useTimestamps = true;
    // protected $allowedFields = ['email', 'username'];

    public function get_all($kegiatan_id = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('ms_sub_kegiatan ms');

        $builder->select('ms.*');
        if(!empty($kegiatan_id)){
            $builder->where([
                'ms.fk_kegiatan_id' => $kegiatan_id
            ]);
        }

        $builder->orderBy('ms.id', 'ASC');

        $query = $builder->get();
        return $query->getResultArray();
    }
}
