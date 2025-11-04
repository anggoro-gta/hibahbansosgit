<?php

namespace App\Models;

use CodeIgniter\Model;

class KegiatanModel extends Model
{
    protected $table = 'ms_kegiatan';
    protected $useTimestamps = true;
    // protected $allowedFields = ['email', 'username'];

    public function get_all($program_id = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('ms_kegiatan mk');

        $builder->select('mk.*');
        if(!empty($program_id)){
            $builder->where([
                'mk.fk_program_id' => $program_id
            ]);
        }

        $builder->orderBy('mk.id', 'ASC');

        $query = $builder->get();
        return $query->getResultArray();
    }
}
