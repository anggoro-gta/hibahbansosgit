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

    private function baseQuery($search = '')
    {
        $db = \Config\Database::connect();
        $builder = $db->table('ms_desa')
            ->select("ms_desa.id, nama_desa, nama_kabupaten, nama_kecamatan")
            ->join('ms_kabupaten a', 'ms_desa.fk_id_kabupaten = a.id')
            ->join('ms_kecamatan b', 'ms_desa.fk_id_kecamatan = b.id');

        if ($search !== '') {
            $builder->groupStart()
                ->like('ms_desa.nama_desa', $search)
                ->orLike('a.nama_kabupaten', $search)
                ->orLike('b.nama_kecamatan', $search)
                ->groupEnd();
        }

        return $builder;
    }

    public function count_all()
    {
        return $this->baseQuery()->countAllResults(false);
    }

    public function count_filtered($search)
    {
        return $this->baseQuery($search)->countAllResults(false);
    }

    public function get_page($search, $orderBy, $orderDir, $limit, $offset)
    {
        return $this->baseQuery($search)
            ->orderBy($orderBy, $orderDir)
            ->limit($limit, $offset)
            ->get()->getResultArray();
    }

    public function get_by_id($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('ms_desa');
        
        // Select all fields
        $builder->select('ms_desa.*, nama_kabupaten, nama_kecamatan, nama_program, nama_kegiatan, nama_sub_kegiatan');
        $builder->join('ms_kabupaten', 'ms_desa.fk_id_kabupaten = ms_kabupaten.id');
        $builder->join('ms_kecamatan', 'ms_desa.fk_id_kecamatan = ms_kecamatan.id');
        $builder->join('ms_program', 'ms_desa.fk_program_id = ms_program.id');
        $builder->join('ms_kegiatan', 'ms_desa.fk_kegiatan_id = ms_kegiatan.id');
        $builder->join('ms_sub_kegiatan', 'ms_desa.fk_sub_kegiatan_id = ms_sub_kegiatan.id');
        
        // Use an associative array directly for where condition
        $query = $builder->getWhere(['ms_desa.id' => $id]);

        // Return the single row as an object
        return $query->getRow();
    }
}
