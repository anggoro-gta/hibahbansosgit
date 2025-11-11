<?= $this->extend('layouts/template'); ?>

<?= $this->section('content'); ?>
<style>
    #preview-grid { gap:.5rem; }
    #preview-grid .thumb { width:100px; }
    #preview-grid .thumb img { cursor:pointer; }
    .uppercase { text-transform: uppercase; }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= $button ?> Master Hibah</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Master</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('master/hibah') ?>">Hibah</a></li>
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
                <div class="col-12">
                    <div class="card">
                        <form method="POST" action="<?= $url ?>" enctype="multipart/form-data"> 
                            <input type="hidden" class="form-control" name="id" id="id" required value="<?= $id ?>">
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Tanggal Berdiri <sup class="text-danger">*</sup></label>
                                        <input type="date" class="form-control" name="tgl_berdiri" id="tgl_berdiri" value="<?= $tgl_berdiri ?>" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>No. Akta Hukum <sup class="text-danger">*</sup></label>
                                        <input type="text"class="form-control uppercase" name="no_akta_hukum" id="no_akta_hukum" autocomplete="off" oninput="toUpperNoSpace(this)" onkeydown="return blockSpace(event)" value="<?= esc($no_akta_hukum) ?>" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Nama Lembaga <sup class="text-danger">*</sup></label>
                                        <input type="text" class="form-control" name="nama_lembaga" id="nama_lembaga" required value="<?= $nama_lembaga ?>">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Kabupaten <sup class="text-danger">*</sup></label>
                                        <select class="form-control select2" name="kabupaten" id="kabupaten" required>
                                            <option value="">Pilih Kabupaten</option>
                                            <?php foreach ($ref_kabupaten as $item) : ?>
                                            <option value="<?= $item['id'] ?>" <?= $kabupaten==$item['id'] ? 'selected' : '' ?>><?= $item['nama_kabupaten'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Kecamatan <sup class="text-danger">*</sup></label>
                                        <select class="form-control select2" name="kecamatan" id="kecamatan" required>
                                            <option value="">Pilih Kecamatan</option>
                                            <?php foreach ($ref_kecamatan as $item) : ?>
                                            <option value="<?= $item['id'] ?>" <?= $kecamatan==$item['id'] ? 'selected' : '' ?>><?= $item['nama_kecamatan'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Desa <sup class="text-danger">*</sup></label>
                                        <select class="form-control select2" name="desa" id="desa" required>
                                            <option value="">Pilih Desa</option>
                                            <?php foreach ($ref_desa as $item) : ?>
                                            <option value="<?= $item['id'] ?>" <?= $desa==$item['id'] ? 'selected' : '' ?>><?= $item['nama_desa'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Alamat <sup class="text-danger">*</sup></label>
                                        <input type="text" class="form-control" name="alamat" id="alamat" required value="<?= $alamat ?>">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Program <sup class="text-danger">*</sup></label>
                                        <select class="form-control select2" name="program" id="program" required>
                                            <option value="">Pilih Program</option>
                                            <?php foreach ($ref_program as $item) : ?>
                                            <option value="<?= $item['id'] ?>" <?= $program==$item['id'] ? 'selected' : '' ?>><?= $item['nama_program'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Kegiatan <sup class="text-danger">*</sup></label>
                                        <select class="form-control select2" name="kegiatan" id="kegiatan" required>
                                            <option value="">Pilih Kegiatan</option>
                                            <?php foreach ($ref_kegiatan as $item) : ?>
                                            <option value="<?= $item['id'] ?>" <?= $kegiatan==$item['id'] ? 'selected' : '' ?>><?= $item['nama_kegiatan'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Sub Kegiatan <sup class="text-danger">*</sup></label>
                                        <select class="form-control select2" name="sub_kegiatan" id="sub_kegiatan" required>
                                            <option value="">Pilih Sub Kegiatan</option>
                                            <?php foreach ($ref_sub_kegiatan as $item) : ?>
                                            <option value="<?= $item['id'] ?>" <?= $sub_kegiatan==$item['id'] ? 'selected' : '' ?>><?= $item['nama_sub_kegiatan'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>File Akta Berbadan Hukum</label>
                                        <input type="file" class="form-control" name="file_1" id="file_1" accept=".jpg, .jpeg, .png, .pdf">
                                        <?php if (isset($file_1) && !empty($file_1)): ?>
                                            <div class="div-file col-12 col-sm-8 col-lg-6 border border-2 rounded-lg p-1 mb-2 bg-gray-light mt-2">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="d-flex align-items-center flex-grow-1" style="min-width:0">
                                                        <i class="fas fa-paperclip text-muted mr-2"></i>
                                                        
                                                        <a href="<?= base_url($file_1->url_name) ?>" target="_blank" class="text-muted text-truncate"
                                                            style="display:inline-block; max-width:100%;">
                                                            <?= esc($file_1->originale_name ?? $file_1->file_name) ?>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>File Bukti Telah Lapor ke Bupati</label>
                                        <input type="file" class="form-control" name="file_2" id="file_2" accept=".jpg, .jpeg, .png, .pdf">
                                        <?php if (isset($file_2) && !empty($file_2)): ?>
                                            <div class="div-file col-12 col-sm-8 col-lg-6 border border-2 rounded-lg p-1 mb-2 bg-gray-light mt-2">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="d-flex align-items-center flex-grow-1" style="min-width:0">
                                                        <i class="fas fa-paperclip text-muted mr-2"></i>
                                                        
                                                        <a href="<?= base_url($file_2->url_name) ?>" target="_blank" class="text-muted text-truncate"
                                                            style="display:inline-block; max-width:100%;">
                                                            <?= esc($file_2->originale_name ?? $file_2->file_name) ?>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>File NPWP</label>
                                        <input type="file" class="form-control" name="file_3" id="file_3" accept=".jpg, .jpeg, .png, .pdf">
                                        <?php if (isset($file_3) && !empty($file_2)): ?>
                                            <div class="div-file col-12 col-sm-8 col-lg-6 border border-2 rounded-lg p-1 mb-2 bg-gray-light mt-2">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="d-flex align-items-center flex-grow-1" style="min-width:0">
                                                        <i class="fas fa-paperclip text-muted mr-2"></i>
                                                        
                                                        <a href="<?= base_url($file_3->url_name) ?>" target="_blank" class="text-muted text-truncate"
                                                            style="display:inline-block; max-width:100%;">
                                                            <?= esc($file_3->originale_name ?? $file_3->file_name) ?>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>File Surat Keterangan Domisili</label>
                                        <input type="file" class="form-control" name="file_4" id="file_4" accept=".jpg, .jpeg, .png, .pdf">
                                        <?php if (isset($file_4) && !empty($file_4)): ?>
                                            <div class="div-file col-12 col-sm-8 col-lg-6 border border-2 rounded-lg p-1 mb-2 bg-gray-light mt-2">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="d-flex align-items-center flex-grow-1" style="min-width:0">
                                                        <i class="fas fa-paperclip text-muted mr-2"></i>
                                                        
                                                        <a href="<?= base_url($file_4->url_name) ?>" target="_blank" class="text-muted text-truncate"
                                                            style="display:inline-block; max-width:100%;">
                                                            <?= esc($file_4->originale_name ?? $file_4->file_name) ?>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer text-right">
                                <a href="<?= base_url('master/hibah') ?>" class="btn btn-secondary">Batal</a>
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
    const ahrefhibah = document.querySelector('.ahref-master-hibah');

    limaster.classList.add("menu-open");
    ahrefmaster.classList.add('active');
    ahrefhibah.classList.add('active');
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

        $('#kecamatan').change(function() {
            var kec_id = $(this).val();
            
            if (kec_id) {
                $('#desa').empty().append('<option value="">Memuat...</option>');
                
                $.ajax({
                    url: "<?= site_url('master/desa') ?>/" + kec_id, // Sesuaikan URL dengan route yang ada
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#desa').empty().append('<option value="">Pilih Desa</option>');

                        if (data.results.length > 0) {
                            $.each(data.results, function(index, item) {
                                var selected = (item.id == old_desa) ? 'selected' : '';
                                $('#desa').append('<option value="' + item.id + '" ' + selected + '>' + item.text + '</option>');
                            });
                        } else {
                            $('#desa').append('<option value="">Tidak ada desa ditemukan</option>');
                        }

                        // Refresh select2 untuk memperbarui tampilan
                        $('#desa').trigger('change');
                    },
                    error: function(xhr, status, error) {
                        // Menangani error jika AJAX gagal
                        $('#desa').empty().append('<option value="">Error memuat data desa</option>');
                        console.log('AJAX Error: ' + status + ' - ' + error); // Debugging error
                    }
                });
            } else {
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

        $('form').submit(function(event) {
            var no_akta_hukum = $('#no_akta_hukum').val(); // Ambil nilai NIK dari input
            var id = $('#id').val();

            // Cek NIK melalui AJAX
            $.ajax({
                url: "<?= site_url('master/cek_no_akta') ?>", // Ganti dengan URL route untuk pengecekan NIK
                type: 'POST',
                data: { no_akta: no_akta_hukum, id: id},
                dataType: 'json',
                success: function(response) {
                    if (response.exists) {
                        // Jika NIK sudah ada, tampilkan peringatan dan hentikan submit
                        Swal.fire({
                            title: 'Peringatan',
                            text: 'No. Akta Hukum sudah terdaftar. Silakan masukkan No. Akta Hukum yang berbeda.',
                            icon: 'info', // Menampilkan icon info
                            confirmButtonText: 'OK' // Tombol konfirmasi
                        });
                        event.preventDefault(); // Hentikan form submit
                    } else {
                        // Jika NIK belum ada, form tetap disubmit
                        $('form')[0].submit();
                    }
                },
                error: function(xhr, status, error) {
                    // Tangani error jika AJAX gagal
                    // console.log(error)
                    
                    Swal.fire({
                        title: 'Peringatan',
                        text: 'Gagal memeriksa No. Akta Hukum. Silakan coba lagi.',
                        icon: 'error', // Menampilkan icon info
                        confirmButtonText: 'OK' // Tombol konfirmasi
                    });
                    event.preventDefault(); // Hentikan form submit jika error
                }
            });

            // Hentikan submit form sementara menunggu respons AJAX
            event.preventDefault();
        });
    });
</script>
<script>
    function toUpperNoSpace(el) {
        // ubah ke uppercase dan hapus semua spasi (termasuk spasi di tengah)
        el.value = el.value.toUpperCase().replace(/\s+/g, '');
    }

    function blockSpace(e) {
        // cegah spasi dari keyboard
        if (e.key === ' ') return false;
        return true;
    }
</script>

<?= $this->endSection(); ?>