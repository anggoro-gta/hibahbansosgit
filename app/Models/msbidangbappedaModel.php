<?php

namespace App\Models;

use CodeIgniter\Model;

class msbidangbappedaModel extends Model
{
    protected $table = 'ms_bidang_bappeda';
    protected $useTimestamps = true;          

    public function namabidang($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('ms_bidang_bappeda');

        $builder->select('namabidang');

        $array = ['id' => $id];
        $builder->where($array);
        $query = $builder->get();

        $total = $query->getResultArray();   
        
        $hasil = $total[0]['namabidang'];
    
        return $hasil;
    }
}
