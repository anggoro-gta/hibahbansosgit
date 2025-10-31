<?= $this->extend('layouts/template'); ?>

<?= $this->section('content'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>INPUT TTD - <?= $nama_kecamatan; ?> (<?= $nama_bidang; ?>)</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Input TTD</li>
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
                    <h3 class="card-title">Input TTD</h3>

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
                <form action="/savettd" method="post" enctype="multipart/form-data">
                    <?= csrf_field(); ?>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Jenis</label>
                                    <select name="jenis" id="jenis" class="form-control">
                                        <option value="Bappeda">Bappeda</option>
                                        <option value="Kecamatan">Kecamatan</option>
                                        <option value="OPD">OPD</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Nama</label>
                                    <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama" value="" required>
                                    <input type="hidden" class="form-control" name="id_kecamatan" id="id_kecamatan" value="<?= $idkec; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Instansi</label>
                                    <input type="text" class="form-control" name="instansi" id="instansi" placeholder="instansi" value="" required>
                                </div>
                                <div class="form-group">
                                    <label>NIP</label>
                                    <input type="text" class="form-control" name="nip" id="nip" placeholder="nip" value="" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="" for="">Tanda Tangan Digital</label>
                                    <br />
                                    <div id="sig">                                        
                                    </div>
                                    <br><br>
                                    <button id="clear" class="btn btn-danger">Clear Signature</button>
                                    <!-- <button class="btn btn-success">Save</button> -->
                                    <textarea id="signature" name="signed" style="display: none"></textarea>
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
                    <a href="/detailttd/<?= $idkec; ?>"><button type="button" class="btn btn-secondary"><i class="fas fa-arrow-circle-left"></i> Kembali</button></a>
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

<script type="text/javascript">
    const sig = $('#sig').signature({
        syncField: '#signature',
        syncFormat: 'PNG'
    });


    $('#clear').click(function(e) {
        e.preventDefault();
        sig.signature('clear');
        $("#signature").val('');
    });
</script>

<?= $this->endSection(); ?>