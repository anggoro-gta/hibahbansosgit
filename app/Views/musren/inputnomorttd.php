<?= $this->extend('layouts/template'); ?>

<?= $this->section('content'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>INPUT NOMOR TTD - <?= $nama_kecamatan; ?> (<?= $nama_bidang; ?>)</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Input NOMOR TTD</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- SELECT2 EXAMPLE -->
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Input NOMOR TTD</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <!-- <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button> -->
                    </div>
                </div>
                <!-- /.card-header -->
                <form action="/savenomorttd" method="post" enctype="multipart/form-data">
                    <?= csrf_field(); ?>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">                                
                                <div class="form-group">
                                    <label>Nomor Surat</label>
                                    <input type="number" class="form-control" name="nomor" id="nomor" placeholder="nomor surat" value="" required>
                                    <input type="hidden" class="form-control" name="id_kecamatan" id="id_kecamatan" value="<?= $idkec; ?>">
                                </div>

                            </div>
                        </div>
                        <!-- /.row -->
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-edit"></i> Simpan</button>
                    </div>
                </form>
                <div class="card-footer">
                    <a href="/nomorttd/<?= $idkec; ?>"><button type="button" class="btn btn-secondary"><i class="fas fa-arrow-circle-left"></i> Kembali</button></a>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?= $this->endSection(); ?>

<?= $this->section('javascriptkhusus'); ?>
<script>
    const liformbidangadmin = document.querySelector('.liformbidangadmin');
    const ahrefformbidangdamin = document.querySelector('.ahrefformbidangdamin');
    const ahrefbidangadmin = document.querySelector('.ahrefbidangadmin');

    liformbidangadmin.classList.add("menu-open");
    ahrefformbidangdamin.classList.add("active");
    ahrefbidangadmin.classList.add("active");
</script>

<?= $this->endSection(); ?>