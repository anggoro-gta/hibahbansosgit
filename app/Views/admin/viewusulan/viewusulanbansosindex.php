<?= $this->extend('layouts/template'); ?>

<?= $this->section('content'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1>View Usulan Bansos</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Usulan</a></li>
                        <li class="breadcrumb-item active">Bansos</li>
                    </ol>
                </div>
                <div class="col-sm-6 pt-2">
                    <?php if (in_groups(['useropd'])) : ?>
                        <a href="<?= base_url('master/bansos/create') ?>" class="btn btn-sm btn-success"><i class="fa fa-plus mr-2"></i>Tambah</a>
                        <a href="<?= base_url('import-excel-bansos') ?>" class="btn btn-sm btn-outline-primary"><i class="fa fa-upload mr-2"></i>Import</a>
                    <?php endif; ?>
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
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-4">
                                        <select class="form-control select2" name="kode_opd" id="kode_opd">
                                            <option value="all">Semua OPD</option>
                                            <?php foreach ($ref_opd as $item) : ?>
                                                <option value="<?= $item['kode_opd'] ?>"><?= $item['nama_opd'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Alamat</th>
                                            <th>APBD <?= $_SESSION['years'] ?></th>
                                            <th>Perubahan PERBUP 1 <?= $_SESSION['years'] ?></th>
                                            <th>Perubahan PERBUP 2 <?= $_SESSION['years'] ?></th>
                                            <th>P-APBD <?= $_SESSION['years'] ?></th>
                                            <th>OPD</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Alamat</th>
                                            <th>APBD <?= $_SESSION['years'] ?></th>
                                            <th>Perubahan PERBUP 1 <?= $_SESSION['years'] ?></th>
                                            <th>Perubahan PERBUP 2 <?= $_SESSION['years'] ?></th>
                                            <th>P-APBD <?= $_SESSION['years'] ?></th>
                                            <th>OPD</th>
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
    const liviewadmin = document.querySelector('.liviewadmin');
    const ahrefviewadmin = document.querySelector('.ahrefviewadmin');
    const ahrefviewusulanbansos = document.querySelector('.ahref-view-usulan-bansos');

    liviewadmin.classList.add("menu-open");
    ahrefviewadmin.classList.add('active');
    ahrefviewusulanbansos.classList.add('active');
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

<script>
    $(function() {
        $('.select2').select2();

        let kodeOpd = $('#kode_opd').val();

        const table = $('#example1').DataTable({
            'oLanguage': {
                "sProcessing": "Sedang memproses...",
                "sLengthMenu": "Tampilkan _MENU_ entri",
                "sZeroRecords": "Data tidak ditemukan",
                "sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                "sInfoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                "sInfoPostFix": "",
                "sSearch": "Cari:",
                "sUrl": "",
                "oPaginate": {
                    "sFirst": "Pertama",
                    "sPrevious": "Sebelumnya",
                    "sNext": "Selanjutnya",
                    "sLast": "Terakhir"
                }
            },
            processing: true,
            serverSide: true,
            deferRender: true,
            ajax: {
                url: "<?= site_url('view/bansos/datatable'); ?>",
                type: "POST",
                data: d => {
                    d.kode_opd = kodeOpd;
                    d["<?= csrf_token() ?>"] = "<?= csrf_hash() ?>";
                }
            },
            columns: [{
                    data: null,
                    render: (d, t, r, meta) => meta.row + 1 + +$('#example1').DataTable().page.info().start
                },
                {
                    data: 'nama',
                    defaultContent: '-'
                },
                {
                    data: 'alamat',
                    defaultContent: '-'
                },
                {
                    data: 'apbd',
                    className: 'text-right',
                    render: (data, type) => {
                        if (type === 'display') return Number(data || 0).toLocaleString('id-ID');
                        return data;
                    }
                },
                {
                    data: 'perubahan_perbup_1',
                    className: 'text-right',
                    render: (data, type) => {
                        if (type === 'display') return Number(data || 0).toLocaleString('id-ID');
                        return data;
                    }
                },
                {
                    data: 'perubahan_perbup_2',
                    className: 'text-right',
                    render: (data, type) => {
                        if (type === 'display') return Number(data || 0).toLocaleString('id-ID');
                        return data;
                    }
                },
                {
                    data: 'papbd',
                    className: 'text-right',
                    render: (data, type) => {
                        if (type === 'display') return Number(data || 0).toLocaleString('id-ID');
                        return data;
                    }
                },
                {
                    data: 'opd',
                    defaultContent: '-'
                },
            ]
        });

        $('#kode_opd').on('change', function() {
            kodeOpd = $(this).val();
            table.ajax.reload(null, true);
        });

        // contoh: reload saat ganti tahun
        // $('#years').on('change', () => table.ajax.reload(null, false));

        const valOrDash = v => (v === null || v === undefined || v === '') ? '-' : v;
    });
</script>

<?php if (session()->getFlashdata('success')) : ?>
    <script>
        $(function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            Toast.fire({
                icon: 'success',
                title: '<?= session()->getFlashdata('success') ?>'
            });
        });
    </script>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <script>
        $(function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            Toast.fire({
                icon: 'error',
                title: '<?= session()->getFlashdata('error') ?>'
            });
        });
    </script>
<?php endif; ?>

<?= $this->endSection(); ?>