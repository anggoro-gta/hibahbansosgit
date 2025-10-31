<?= $this->extend('layouts/template'); ?>

<?= $this->section('content'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Ganti Password by admin</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Ganti Password</li>
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

                <!-- SELECT2 EXAMPLE -->
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Reset Password</h3>

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
                    <form action="/home/updatepasswordbyadmin" method="post" enctype="multipart/form-data">
                        <?= csrf_field(); ?>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>fullname</label>
                                        <input type="text" class="form-control" name="fullname" id="fullname" value="<?= (old('fullname')) ? old('fullname') : $fullname ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>password</label>
                                        <input type="hidden" class="form-control" name="iddata" id="iddata" value="<?= (old('iddata')) ? old('iddata') : $iddata ?>">
                                        <input type="password" class="form-control <?= ($validation->hasError('password1')) ? 'is-invalid' : ''; ?>" name="password1" id="password1" placeholder="Password" value="<?= (old('password1')) ? old('password1') : '' ?>" required>
                                        <div class="error invalid-feedback">
                                            <?= $validation->getError('password1'); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Ketikan Ulang Password</label>
                                        <input type="password" class="form-control <?= ($validation->hasError('password2')) ? 'is-invalid' : ''; ?>" name="password2" id="password2" placeholder="Password" value="<?= (old('password2')) ? old('password2') : '' ?>" required>
                                        <div class="error invalid-feedback">
                                            <?= $validation->getError('password2'); ?>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- /.row -->
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-edit"></i> Ubah</button>
                        </div>
                    </form>
                    <div class="card-footer">
                        <a href="/indexusers"><button type="button" class="btn btn-secondary"><i class="fas fa-arrow-circle-left"></i> Kembali</button></a>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
        </div>
        <!-- /.container-fluid -->

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

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?= $this->endSection(); ?>

<?= $this->section('javascriptkhusus'); ?>
<script>
    const lisetting = document.querySelector('.lisetting');
    const ahrefsetting = document.querySelector('.ahrefsetting');
    const ahrefsettingusers = document.querySelector('.ahrefsettingusers');

    lisetting.classList.add("menu-open");
    ahrefsetting.classList.add("active");
    ahrefsettingusers.classList.add("active");
</script>
<?= $this->endSection(); ?>