<?= $this->extend('layouts/template'); ?>

<?= $this->section('content'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>SIPD Bansos</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">SIPD Bansos</a></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <?php
            if (isset($_SESSION['years'])) {
            ?>
            <div class="row">
                <?php
                    $disabled = 'disabled';
                    if($jml_usulan>0){
                        $disabled = '';
                    }else{
                ?>
                <div class="col-12">
                    <div class="alert alert-warning alert-dismissible">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Informasi !</h5>
                        Belum ada data usulan bansos yang diinput pada tahun <?= $_SESSION['years'] ?>.
                    </div>
                </div>
                <?php
                    }
                ?>
                <div class="col-5">
                    <div class="card">
                        <form method="POST" action="<?= base_url('sipd/bansos/export-excel') ?>">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Jenis Anggaran <sup class="text-danger">*</sup></label>
                                    <select class="form-control select2" name="jenis_anggaran" id="jenis_anggaran" <?= $disabled ?> required>
                                        <option value="">Pilih</option>
                                        <option value="apbd">APBD</option>
                                        <option value="perubahan_perbup_1">Perubahan PERBUP 1</option>
                                        <option value="perubahan_perbup_2">Perubahan PERBUP 2</option>
                                        <option value="papbd">P-APBD</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Pemaketan/Pengelompokan SIPD <sup class="text-danger">*</sup></label>
                                    <textarea class="form-control" name="pemaketan_sipd" id="pemaketan_sipd"  placeholder="Input disini..." <?= $disabled ?> required></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Keterangan SIPD <sup class="text-danger">*</sup></label>
                                    <textarea class="form-control" name="keterangan_sipd" id="keterangan_sipd"  placeholder="Input disini..." <?= $disabled ?> required></textarea>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-sm btn-success" <?= $disabled ?>><i class="fa fa-file-excel mr-1"></i>Export Excel</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
            <?php
            } else {
            ?>
                <div class="alert alert-warning alert-dismissible">
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Perhatian!</h5>
                    Anda belum memilih tahun anggaran. Data tidak akan tersinkron sebelum memilih tahun anggaran.
                </div>
            <?php
            }
            ?>
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
    const liparent = document.querySelector('.lisipd');
    const ahrefparent = document.querySelector('.ahrefsipd');
    const ahrefsipdbansos = document.querySelector('.ahref-sipd-bansos');

    liparent.classList.add("menu-open");
    ahrefparent.classList.add('active');
    ahrefsipdbansos.classList.add('active');
</script>
<script>
    //Get the button
    const mybutton = document.getElementById("btn-back-to-top");

    const old_kecamatan = '<?= $kecamatan ?? '' ?>';
    const old_desa = '<?= $desa ?? '' ?>';
    const old_kegiatan = '<?= $kegiatan ?? '' ?>';
    const old_sub_kegiatan = '<?= $sub_kegiatan ?? '' ?>';

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
        $('.select2').select2();
    });
</script>


<?= $this->endSection(); ?>