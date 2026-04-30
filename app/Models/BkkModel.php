<?php

namespace App\Models;

use CodeIgniter\Model;

class BkkModel extends Model
{
    protected $table = 'ms_desa';
    protected $useTimestamps = true;
    // protected $allowedFields = ['email', 'username'];

    public function get_all_opd()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('ms_opd');

        $builder->select('kode_opd, nama_opd');
        $builder->orderBy('nama_opd', 'ASC');

        $query = $builder->get();
        return $query->getResultArray();
    }

    //VIEW USULAN HIBAH BY ADMIN
    private function baseQueryviewbkk($kodeOpd, $tahun = null, $search = '')
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tb_usulan_bkk a')
            ->select("a.id,b.nama_desa,c.nama_kabupaten,d.nama_kecamatan,e.kode_user,a.apbd,a.perubahan_perbup_1,a.perubahan_perbup_2,a.papbd,e.fullname")
            ->join('ms_desa b', 'a.fk_ms_desa_id = b.id')
            ->join('ms_kabupaten c', 'b.fk_id_kabupaten = c.id')
            ->join('ms_kecamatan d', 'b.fk_id_kecamatan = d.id')            
            ->join('users e', 'a.created_by = e.id');

        if (!empty($kodeOpd) && $kodeOpd !== 'all') {
            $builder->where('e.kode_user', $kodeOpd);
        }

        if (!empty($tahun)) {
            $builder->where([
                'a.tahun' => $tahun
            ]);
        }

        if ($search !== '') {
            $builder->groupStart()
                ->like('b.nama_desa', $search)                
                ->orLike('e.fullname', $search)
                ->groupEnd();
        }

        return $builder;
    }

    public function count_all_view_bkk($kodeOpd, $tahun)
    {
        return $this->baseQueryviewbkk($kodeOpd, $tahun)->countAllResults(false);
    }

    public function count_filtered_view_bkk($kodeOpd, $tahun, $search)
    {
        return $this->baseQueryviewbkk($kodeOpd, $tahun, $search)->countAllResults(false);
    }

    public function get_page_view_bkk($kodeOpd, $tahun, $search, $orderBy, $orderDir, $limit, $offset)
    {
        return $this->baseQueryviewbkk($kodeOpd, $tahun, $search)
            ->orderBy($orderBy, $orderDir)
            ->limit($limit, $offset)
            ->get()->getResultArray();
    }
    //END VIEW USULAN HIBAH BY ADMIN
}
