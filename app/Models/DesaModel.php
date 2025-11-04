<?php

namespace App\Models;

use CodeIgniter\Model;

class DesaModel extends Model
{
    protected $table = 'ms_desa';
    protected $useTimestamps = true;
    // protected $allowedFields = ['email', 'username'];

    public function get_all($kec_id = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('ms_desa md');

        $builder->select('md.*');
        
        if(!empty($kec_id)){
            $builder->where([
                'md.fk_id_kecamatan' => $kec_id
            ]);
        }
        
        $builder->orderBy('md.nama_desa', 'ASC');

        $query = $builder->get();
        return $query->getResultArray();
    }
}
