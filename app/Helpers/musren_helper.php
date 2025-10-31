<?php
use Config\Database;

if (! function_exists('musren_years')) {
    /**
     * Ambil daftar tahun unik dari tb_usulan_musren (diurut DESC).
     * Hasil di-cache 5 menit agar hemat query.
     * @return int[]
     */
    function musren_years(): array
    {
        // cache 300 detik
        return cache()->remember('musren_years', 300, static function () {
            $db = Database::connect();
            $rows = $db->table('tb_usulan_musren')
                ->select('tahun')
                ->where('tahun IS NOT NULL', null, false)
                ->where('tahun <> ""', null, false)
                ->groupBy('tahun')
                ->orderBy('tahun', 'ASC')
                ->get()
                ->getResultArray();

            return array_values(array_map(static fn($r) => (int)$r['tahun'], $rows));
        });
    }
}

if (! function_exists('musren_years_dropdown')) {
    /**
     * Cetak <option> untuk dropdown tahun (langsung echo).
     * @param string|null $selected
     */
    function musren_years_dropdown(?string $selected = null): void
    {
        foreach (musren_years() as $th) {
            $sel = ((string)$th === (string)$selected) ? ' selected' : '';
            echo '<option value="'.esc($th).'"'.$sel.'>'.esc($th).'</option>';
        }
    }
}