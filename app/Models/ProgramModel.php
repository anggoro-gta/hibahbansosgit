<?php

namespace App\Models;

use CodeIgniter\Model;

class ProgramModel extends Model
{
    protected $table = 'ms_program';
    protected $useTimestamps = true;
    // protected $allowedFields = ['email', 'username'];

    public function get_all($kode_opd = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('ms_program mp');

        $builder->select('mp.*');
        if(!empty($kode_opd)){
            $builder->where([
                'mp.kode_opd' => $kode_opd
            ]);
        }

        $builder->orderBy('mp.id', 'ASC');

        $query = $builder->get();
        return $query->getResultArray();
    }
}
