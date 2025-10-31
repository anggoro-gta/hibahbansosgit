<?php

namespace App\Models;

use CodeIgniter\Model;

class usersModel extends Model
{
    protected $table = 'users';
    protected $useTimestamps = true;
    protected $allowedFields = ['email', 'username'];

    public function getuser($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('users');
        $builder->select('id, username, email, user_image');
        $array = ['id' => $id];
        $builder->where($array);
        $query = $builder->get();

        return $query->getRow();
    }

    public function getnamaskpd($kode_user)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('users');
        $builder->select('fullname');
        $array = ['kode_user' => $kode_user];
        $builder->where($array);
        $query = $builder->get();

        return $query->getResultArray();
    }

    public function updatepass($pass, $kode_skpd)
    {
        date_default_timezone_set('Asia/Jakarta');
        $updatedate = date("Y-m-d H:i:s");
        $db = \Config\Database::connect();
        $builder = $db->table('users');
        $data = [
            'password_hash'  => $pass,
            'updated_at' => $updatedate,
        ];

        $builder->where('kode_user', $kode_skpd);
        $builder->update($data);
    }

    public function updatepassbyid($pass, $id)
    {
        date_default_timezone_set('Asia/Jakarta');
        $updatedate = date("Y-m-d H:i:s");
        $db = \Config\Database::connect();
        $builder = $db->table('users');
        $data = [
            'password_hash'  => $pass,
            'updated_at' => $updatedate,
        ];

        $builder->where('id', $id);
        $builder->update($data);
    }
}
