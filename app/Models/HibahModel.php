<?php

namespace App\Models;

use CodeIgniter\Model;

class HibahModel extends Model
{
    protected $table = 'ms_hibah';
    protected $useTimestamps = true;
    // protected $allowedFields = ['email', 'username'];

    public function get_all($kode_opd = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('ms_hibah mh');

        $builder->select('mh.*,	mk.nama_kabupaten, k.nama_kecamatan, d.nama_desa');
        $builder->join('ms_kabupaten mk', 'mh.fk_kabupaten_id = mk.id');
        $builder->join('ms_kecamatan k', 'mh.fk_kecamatan_id = k.id');
        $builder->join('ms_desa d', 'mh.fk_desa_id = d.id');

        if(!empty($kode_opd)){
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
        $builder->select('mh.*,	nama_kabupaten, nama_kecamatan, nama_desa, nama_program, nama_kegiatan, nama_sub_kegiatan');
        $builder->join('ms_kabupaten', 'mh.fk_kabupaten_id = ms_kabupaten.id');
        $builder->join('ms_kecamatan', 'mh.fk_kecamatan_id = ms_kecamatan.id');
        $builder->join('ms_desa', 'mh.fk_desa_id = ms_desa.id');
        $builder->join('ms_program', 'mh.fk_program_id = ms_program.id');
        $builder->join('ms_kegiatan', 'mh.fk_kegiatan_id = ms_kegiatan.id');
        $builder->join('ms_sub_kegiatan', 'mh.fk_sub_kegiatan_id = ms_sub_kegiatan.id');
        
        // Use an associative array directly for where condition
        $query = $builder->getWhere(['mh.id' => $id]);

        // Return the single row as an object
        return $query->getRow();
    }
}
