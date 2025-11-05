<?= $this->extend('layouts/template'); ?>

<?= $this->section('content'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Import Bansos</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Master</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('master/bansos') ?>">Bansos</a></li>
                        <li class="breadcrumb-item active">Import</li>
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
                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <form action="<?= site_url('import-excel-bansos/do') ?>" method="post" enctype="multipart/form-data">
                                <?= csrf_field() ?>
                                <div class="input-group">
                                    <input type="file" class="form-control" name="excel" accept=".xls,.xlsx" required>
                                    <span class="input-group-append">
                                    <button type="submit" class="btn btn-sm btn-success">Import</button>
                                </div>
                                <span class="text-success text-sm">Download template import excel <a href="<?= base_url('template/template_import_bansos.xlsx' )?>" class="text-success text-bold"><u>disini</u></a></span>
                            </form>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <?php
                } else{
            ?>
            <div class="alert alert-warning alert-dismissible">
                <h5><i class="icon fas fa-exclamation-triangle"></i> Perhatian!</h5>
                Anda belum memilih tahun anggaran. Data tidak akan tersinkron sebelum memilih tahun anggaran.
            </div>
            <?php
                }
            ?>
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
    const limaster = document.querySelector('.limaster');
    const ahrefmaster = document.querySelector('.ahrefmaster');
    const ahrefbansos = document.querySelector('.ahref-master-bansos');

    limaster.classList.add("menu-open");
    ahrefmaster.classList.add('active');
    ahrefbansos.classList.add('active');
</script>
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
<?= $this->endSection(); ?>
