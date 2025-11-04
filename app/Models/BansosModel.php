<?php

namespace App\Models;

use CodeIgniter\Model;

class BansosModel extends Model
{
    protected $table = 'ms_bansos';
    protected $useTimestamps = true;
    // protected $allowedFields = ['email', 'username'];

    public function get_all($kode_opd = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('ms_bansos mb');

        $builder->select('mb.*,	mk.nama_kabupaten, k.nama_kecamatan, d.nama_desa');
        $builder->join('ms_kabupaten mk', 'mb.fk_kabupaten_id = mk.id');
        $builder->join('ms_kecamatan k', 'mb.fk_kecamatan_id = k.id');
        $builder->join('ms_desa d', 'mb.fk_desa_id = d.id');

        if(!empty($kode_opd)){
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
        $builder->select('mb.*,	nama_kabupaten, nama_kecamatan, nama_desa, nama_program, nama_kegiatan, nama_sub_kegiatan');
        $builder->join('ms_kabupaten', 'mb.fk_kabupaten_id = ms_kabupaten.id');
        $builder->join('ms_kecamatan', 'mb.fk_kecamatan_id = ms_kecamatan.id');
        $builder->join('ms_desa', 'mb.fk_desa_id = ms_desa.id');
        $builder->join('ms_program', 'mb.fk_program_id = ms_program.id');
        $builder->join('ms_kegiatan', 'mb.fk_kegiatan_id = ms_kegiatan.id');
        $builder->join('ms_sub_kegiatan', 'mb.fk_sub_kegiatan_id = ms_sub_kegiatan.id');
        
        // Use an associative array directly for where condition
        $query = $builder->getWhere(['mb.id' => $id]);

        // Return the single row as an object
        return $query->getRow();
    }
}
