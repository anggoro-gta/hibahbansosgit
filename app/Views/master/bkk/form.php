<?= $this->extend('layouts/template'); ?>

<?= $this->section('content'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= $button ?> Master BKK</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Master</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('master/bkk') ?>">BKK</a></li>
                        <li class="breadcrumb-item active"><?= $button ?></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-6">
                    <div class="card">
                        <form method="POST" action="<?= $url ?>">
                            <input type="hidden" class="form-control" name="id" id="id" required value="<?= $id ?>">
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label>Nama Desa<sup class="text-danger">*</sup></label>
                                        <input type="text" class="form-control" name="nama_desa" id="nama_desa" required value="<?= $nama_desa ?>">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Kabupaten <sup class="text-danger">*</sup></label>
                                        <select class="form-control select2" name="kabupaten" id="kabupaten" required>
                                            <option value="">Pilih Kabupaten</option>
                                            <?php foreach ($ref_kabupaten as $item) : ?>
                                            <option value="<?= $item['id'] ?>" <?= $kabupaten==$item['id'] ? 'selected' : '' ?>><?= $item['nama_kabupaten'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Kecamatan <sup class="text-danger">*</sup></label>
                                        <select class="form-control select2" name="kecamatan" id="kecamatan" required>
                                            <option value="">Pilih Kecamatan</option>
                                            <?php foreach ($ref_kecamatan as $item) : ?>
                                            <option value="<?= $item['id'] ?>" <?= $kecamatan==$item['id'] ? 'selected' : '' ?>><?= $item['nama_kecamatan'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Program <sup class="text-danger">*</sup></label>
                                        <select class="form-control select2" name="program" id="program" required>
                                            <option value="">Pilih Program</option>
                                            <?php foreach ($ref_program as $item) : ?>
                                            <option value="<?= $item['id'] ?>" <?= $program==$item['id'] ? 'selected' : '' ?>><?= $item['nama_program'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Kegiatan <sup class="text-danger">*</sup></label>
                                        <select class="form-control select2" name="kegiatan" id="kegiatan" required>
                                            <option value="">Pilih Kegiatan</option>
                                            <?php foreach ($ref_kegiatan as $item) : ?>
                                            <option value="<?= $item['id'] ?>" <?= $kegiatan==$item['id'] ? 'selected' : '' ?>><?= $item['nama_kegiatan'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Sub Kegiatan <sup class="text-danger">*</sup></label>
                                        <select class="form-control select2" name="sub_kegiatan" id="sub_kegiatan" required>
                                            <option value="">Pilih Sub Kegiatan</option>
                                            <?php foreach ($ref_sub_kegiatan as $item) : ?>
                                            <option value="<?= $item['id'] ?>" <?= $sub_kegiatan==$item['id'] ? 'selected' : '' ?>><?= $item['nama_sub_kegiatan'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer text-right">
                                <a href="<?= base_url('master/bkk') ?>" class="btn btn-secondary">Batal</a>
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </form>
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
    const limaster = document.querySelector('.limaster');
    const ahrefmaster = document.querySelector('.ahrefmaster');
    const ahrefchild = document.querySelector('.ahref-master-bkk');

    limaster.classList.add("menu-open");
    ahrefmaster.classList.add('active');
    ahrefchild.classList.add('active');
</script>
<script>
    //Get the button
    const mybutton = document.getElementById("btn-back-to-top");

    const old_kecamatan = '<?= $kecamatan ?? '' ?>';
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
    $(document).ready(function() {
        $('.select2').select2();  // Inisialisasi Select2 untuk semua elemen dengan class select2

        $('#kabupaten').change(function() {
            var kab_id = $(this).val();
            
            if (kab_id) {
                // Menampilkan opsi "Memuat.." di dropdown kecamatan
                $('#kecamatan').empty().append('<option value="">Memuat...</option>');
                
                // AJAX request untuk mendapatkan kecamatan berdasarkan kabupaten
                $.ajax({
                    url: "<?= site_url('master/kecamatan') ?>/" + kab_id, // Sesuaikan URL dengan route yang ada
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        // Kosongkan dropdown kecamatan dan tambahkan opsi "Pilih Kecamatan"
                        $('#kecamatan').empty().append('<option value="">Pilih Kecamatan</option>');

                        // Masukkan opsi kecamatan ke dalam dropdown
                        if (data.results.length > 0) {
                            $.each(data.results, function(index, item) {
                                var selected = (item.id == old_kecamatan) ? 'selected' : ''; // Cek apakah ini kecamatan yang dipilih
                                $('#kecamatan').append('<option value="' + item.id + '" ' + selected + '>' + item.text + '</option>');
                            });
                        } else {
                            // Jika tidak ada kecamatan, tampilkan pesan
                            $('#kecamatan').append('<option value="">Tidak ada kecamatan ditemukan</option>');
                        }

                        // Refresh select2 untuk memperbarui tampilan
                        $('#kecamatan').trigger('change');
                    },
                    error: function(xhr, status, error) {
                        // Menangani error jika AJAX gagal
                        $('#kecamatan').empty().append('<option value="">Error memuat data kecamatan</option>');
                        console.log('AJAX Error: ' + status + ' - ' + error); // Debugging error
                    }
                });
            } else {
                // Jika kabupaten tidak dipilih, kosongkan kecamatan
                $('#kecamatan').empty().append('<option value="">Pilih Kecamatan</option>');
                $('#desa').empty().append('<option value="">Pilih Desa</option>');
            }
        });

        $('#program').change(function() {
            var program_id = $(this).val();
            
            if (program_id) {
                $('#kegiatan').empty().append('<option value="">Memuat...</option>');
                
                $.ajax({
                    url: "<?= site_url('master/kegiatan') ?>/" + program_id, // Sesuaikan URL dengan route yang ada
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#kegiatan').empty().append('<option value="">Pilih Kegiatan</option>');

                        if (data.results.length > 0) {
                            $.each(data.results, function(index, item) {
                                var selected = (item.id == old_kegiatan) ? 'selected' : ''; // Cek apakah ini kecamatan yang dipilih
                                $('#kegiatan').append('<option value="' + item.id + '" ' + selected + '>' + item.text + '</option>');
                            });
                        } else {
                            $('#kegiatan').append('<option value="">Tidak ada kegiatan ditemukan</option>');
                        }

                        // Refresh select2 untuk memperbarui tampilan
                        $('#kegiatan').trigger('change');
                    },
                    error: function(xhr, status, error) {
                        // Menangani error jika AJAX gagal
                        $('#kegiatan').empty().append('<option value="">Error memuat data kegiatan</option>');
                        console.log('AJAX Error: ' + status + ' - ' + error); // Debugging error
                    }
                });
            } else {
                $('#kegiatan').empty().append('<option value="">Pilih Kegiatan</option>');
                $('#sub_kegiatan').empty().append('<option value="">Pilih Sub Kegiatan</option>');
            }
        });

        $('#kegiatan').change(function() {
            var kegiatan_id = $(this).val();
            
            if (kegiatan_id) {
                $('#sub_kegiatan').empty().append('<option value="">Memuat...</option>');
                
                $.ajax({
                    url: "<?= site_url('master/sub-kegiatan') ?>/" + kegiatan_id, // Sesuaikan URL dengan route yang ada
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#sub_kegiatan').empty().append('<option value="">Pilih Sub Kegiatan</option>');

                        if (data.results.length > 0) {
                            $.each(data.results, function(index, item) {
                                var selected = (item.id == old_sub_kegiatan) ? 'selected' : ''; // Cek apakah ini kecamatan yang dipilih
                                $('#sub_kegiatan').append('<option value="' + item.id + '" ' + selected + '>' + item.text + '</option>');
                            });
                        } else {
                            $('#sub_kegiatan').append('<option value="">Tidak ada sub kegiatan ditemukan</option>');
                        }

                        // Refresh select2 untuk memperbarui tampilan
                        $('#sub_kegiatan').trigger('change');
                    },
                    error: function(xhr, status, error) {
                        // Menangani error jika AJAX gagal
                        $('#sub_kegiatan').empty().append('<option value="">Error memuat data sub kegiatan</option>');
                        console.log('AJAX Error: ' + status + ' - ' + error); // Debugging error
                    }
                });
            } else {
                $('#sub_kegiatan').empty().append('<option value="">Pilih Sub Kegiatan</option>');
            }
        });
    });
</script>

<?= $this->endSection(); ?>