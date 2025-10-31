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
</style>

<p><b><u>Lampiran II</u></b> <br>
    Nomor &nbsp;&nbsp;: 000.7.1/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/418.54/2024 <br>
    Tanggal : 5 Maret 2024</p>

<h2>Usulan Musrenbang RKPD 2025<br>
    Bidang : <?= $namabidang; ?></h2>
<br>
<table>
    <thead>
        <tr>
            <th style="width: 20px">No</th>
            <th>ID Usulan</th>
            <th>Kecamatan</th>
            <th>Kamus Usulan</th>
            <th>Permasalahan</th>
            <th>Lokasi Usulan</th>
            <th>OPD</th>
            <th>Hasil Pembahasan</th>
            <th>Catatan</th>
            <!-- <th style="width: 100px">Rata-rata</th> -->
        </tr>
    </thead>
    <tbody>
        <?php $hitungusulan = count($datausulan); ?>
        <?php $hitungkecamatan = count($datakecamatan) ?>
        <?php for ($i = 0; $i < $hitungkecamatan; $i++) { ?>
            <?php $k = 0; ?>
            <tr>
                <td colspan="9" style="text-align: center; vertical-align: middle; background-color:#778DA9;"><b><?= $datakecamatan[$i]['nama_kecamatan']; ?></b></td>
            </tr>
            <?php for ($j = 0; $j < $hitungusulan; $j++) : ?>
                <?php if ($datausulan[$j]['id_kecamatan'] == $datakecamatan[$i]['id']) { ?>
                    <tr>
                        <td><?= $k += 1; ?></td>
                        <td><?= $datausulan[$j]['id_usulan']; ?></td>
                        <td><?= $datausulan[$j]['kecamatan']; ?></td>
                        <td><?= $datausulan[$j]['kamus_usulan']; ?></td>
                        <td><?= $datausulan[$j]['masalah']; ?></td>
                        <td><?= $datausulan[$j]['alamat']; ?></td>
                        <td><?= $datausulan[$j]['opd_tujuan']; ?></td>
                        <?php if ($datausulan[$j]['status'] == 0) { ?>
                            <td>Tidak Diakomodir</td>
                        <?php } else if ($datausulan[$j]['status'] == 1) { ?>
                            <td>Diakomodir</td>
                        <?php } else { ?>
                            <td>Belum Diproses</td>
                        <?php } ?>
                        <td><?= $datausulan[$j]['catatan']; ?></td>
                    </tr>
                <?php } ?>
            <?php endfor; ?>
        <?php } ?>
    </tbody>
</table>