<?php

namespace App\Models;

use CodeIgniter\Model;

class BansosModel extends Model
{
    protected $table = 'ms_bansos';
    protected $useTimestamps = true;
    // protected $allowedFields = ['email', 'username'];

    private function baseQuery($kodeOpd, $search = '')
    {
        $db = \Config\Database::connect();
        $builder = $db->table('ms_bansos mb')
            ->select("mb.id, mb.nik, mb.nama, mb.kode_opd, CONCAT(mk.nama_kabupaten, ', ', k.nama_kecamatan, ', ', d.nama_desa, ', ', mb.alamat) AS alamat_full, nama_opd")
            ->join('ms_kabupaten mk', 'mb.fk_kabupaten_id = mk.id')
            ->join('ms_kecamatan k', 'mb.fk_kecamatan_id = k.id')
            ->join('ms_desa d', 'mb.fk_desa_id = d.id')
            ->join('ms_opd e', 'mb.kode_opd = e.kode_opd');

        if (!empty($kodeOpd) && $kodeOpd !== 'all') {
            $builder->where('mb.kode_opd', $kodeOpd);
        }

        if ($search !== '') {
            $builder->groupStart()
                ->like('mb.nama', $search)
                ->orLike('mb.nik', $search)
                ->orLike('mb.alamat', $search)
                ->orLike('mk.nama_kabupaten', $search)
                ->orLike('k.nama_kecamatan', $search)
                ->orLike('d.nama_desa', $search)
                ->orLike('e.nama_opd', $search)
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

    public function get_all($kode_opd = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('ms_bansos mb');

        $builder->select('mb.*,	mk.nama_kabupaten, k.nama_kecamatan, d.nama_desa, e.nama_opd');
        $builder->join('ms_kabupaten mk', 'mb.fk_kabupaten_id = mk.id');
        $builder->join('ms_kecamatan k', 'mb.fk_kecamatan_id = k.id');
        $builder->join('ms_desa d', 'mb.fk_desa_id = d.id');
        $builder->join('ms_opd e', 'mb.kode_opd = e.kode_opd');

        if(!empty($kode_opd) && $kode_opd!='all'){
            $builder->where([
                'mb.kode_opd' => $kode_opd
            ]);
        }

        $builder->orderBy('mb.id', 'ASC');

        $query = $builder->get();
        return $query->getResultArray();
    }

    public function cek_nik($nik = null, $id = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('ms_bansos');  // Ganti dengan nama tabel yang sesuai

        // Menambahkan pengecekan jika ID ada
        if ($id) {
            $builder->where('nik', $nik)->where('id !=', $id);  // Cek NIK yang sama, tetapi dengan ID yang berbeda
        } else {
            $builder->where('nik', $nik);  // Jika tidak ada ID (untuk tambah data), hanya cek NIK
        }
        
        $query = $builder->get();
        
        return $query->getNumRows() > 0;  // Mengembalikan true jika NIK sudah ada
    }

    public function get_by_id($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('ms_bansos mb');
        
        // Select all fields
        $builder->select('mb.*,	nama_kabupaten, nama_kecamatan, nama_desa, nama_program, nama_kegiatan, nama_sub_kegiatan, nama_opd');
        $builder->join('ms_kabupaten', 'mb.fk_kabupaten_id = ms_kabupaten.id');
        $builder->join('ms_kecamatan', 'mb.fk_kecamatan_id = ms_kecamatan.id');
        $builder->join('ms_desa', 'mb.fk_desa_id = ms_desa.id');
        $builder->join('ms_program', 'mb.fk_program_id = ms_program.id');
        $builder->join('ms_kegiatan', 'mb.fk_kegiatan_id = ms_kegiatan.id');
        $builder->join('ms_sub_kegiatan', 'mb.fk_sub_kegiatan_id = ms_sub_kegiatan.id');
        $builder->join('ms_opd', 'mb.kode_opd = ms_opd.kode_opd');
        
        // Use an associative array directly for where condition
        $query = $builder->getWhere(['mb.id' => $id]);

        // Return the single row as an object
        return $query->getRow();
    }

    public function get_all_usulan($user_id = null, $tahun = null, $search = '')
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tb_usulan_bansos a');

        $builder->select(
            "a.id, 
            b.nama, 
            b.nik,
            a.apbd, 
            a.perubahan_perbup_1, 
            a.perubahan_perbup_2, 
            a.papbd, 
            CONCAT(c.nama_kabupaten, ', ', d.nama_kecamatan, ', ', e.nama_desa, ', ', b.alamat) AS alamat_full,
            b.alamat,
            d.kode_ref_sipd_kec,
            e.kode_ref_sipd_desa",
            false
        );
        $builder->join('ms_bansos b', 'a.fk_ms_bansos_id = b.id');
        $builder->join('ms_kabupaten c', 'b.fk_kabupaten_id = c.id');
        $builder->join('ms_kecamatan d', 'b.fk_kecamatan_id = d.id');
        $builder->join('ms_desa e', 'b.fk_desa_id = e.id');

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
                ->like('b.nama', $search)
                ->orLike('b.nik', $search)
                ->orLike('b.alamat', $search)
                ->orLike('c.nama_kabupaten', $search)
                ->orLike('d.nama_kecamatan', $search)
                ->orLike('e.nama_desa', $search)
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

    public function get_layak_usulan($tahun)
    {
        $builder = $this->builder('ms_bansos a');

        $builder->select('a.*, b.nama_kabupaten, c.nama_kecamatan, d.nama_desa')
                ->join('ms_kabupaten b', 'a.fk_kabupaten_id = b.id', 'left')
                ->join('ms_kecamatan c', 'a.fk_kecamatan_id = c.id', 'left')
                ->join('ms_desa d', 'a.fk_desa_id = d.id', 'left');

        $tahun_select = $tahun;
        $tahun_kemarin = $tahun-1;
        $tahun_berikutnya = $tahun+1;
        
        // $builder->where("
        //     NOT EXISTS (
        //         SELECT 1
        //         FROM tb_usulan_bansos u
        //         WHERE u.fk_ms_bansos_id = a.id
        //         AND u.tahun IN ('$tahun_kemarin', '$tahun_select', '$tahun_berikutnya')
        //     )
        // ", null, false);

        $builder->where("
            NOT EXISTS (
                SELECT 1
                FROM tb_usulan_bansos u
                WHERE u.fk_ms_bansos_id = a.id
                AND u.tahun IN ('$tahun_select')
            )
        ", null, false);

        return $builder->get()->getResultArray();
    }

    public function get_usulan_by_id($id)
    {
        $db = \Config\Database::connect();        
        // Select all fields
        $builder = $db->table('tb_usulan_bansos a');

        $builder->select('a.*, b.nama, b.nik');
        $builder->join('ms_bansos b', 'a.fk_ms_bansos_id = b.id');
        
        // Use an associative array directly for where condition
        $query = $builder->getWhere(['a.id' => $id]);

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

    public function get_usulan_by_ms_bansos_id($id)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tb_usulan_bansos a');

        $builder->select('a.tahun, , b.fullname AS nama_opd')
                ->join('users b', 'a.created_by = b.id')
                ->where('a.fk_ms_bansos_id', $id);

        return $builder;
    }

    public function countallusulanbansosbytahun($tahun)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tb_usulan_bansos');

        $builder->select('*');

        if (!empty($tahun)) {
            $builder->where([
                'tahun' => $tahun
            ]);
        }

        $query = $builder->get();
        return count($query->getResultArray());
    }

    public function countallusulanbansosbytahundanuserid($tahun, $userid)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tb_usulan_bansos');

        $builder->select('*');

        if (!empty($tahun)) {
            $builder->where([
                'tahun' => $tahun,
                'created_by' => $userid
            ]);
        }

        $query = $builder->get();
        return count($query->getResultArray());
    }

    //VIEW USULAN BANSOS BY ADMIN
    private function baseQueryviewbansos($kodeOpd, $tahun = null, $search = '')
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tb_usulan_bansos a')
            ->select("a.id,b.nama,b.nik,f.kode_user,a.apbd,a.perubahan_perbup_1,a.perubahan_perbup_2,a.papbd,f.fullname,CONCAT(c.nama_kabupaten, ', ', d.nama_kecamatan, ', ', e.nama_desa, ', ', b.alamat) AS alamat_full")
            ->join('ms_bansos b', 'a.fk_ms_bansos_id = b.id')
            ->join('ms_kabupaten c', 'b.fk_kabupaten_id = c.id')
            ->join('ms_kecamatan d', 'b.fk_kecamatan_id = d.id')
            ->join('ms_desa e', 'b.fk_desa_id = e.id')
            ->join('users f', 'a.created_by = f.id');

        if (!empty($kodeOpd) && $kodeOpd !== 'all') {
            $builder->where('f.kode_user', $kodeOpd);
        }

        if (!empty($tahun)) {
            $builder->where([
                'a.tahun' => $tahun
            ]);
        }

        if ($search !== '') {
            $builder->groupStart()
                ->like('b.nama', $search)
                ->orLike('b.nik', $search)
                ->orLike('f.kode_user', $search)
                ->groupEnd();
        }

        return $builder;
    }

    public function count_all_view_bansos($kodeOpd, $tahun)
    {
        return $this->baseQueryviewbansos($kodeOpd, $tahun)->countAllResults(false);
    }

    public function count_filtered_view_bansos($kodeOpd, $tahun, $search)
    {
        return $this->baseQueryviewbansos($kodeOpd, $tahun, $search)->countAllResults(false);
    }

    public function get_page_view_bansos($kodeOpd, $tahun, $search, $orderBy, $orderDir, $limit, $offset)
    {
        return $this->baseQueryviewbansos($kodeOpd, $tahun, $search)
            ->orderBy($orderBy, $orderDir)
            ->limit($limit, $offset)
            ->get()->getResultArray();
    }
    //END VIEW USULAN BANSOS BY ADMIN
}
