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

    private function baseQuery($kodeOpd= '', $search = '')
    {
        $db = \Config\Database::connect();
        $builder = $db->table('ms_desa')
            ->select("ms_desa.id, nama_desa, nama_kabupaten, nama_kecamatan, ms_desa.kode_opd")
            ->join('ms_kabupaten a', 'ms_desa.fk_id_kabupaten = a.id')
            ->join('ms_kecamatan b', 'ms_desa.fk_id_kecamatan = b.id')
            ->join('ms_opd c', 'ms_desa.kode_opd = c.kode_opd', 'left');

        if (!empty($kodeOpd) && $kodeOpd !== 'all') {
            $builder->where('ms_desa.kode_opd', $kodeOpd);
        }

        if ($search !== '') {
            $builder->groupStart()
                ->like('ms_desa.nama_desa', $search)
                ->orLike('a.nama_kabupaten', $search)
                ->orLike('b.nama_kecamatan', $search)
                ->groupEnd();
        }

        return $builder;
    }

    public function count_all($kodeOpd)
    {
        return $this->baseQuery($kodeOpd)->countAllResults(false);
    }

    public function count_filtered($kodeOpd, $search)
    {
        return $this->baseQuery($kodeOpd, $search)->countAllResults(false);
    }

    public function get_page($kodeOpd, $search, $orderBy, $orderDir, $limit, $offset)
    {
        return $this->baseQuery($kodeOpd, $search)
            ->orderBy($orderBy, $orderDir)
            ->limit($limit, $offset)
            ->get()->getResultArray();
    }

    public function get_by_id($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('ms_desa');
        
        // Select all fields
        $builder->select('ms_desa.*, nama_kabupaten, nama_kecamatan, nama_program, nama_kegiatan, nama_sub_kegiatan, nama_opd');
        $builder->join('ms_kabupaten', 'ms_desa.fk_id_kabupaten = ms_kabupaten.id');
        $builder->join('ms_kecamatan', 'ms_desa.fk_id_kecamatan = ms_kecamatan.id');
        $builder->join('ms_program', 'ms_desa.fk_program_id = ms_program.id');
        $builder->join('ms_kegiatan', 'ms_desa.fk_kegiatan_id = ms_kegiatan.id');
        $builder->join('ms_sub_kegiatan', 'ms_desa.fk_sub_kegiatan_id = ms_sub_kegiatan.id');
        $builder->join('ms_opd', 'ms_desa.kode_opd = ms_opd.kode_opd', 'left');
        
        // Use an associative array directly for where condition
        $query = $builder->getWhere(['ms_desa.id' => $id]);

        // Return the single row as an object
        return $query->getRow();
    }

    public function get_all_opd()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('ms_opd');

        $builder->select('kode_opd, nama_opd');
        $builder->orderBy('nama_opd', 'ASC');

        $query = $builder->get();
        return $query->getResultArray();
    }

    public function get_all_usulan($user_id = null, $tahun = null, $search = '')
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tb_usulan_bkk a');

        $builder->select(
            "a.id, 
            b.nama_kabupaten,
            c.nama_kecamatan,
            d.nama_desa, 
            a.apbd, 
            a.perubahan_perbup_1, 
            a.perubahan_perbup_2, 
            a.papbd",
            false
        );
        $builder->join('ms_desa d', 'a.fk_ms_desa_id = d.id');
        $builder->join('ms_kabupaten b', 'd.fk_id_kabupaten = b.id');
        $builder->join('ms_kecamatan c', 'd.fk_id_kecamatan = c.id');

        if(!empty($user_id)){
            $builder->where([
                'a.created_by' => $user_id
            ]);
        }

        if(!empty($tahun)){
            $builder->where([
                'a.tahun' => $tahun
            ]);
        }

        if ($search !== '') {
            $builder->groupStart()
                ->orLike('b.nama_kabupaten', $search)
                ->orLike('c.nama_kecamatan', $search)
                ->orLike('d.nama_desa', $search)
                ->groupEnd();
        }

        return $builder;
    }

    public function count_all_usulan($user_id, $tahun)
    {
        return $this->get_all_usulan($user_id, $tahun)->countAllResults(false);
    }

    public function count_filtered_usulan($user_id, $tahun, $search)
    {
        return $this->get_all_usulan($user_id, $tahun, $search)->countAllResults(false);
    }

    public function get_page_usulan($user_id, $tahun, $search, $orderBy, $orderDir, $limit, $offset)
    {
        return $this->get_all_usulan($user_id, $tahun, $search)
            ->orderBy($orderBy, $orderDir)
            ->limit($limit, $offset)
            ->get()->getResultArray();
    }

    public function get_usulan_by_id($id)
    {
        $db = \Config\Database::connect();        
        // Select all fields
        $builder = $db->table('tb_usulan_bkk a');

        $builder->select('a.*, b.nama_desa, c.nama_kabupaten, d.nama_kecamatan');
        $builder->join('ms_desa b', 'a.fk_ms_desa_id = b.id');
        $builder->join('ms_kabupaten c', 'b.fk_id_kabupaten = c.id');
        $builder->join('ms_kecamatan d', 'b.fk_id_kecamatan = d.id');
        
        // Use an associative array directly for where condition
        $query = $builder->getWhere(['a.id' => $id]);

        // Return the single row as an object
        return $query->getRow();
    }

    public function get_usulan_by_ms_desa_id($id)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tb_usulan_bkk a');

        $builder->select('a.tahun, c.nama_opd')
                ->join('ms_desa b', 'a.fk_ms_desa_id = b.id')
                ->join('ms_opd c', 'b.kode_opd = c.kode_opd')
                ->where('a.fk_ms_desa_id', $id);

        return $builder;
    }
}
