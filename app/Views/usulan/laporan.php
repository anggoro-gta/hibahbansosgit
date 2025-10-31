<?= $this->extend('layouts/template'); ?>

<?= $this->section('content'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Laporan Usulan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Laporan Usulan</li>
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
                <div class="col-12">

                    <div class="card">
                        <div class="card-header">
                            <a href="<?= base_url('usulan/export-pdf')?>" class="btn btn-danger btn-sm"><i class="fa fa-file-pdf mr-1"></i>Export PDF</a>
                            <a href="<?= base_url('usulan/export-excel')?>" class="btn btn-success btn-sm"><i class="fa fa-file-excel mr-1"></i>Export Excel</a>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Id Usulan</th>
                                        <th>Kecamatan</th>
                                        <th>Usulan</th>
                                        <th>Lokasi</th>
                                        <th>OPD</th>
                                        <th>Anggaran</th>
                                        <th>Status Pelaksanaan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Id Usulan</th>
                                        <th>Kecamatan</th>
                                        <th>Usulan</th>
                                        <th>Lokasi</th>
                                        <th>OPD</th>
                                        <th>Anggaran</th>
                                        <th>Status Pelaksanaan</th>
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
    const ahrefUsulanLaporan = document.querySelector('.ahref-usulan-laporan');

    ahrefUsulanLaporan.classList.add('active');
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
$(function () {
    const BASE = "<?= site_url() ?>";        // utk link di kolom Action
    
    const IS_OPD = <?= $is_opd ? 'true' : 'false' ?>;

    // kolom wajib (tanpa Action)
    const columns = [
        { data: null, render: (d,t,r,meta) => meta.row + 1 },
        { data: 'id_usulan', defaultContent: '-' },
        { data: 'kecamatan', defaultContent: '-' },
        { data: 'masalah',   defaultContent: '-' },
        { data: 'alamat',    defaultContent: '-' },
        { data: 'opd',       defaultContent: '-' },
        { data: 'anggaran', className:'text-right', render: (data,type) => {
                if (type === 'display') return Number(data || 0).toLocaleString('id-ID');
                return data;
            } 
        },
        { data: 'status_pelaksanaan',      defaultContent: '-', render: function (data) {
            if (data == '1') {
                return `<span class="badge badge-success text-sm">Sudah</span>`;
            } else{
                return `<span class="badge badge-danger text-sm">Belum</span>`;
            }
        }, className: 'text-center' }
    ];

    const table = $("#example1").DataTable({
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
        responsive: true,
        autoWidth: false,
        ordering: true,
        lengthMenu: [[30,40,50,100,-1],[30,40,50,100,"All"]],
        processing: true,
        serverSide: false,
        ajax: {
            url: "<?= site_url('usulan/datatable-laporan'); ?>",
            type: "POST",
            data: d => { d["<?= csrf_token() ?>"] = "<?= csrf_hash() ?>"; },
            dataSrc: json => {
                if (json.csrf) $('meta[name="<?= csrf_token() ?>"]').attr('content', json.csrf);
                return json.data || [];
            }
        },
        columns
    });

    // contoh: reload saat ganti tahun
    $('#years').on('change', () => table.ajax.reload(null, false));

});
</script>

<?= $this->endSection(); ?>