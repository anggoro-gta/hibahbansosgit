<?php

namespace App\Models;

use CodeIgniter\Model;

class KabupatenModel extends Model
{
    protected $table = 'ms_kabupaten';
    protected $useTimestamps = true;
    // protected $allowedFields = ['email', 'username'];

    public function get_all()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('ms_kabupaten mk');

        $builder->select('mk.*');

        $builder->orderBy('mk.nama_kabupaten', 'ASC');

        $query = $builder->get();
        return $query->getResultArray();
    }
}
