<style>
    table,
    td,
    th {
        border: 1px solid #333;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    td,
    th {
        padding: 2px;
    }

    th {
        background-color: #CCC;
    }

    h1 {
        text-align: center;
    }

    h2 {
        text-align: center;
    }
    h3 {
        text-align: center;
    }
</style>

<h3>LAPORAN USULAN MUSRENBANG TAHUN <?= $tahun ?><br>
    <?= strtoupper($instansi) ?><br></h3>

<br>
<table>
    <thead>
        <tr>
            <th style="width: 20px">No</th>
            <th>ID Usulan</th>
            <th>Kecamatan</th>
            <th>Usulan</th>
            <th>Lokasi</th>
            <th>OPD</th>
            <th>Anggaran</th>
            <th>Status Pelaksanaan</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $no = 1;
            foreach ($data_usulan as $item) {
        ?>
        <tr>
            <td style="text-align: center; vertical-align: top;"><?= $no++ ?>.</td>
            <td style="text-align: center; vertical-align: top;"><?= $item['id_usulan'] ?></td>
            <td style="vertical-align: top;"><?= $item['kecamatan'] ?></td>
            <td><?= $item['masalah'] ?></td>
            <td style="vertical-align: top;"><?= $item['alamat'] ?></td>
            <td style="vertical-align: top;"><?= $item['opd_tujuan'] ?></td>
            <td style="vertical-align: top;"><?= number_format($item['perkiraan_anggaran'], 0, ',', '.') ?></td>
            <td style="text-align: center; vertical-align: top;">
                <?php 
                    if($item['status_pelaksanaan']=='1'){
                ?>
                <span style="color: green"><b>Sudah</b></span>
                <?php
                    }else{
                ?>
                <span style="color: red "><b>Belum</b></span>
                <?php
                    }
                ?>
            </td>
        </tr>
        <?php
            }
        ?>
    </tbody>
</table>