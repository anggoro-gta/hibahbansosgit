<?= $this->extend('layouts/template'); ?>

<?= $this->section('content'); ?>
<style>
    .uppercase {
        text-transform: uppercase;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= $button ?> Usulan Bansos</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Usulan</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('usulan/bansos') ?>">Bansos</a></li>
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
                <div class="<?= $button == 'Tambah' ? 'col-12' : 'col-5' ?>">
                    <div class="card">
                        <form id="<?= $button == 'Tambah' ? 'form-tambah' : 'form-edit' ?>" method="POST" action="<?= $url ?>">
                            <?php if ($button == 'Tambah') : ?>
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <select class="form-control select2" name="kode_opd" id="kode_opd">
                                                <option value="all">Semua OPD</option>
                                                <?php foreach ($ref_opd as $item) : ?>
                                                <option value="<?= $item['kode_opd'] ?>"><?= $item['nama_opd'] ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            <?php endif ?>
                            <div class="card-body">
                                <?php if ($button == 'Tambah') : ?>
                                    <input type="hidden" name="selected_ids" id="selected_ids">
                                    <table id="example1" class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th class="text-center" width="5%">
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input" type="checkbox" id="customCheckboxAll" value="all">
                                                        <label for="customCheckboxAll" class="custom-control-label">&nbsp;</label>
                                                    </div>
                                                </th>
                                                <th class="text-center">NIK</th>
                                                <th class="text-center">Nama / Alamat</th>
                                                <th class="text-center">OPD</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                <?php else : ?>
                                    <input type="hidden" class="form-control" name="id" id="id" required value="<?= $id ?>">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label>Tahun Usulan <sup class="text-danger">*</sup></label>
                                            <input type="text" class="form-control uppercase" name="tahun" id="tahun" value="<?= $tahun ?>" readonly>
                                        </div>
                                        <div class="form-group col-md-8">
                                            <label>NIK <sup class="text-danger">*</sup></label>
                                            <input type="text" class="form-control uppercase" name="nik" id="nik" value="<?= $nik ?>" readonly>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>Nama <sup class="text-danger">*</sup></label>
                                            <input type="text" class="form-control" name="nama" id="nama" readonly value="<?= $nama ?>">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>APBD</label>
                                            <input type="text" class="form-control nominal" name="apbd" id="apbd" value="<?= $apbd > 0 ? number_format($apbd, 0, ',', '.') : '' ?>">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>Perubahan PERBUP 1</label>
                                            <input type="text" class="form-control nominal" name="perubahan_perbup_1" id="perubahan_perbup_1" value="<?= $perubahan_perbup_1 > 0 ? number_format($perubahan_perbup_1, 0, ',', '.') : '' ?>">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>Perubahan PERBUP 2</label>
                                            <input type="text" class="form-control nominal" name="perubahan_perbup_2" id="perubahan_perbup_2" value="<?= $perubahan_perbup_2 > 0 ? number_format($perubahan_perbup_2, 0, ',', '.') : '' ?>">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>P-APBD</label>
                                            <input type="text" class="form-control nominal" name="papbd" id="papbd" value="<?= $papbd > 0 ? number_format($papbd, 0, ',', '.') : '' ?>">
                                        </div>
                                    </div>
                                <?php endif ?>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer text-right">
                                <a href="<?= base_url('usulan/bansos') ?>" class="btn btn-secondary">Batal</a>
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
    const liparent = document.querySelector('.liinputadmin');
    const ahrefparent = document.querySelector('.ahrefinputadmin');
    const ahrefbansos = document.querySelector('.ahref-usulan-bansos');

    liparent.classList.add("menu-open");
    ahrefparent.classList.add('active');
    ahrefbansos.classList.add('active');
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
        
        const MAX_CHECK = 100;
        // tempat nyimpen id yang sudah dipilih, lintas halaman
        const selectedIds = new Set();

        const table = $('#example1').DataTable({
            'oLanguage':
            {
                "sProcessing":   "Sedang memproses...",
                "sLengthMenu":   "Tampilkan _MENU_ entri",
                "sZeroRecords":  "Data tidak ditemukan",
                "sInfo":         "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                "sInfoEmpty":    "Menampilkan 0 sampai 0 dari 0 entri",
                "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                "sInfoPostFix":  "",
                "sSearch":       "Cari:",
                "sUrl":          "",
                "oPaginate": {
                "sFirst":    "Pertama",
                "sPrevious": "Sebelumnya",
                "sNext":     "Selanjutnya",
                "sLast":     "Terakhir"
                }
            },
            pageLength: 10,
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?= site_url('usulan/bansos/layak-usulan-json'); ?>",
                type: "POST",
                data: function (d) {
                    d.kode_opd = $('#kode_opd').val(); // kirim kode_opd ke server
                }
            },
            columns: [
                { // checkbox
                    data: 'id',
                    orderable: false,
                    className: 'text-center',
                    render: function (data, type, row) {
                        return `
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input child-check"
                                        type="checkbox"
                                        id="customCheckbox${data}"
                                        value="${data}">
                                <label for="customCheckbox${data}"
                                        class="custom-control-label">&nbsp;</label>
                            </div>`;
                    }
                },
                { data: 'nik' },
                { data: 'nama' },
                { data: 'nama_opd' }
            ],
            columnDefs: [
                { orderable: false, targets: 0 }
            ],
            order: [[1, 'asc']],
            // ini penting: setiap tabel digambar ulang, kita sync checkbox
            drawCallback: function () {
                syncCheckboxes();
            }
        });

        $('#kode_opd').on('change', function () {
            selectedIds.clear();      // biasanya lebih aman di-clear saat ganti OPD
            $('#customCheckboxAll').prop('checked', false);
            table.ajax.reload();
        });

        function syncCheckboxes() {
            $('#example1 tbody tr').each(function () {
                const $chk = $(this).find('.child-check');
                const id = $chk.val();

                $chk.prop('checked', selectedIds.has(id));

                if (selectedIds.size >= MAX_CHECK && !selectedIds.has(id)) {
                    $chk.prop('disabled', true);
                } else {
                    $chk.prop('disabled', false);
                }
            });
        }

        // pertama kali jalan
        syncCheckboxes();

        // klik satuan
        $('#example1').on('change', '.child-check', function() {
            const id = $(this).val();

            if (this.checked) {
                if (selectedIds.size >= MAX_CHECK) {
                    // batalin
                    this.checked = false;
                    Swal.fire({
                        title: 'Peringatan',
                        text: 'Maksimal hanya boleh memilih ' + MAX_CHECK + ' data.',
                        icon: 'info', // Menampilkan icon info
                        confirmButtonText: 'OK' // Tombol konfirmasi
                    });
                    return;
                }
                selectedIds.add(id);
            } else {
                selectedIds.delete(id);
            }

            syncCheckboxes();
        });

        // klik select-all halaman aktif
        $('#customCheckboxAll').on('change', function() {
            const mauCheck = this.checked;

            if (mauCheck) {
                // centang yang ada di halaman ini, tapi hormati batas
                $('#example1 .child-check').each(function() {
                    const id = $(this).val();
                    if (!selectedIds.has(id) && selectedIds.size < MAX_CHECK) {
                        selectedIds.add(id);
                    }
                });
            } else {
                // uncheck semua yang kelihatan
                $('#example1 .child-check').each(function() {
                    const id = $(this).val();
                    selectedIds.delete(id);
                });
            }

            syncCheckboxes();
        });

        $('#form-tambah').on('submit', function(e) {
            if (selectedIds.size === 0) {
                Swal.fire({
                    title: 'Peringatan',
                    text: 'Pilih minimal 1 data dulu.',
                    icon: 'info', // Menampilkan icon info
                    confirmButtonText: 'OK' // Tombol konfirmasi
                });
                e.preventDefault();
                return;
            }

            // masukkan ke hidden input sebagai JSON atau csv
            $('#selected_ids').val(JSON.stringify(Array.from(selectedIds)));
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('.nominal').on('keyup', function() {
            var angka = $(this).val();
            if (angka === '' || angka === '0' || angka === '0.00') {
                // $(this).val('0');
                return;
            }
            $(this).val(formatRibuan(angka));
        });

        function replaceAngka(angka) {
            let number_string = angka.replace(/[^,\d]/g, '').toString();
            // kalau isinya cuma nol semua, kembalikan '0'
            if (/^0+$/.test(number_string)) {
                return '0';
            }
            return number_string;
        }

        function formatRibuan(angka) {
            // var number_string = angka.replace(/[^,\d]/g, '').toString(),
            var number_string = replaceAngka(angka)
            split = number_string.split(','),
                sisa = split[0].length % 3,
                angka_hasil = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                angka_hasil += separator + ribuan.join('.');
            }

            angka_hasil = split[1] != undefined ? angka_hasil + ',' + split[1] : angka_hasil;
            return angka_hasil;
        }
    })
</script>


<?= $this->endSection(); ?>