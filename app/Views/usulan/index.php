<?= $this->extend('layouts/template'); ?>

<?= $this->section('content'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Data Usulan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Data Usulan</li>
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
                                        <th>SIPD</th>
                                        <th>Anggaran</th>
                                        <?php
                                            if($is_opd){
                                        ?>
                                        <th>Action</th>
                                        <?php
                                            }
                                        ?>
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
                                        <th>SIPD</th>
                                        <th>Anggaran</th>
                                        <th>Action</th>
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

<!-- Modal Detail -->
<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">TANGGING NOMENKLATUR <span class="text-sm text-info" id="span-id-usulan"></span></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span>&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Nama Program</dt><dd class="col-sm-9" id="d-program">-</dd>
                <dt class="col-sm-3">Nama Kegiatan</dt><dd class="col-sm-9" id="d-kegiatan">-</dd>
                <dt class="col-sm-3">Nama Sub Kegiatan</dt><dd class="col-sm-9" id="d-sub-kegiatan">-</dd>
            </dl>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
        </div>
    </div>
</div>



<?= $this->endSection(); ?>

<?= $this->section('javascriptkhusus'); ?>
<script>
    const ahrefusulan = document.querySelector('.ahref-usulan');

    ahrefusulan.classList.add('active');
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
        { data: 'sipd',      defaultContent: '-', render: function (data) {
            if (data === 'Verifikasi Perangkat Daerah' || data === 'Verifikasi Kecamatan' || data === 'Validasi Mitra Bappeda') {
                return `<span class="badge badge-warning text-sm">${data}</span>`;
            } else if (data === 'Pengajuan Usulan' || data === 'Dibatalkan') {
                return `<span class="badge badge-danger text-sm">${data}</span>`;
            }
            return `<span class="badge badge-success text-sm">${data}</span>`;
        }, className: 'text-center' },
        { data: 'anggaran', className:'text-right', render: (data,type) => {
            if (type === 'display') return Number(data || 0).toLocaleString('id-ID');
            return data;
        } }
    ];

    // tambahkan kolom Action hanya untuk OPD
    if (IS_OPD) {
        columns.push({
            data: null, orderable:false, searchable:false, className:'text-center',
            render: r => `
                <button type="button" class="btn btn-sm btn-info mb-1 btn-detail" 
                        data-id="${r.id_usulan}" title="Taging Nomenklatur">
                    <i class="fa fa-eye"></i>
                </button>
                <a href="${BASE}usulan/show/${r.id_usulan}" 
                    class="btn btn-sm btn-primary mb-1" title="Update Status">
                    <i class="fa fa-edit"></i>
                </a>`
        });
    }else{
        columns.push({
            data: null, orderable:false, searchable:false, className:'text-center',
            render: r => `
                <button type="button" class="btn btn-sm btn-info mb-1 btn-detail" 
                        data-id="${r.id_usulan}" title="Taging Nomenklatur">
                    <i class="fa fa-eye"></i>
                </button>
                <a href="${BASE}usulan/show/${r.id_usulan}" 
                    class="btn btn-sm btn-primary mb-1" title="Detail">
                    <i class="fa fa-binoculars"></i>
                </a>`
        });
    }

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
            url: "<?= site_url('usulan/datatable'); ?>",
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

    const valOrDash = v => (v === null || v === undefined || v === '') ? '-' : v;

    $(document).on('click', '.btn-detail', function () {
        const id = $(this).data('id');
        const tokenName = "<?= csrf_token() ?>";
        const tokenVal  = $('meta[name="<?= csrf_token() ?>"]').attr('content');

        // placeholder loading
        $('#d-id-usulan,#d-kecamatan,#d-masalah,#d-alamat,#d-opd,#d-sipd,#d-anggaran').text('Memuat...');
        $('#modalDetail').modal('show');

        $.ajax({
            url: "<?= site_url('usulan/detail-json'); ?>",
            type: "POST",
            data: { [tokenName]: tokenVal, id_usulan: id },
            success: (res) => {
                if (res.csrf) $('meta[name="<?= csrf_token() ?>"]').attr('content', res.csrf);
                if (!res || !res.data) {
                    $('#modalDetail .modal-body').html('<div class="text-danger">Data detail tidak ditemukan.</div>');
                    return;
                }
                const d = res.data;
                $('#d-program').text(`: `+valOrDash(d.nama_program));
                $('#d-kegiatan').text(`: `+valOrDash(d.nama_kegiatan));
                $('#d-sub-kegiatan').text(`: `+valOrDash(d.nama_sub_kegiatan));
                $('#span-id-usulan').text(`[ ${d.id_usulan} ]`);
                // Tambah field lain/riwayat kalau perlu…
            },
            error: () => {
                $('#modalDetail .modal-body').html('<div class="text-danger">Gagal memuat data.</div>');
            }
        });
    });


});
</script>

<?= $this->endSection(); ?>