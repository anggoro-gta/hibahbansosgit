<?php

namespace App\Models;

use CodeIgniter\Model;

class HibahModel extends Model
{
    protected $table = 'ms_hibah';
    protected $useTimestamps = true;
    // protected $allowedFields = ['email', 'username'];

    private function baseQuery($kodeOpd, $search = '')
    {
        $db = \Config\Database::connect();
        $builder = $db->table('ms_hibah mh')
            ->select("mh.id, mh.tgl_berdiri, mh.no_akta_hukum, mh.nama_lembaga, mh.kode_opd, CONCAT(mk.nama_kabupaten, ', ', k.nama_kecamatan, ', ', d.nama_desa, ', ', mh.alamat) AS alamat_full, nama_opd")
            ->join('ms_kabupaten mk', 'mh.fk_kabupaten_id = mk.id')
            ->join('ms_kecamatan k', 'mh.fk_kecamatan_id = k.id')
            ->join('ms_desa d', 'mh.fk_desa_id = d.id')
            ->join('ms_opd e', 'mh.kode_opd = e.kode_opd');

        if (!empty($kodeOpd) && $kodeOpd !== 'all') {
            $builder->where('mh.kode_opd', $kodeOpd);
        }

        if ($search !== '') {
            $builder->groupStart()
                ->like('mh.nama_lembaga', $search)
                ->orLike('mh.no_akta_hukum', $search)
                ->orLike('mh.alamat', $search)
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
        $builder = $db->table('ms_hibah mh');

        $builder->select('mh.*,	mk.nama_kabupaten, k.nama_kecamatan, d.nama_desa, e.nama_opd');
        $builder->join('ms_kabupaten mk', 'mh.fk_kabupaten_id = mk.id');
        $builder->join('ms_kecamatan k', 'mh.fk_kecamatan_id = k.id');
        $builder->join('ms_desa d', 'mh.fk_desa_id = d.id');
        $builder->join('ms_opd e', 'mh.kode_opd = e.kode_opd');

        if(!empty($kode_opd) && $kode_opd!='all'){
            $builder->where([
                'mh.kode_opd' => $kode_opd
            ]);
        }

        $builder->orderBy('mh.id', 'ASC');

        $query = $builder->get();
        return $query->getResultArray();
    }

    public function cek_no_akta($no_akta = null, $id = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('ms_hibah');  // Ganti dengan nama tabel yang sesuai

        // Menambahkan pengecekan jika ID ada
        if ($id) {
            $builder->where('no_akta_hukum', $no_akta)->where('id !=', $id);  // Cek no_akta yang sama, tetapi dengan ID yang berbeda
        } else {
            $builder->where('no_akta_hukum', $no_akta);  // Jika tidak ada ID (untuk tambah data), hanya cek no_akta
        }
        
        $query = $builder->get();
        
        return $query->getNumRows() > 0;  // Mengembalikan true jika no_akta sudah ada
    }

    public function get_by_id($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('ms_hibah mh');
        
        // Select all fields
        $builder->select('mh.*,	nama_kabupaten, nama_kecamatan, nama_desa, nama_program, nama_kegiatan, nama_sub_kegiatan, nama_opd');
        $builder->join('ms_kabupaten', 'mh.fk_kabupaten_id = ms_kabupaten.id');
        $builder->join('ms_kecamatan', 'mh.fk_kecamatan_id = ms_kecamatan.id');
        $builder->join('ms_desa', 'mh.fk_desa_id = ms_desa.id');
        $builder->join('ms_program', 'mh.fk_program_id = ms_program.id');
        $builder->join('ms_kegiatan', 'mh.fk_kegiatan_id = ms_kegiatan.id');
        $builder->join('ms_sub_kegiatan', 'mh.fk_sub_kegiatan_id = ms_sub_kegiatan.id');
        $builder->join('ms_opd', 'mh.kode_opd = ms_opd.kode_opd');
        
        // Use an associative array directly for where condition
        $query = $builder->getWhere(['mh.id' => $id]);

        // Return the single row as an object
        return $query->getRow();
    }

    public function get_all_usulan($user_id = null, $tahun = null, $search = '')
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tb_usulan_hibah a');

        $builder->select(
            "a.id, 
            b.nama_lembaga,
            b.no_akta_hukum,  
            a.apbd, 
            a.perubahan_perbup_1, 
            a.perubahan_perbup_2, 
            a.papbd, 
            CONCAT(c.nama_kabupaten, ', ', d.nama_kecamatan, ', ', e.nama_desa, ', ', b.alamat) AS alamat_full",
            false
        );
        $builder->join('ms_hibah b', 'a.fk_ms_hibah_id = b.id');
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
                ->like('b.nama_lembaga', $search)
                ->orLike('b.no_akta_hukum', $search)
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

    public function get_layak_usulan($kode_opd=null, $tahun)
    {
        $builder = $this->builder('ms_hibah a');

        $builder->select('a.*, b.nama_kabupaten, c.nama_kecamatan, d.nama_desa, e.kode_opd, e.nama_opd')
                ->join('ms_kabupaten b', 'a.fk_kabupaten_id = b.id', 'left')
                ->join('ms_kecamatan c', 'a.fk_kecamatan_id = c.id', 'left')
                ->join('ms_desa d', 'a.fk_desa_id = d.id', 'left')
                ->join('ms_opd e', 'a.kode_opd = e.kode_opd');

        // minimal sudah 2 tahun
        $builder->where('a.tgl_berdiri IS NOT NULL', null, false);
        // $builder->where('a.tgl_berdiri <= DATE_SUB(CURDATE(), INTERVAL 2 YEAR)', null, false);

        // $builder->where('a.tgl_berdiri < CURDATE()', null, false);

        $tahun_select = $tahun;
        $tahun_kemarin = $tahun-1;
        $tahun_berikutnya = $tahun+1;
        
        // $builder->where("
        //     NOT EXISTS (
        //         SELECT 1
        //         FROM tb_usulan_hibah u
        //         WHERE u.fk_ms_hibah_id = a.id
        //         AND u.tahun IN ('$tahun_kemarin', '$tahun_select', '$tahun_berikutnya')
        //     )
        // ", null, false);

        $builder->where("
            NOT EXISTS (
                SELECT 1
                FROM tb_usulan_hibah u
                WHERE u.fk_ms_hibah_id = a.id
                AND u.tahun IN ('$tahun_select')
            )
        ", null, false);

        if ($kode_opd) {
            $builder->where('a.kode_opd', $kode_opd);
        }

        return $builder->get()->getResultArray();
    }

    public function get_usulan_by_id($id)
    {
        $db = \Config\Database::connect();        
        // Select all fields
        $builder = $db->table('tb_usulan_hibah a');

        $builder->select('a.*, b.nama_lembaga, b.tgl_berdiri, b.no_akta_hukum');
        $builder->join('ms_hibah b', 'a.fk_ms_hibah_id = b.id');
        
        // Use an associative array directly for where condition
        $query = $builder->getWhere(['a.id' => $id]);

        // Return the single row as an object
        return $query->getRow();
    }

    public function get_dokumen($table_name, $table_id)
    {
        $db = \Config\Database::connect();        
        // Select all fields
        $builder = $db->table('dokumen');
        // Use an associative array directly for where condition
        $query = $builder->getWhere(['table_name' => $table_name, 'table_id' => $table_id]);
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

    public function get_usulan_by_ms_hibah_id($id)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tb_usulan_hibah a');

        $builder->select('a.tahun, b.fullname AS nama_opd')
                ->join('users b', 'a.created_by = b.id')
                ->where('a.fk_ms_hibah_id', $id);

        return $builder;
    }
}
