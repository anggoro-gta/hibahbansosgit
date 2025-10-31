<?= $this->extend('layouts/template'); ?>

<?= $this->section('content'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Musrenbang RKPD 2026 (<?= $nama_bidang; ?>)</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Data</a></li>
                        <li class="breadcrumb-item active">Usulan Musrenbang</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <!-- <div class="card-header">
                            <a href="/entrytujuanpd/tambahtujuanpd"><button type="submit" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Tambah Data Tujuan Perangkat Daerah</button></a>
                        </div> -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>no</th>
                                        <th>Kecamatan</th>
                                        <th>Total Usulan</th>
                                        <th>Total Verifikasi</th>
                                        <th>Verifikasi</th>
                                        <th>TTD</th>
                                        <th>Print</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $hitungkec = count($datakecamatan); ?>
                                    <?php for ($i = 0; $i < $hitungkec; $i++) : ?>
                                        <tr>
                                            <td><?= $i + 1; ?></td>
                                            <td><?= $datakecamatan[$i]['nama_kecamatan']; ?></td>
                                            <td><?= $datakecamatan[$i]['jumlah_usulan']; ?></td>
                                            <td><?= $datakecamatan[$i]['jumlah_validasi']; ?></td>
                                            <td>
                                                <a href="/detailusulan/<?= $datakecamatan[$i]['id']; ?>"><button type="button" class="btn btn-block btn-info"><i class="fa fa-check"></i> verifikasi</button></a> </br>
                                                <a href="/detailprior/<?= $datakecamatan[$i]['id']; ?>"><button type="button" class="btn btn-block btn-info"><i class="fa fa-check"></i> prior</button></a>
                                            </td>
                                            <td>
                                                <a href="/detailttd/<?= $datakecamatan[$i]['id']; ?>"><button type="button" class="btn btn-block btn-info"><i class="fa fa-file"></i> ttd</button></a>
                                                </br><a href="/nomorttd/<?= $datakecamatan[$i]['id']; ?>"><button type="button" class="btn btn-block btn-info"><i class="fa fa-file"></i> nomor</button></a>
                                            </td>
                                            <td>
                                                <a href="/printbasidangkelompok/<?= $datakecamatan[$i]['id']; ?>" target="_blank"><button type="button" class="btn btn-block btn-secondary"><i class="fa fa-print"></i> BA</button></a> </br>
                                                <a href="/printlampiransdgkel/<?= $datakecamatan[$i]['id']; ?>" target="_blank"><button type="button" class="btn btn-block btn-secondary"><i class="fa fa-print"></i> Lampiran I</button></a> </br>
                                                <a href="/printlampiransdgkelx/<?= $datakecamatan[$i]['id']; ?>" target="_blank"><button type="button" class="btn btn-block btn-secondary"><i class="fa fa-print"></i> Lampiran II</button></a>
                                            </td>
                                        </tr>
                                    <?php endfor; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>no</th>
                                        <th>Kecamatan</th>
                                        <th>Total Usulan</th>
                                        <th>Total Verifikasi</th>
                                        <th>Verifikasi</th>
                                        <th>TTD</th>
                                        <th>Print</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Back to top button -->
<button type="button" class="btn btn-danger btn-floating btn-lg" id="btn-back-to-top">
    <i class="fas fa-arrow-up"></i>
</button>


<?= $this->endSection(); ?>

<?= $this->section('javascriptkhusus'); ?>
<script>
    //Get the button
    const mybutton = document.getElementById("btn-back-to-top");

    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function() {
        scrollFunction();
    };

    function scrollFunction() {
        if (
            document.body.scrollTop > 20 ||
            document.documentElement.scrollTop > 20
        ) {
            mybutton.style.display = "block";
        } else {
            mybutton.style.display = "none";
        }
    }
    // When the user clicks on the button, scroll to the top of the document
    mybutton.addEventListener("click", backToTop);

    function backToTop() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }
</script>

<script>
    $(function() {
        $("#example1").DataTable({
            // "lengthChange": true,
            "responsive": true,
            "autoWidth": false,
            "ordering": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "lengthMenu": [
                [30, 40, 50, -1],
                [30, 40, 50, "All"]
            ]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });

    const liformbidangadmin = document.querySelector('.liformbidangadmin');
    const ahrefformbidangdamin = document.querySelector('.ahrefformbidangdamin');
    const ahrefbidangadmin = document.querySelector('.ahrefbidangadmin');

    liformbidangadmin.classList.add("menu-open");
    ahrefformbidangdamin.classList.add("active");
    ahrefbidangadmin.classList.add("active");
</script>

<?= $this->endSection(); ?>