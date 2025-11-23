<?= $this->extend('layouts/template'); ?>

<?= $this->section('content'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1>Master BKK</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Master</a></li>
                        <li class="breadcrumb-item active">BKK</li>
                    </ol>
                </div>
                <div class="col-sm-6 pt-2">
                    <?php if (in_groups(['useropd'])) : ?>
                        <a href="<?= base_url('master/bkk/create') ?>" class="btn btn-sm btn-success"><i class="fa fa-plus mr-2"></i>Tambah</a>
                        <!-- <a href="<?= base_url('import-excel-bkk') ?>" class="btn btn-sm btn-outline-primary"><i class="fa fa-upload mr-2"></i>Import</a> -->
                    <?php endif; ?>
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
                                        <th>Kabupaten</th>
                                        <th>Kecamatan</th>
                                        <th>Desa</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Kabupaten</th>
                                        <th>Kecamatan</th>
                                        <th>Desa</th>
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
            <h5 class="modal-title">TANGGING NOMENKLATUR <span id="span-id-info"></span></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span>&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Nama Program</dt><dd class="col-sm-9" id="d-program">-</dd>
                <dt class="col-sm-3">Nama Kegiatan</dt><dd class="col-sm-9" id="d-kegiatan">-</dd>
                <dt class="col-sm-3">Nama Sub Kegiatan</dt><dd class="col-sm-9" id="d-sub-kegiatan">-</dd>
                <dt class="col-sm-3">Diinput Oleh</dt><dd class="col-sm-9" id="d-input">-</dd>
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
        $('.select2').select2();

        let kodeOpd = $('#kode_opd').val();

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
            processing: true,
            serverSide: true,
            deferRender: true,
            ajax: {
                url: "<?= site_url('master/bkk/datatable'); ?>",
                type: "POST",
                data: d => {
                    d.kode_opd = kodeOpd;
                    d["<?= csrf_token() ?>"] = "<?= csrf_hash() ?>";
                }
            },
            columns: [
                { data: null, render: (d,t,r,meta) => meta.row + 1 + +$('#example1').DataTable().page.info().start },
                { data: 'nama_kabupaten' },
                { data: 'nama_kecamatan' },
                { data: 'nama_desa' },
                { data: 'action', orderable:false, searchable:false, className:'text-center' }
            ]
        });

        $('#kode_opd').on('change', function(){
            kodeOpd = $(this).val();
            table.ajax.reload(null, true);
        });

        const valOrDash = v => (v === null || v === undefined || v === '') ? '-' : v;

        $(document).on('click', '.btn-detail', function () {
            const id = $(this).data('id');
            const tokenName = "<?= csrf_token() ?>";
            const tokenVal  = $('meta[name="<?= csrf_token() ?>"]').attr('content');

            $('#modalDetail').modal('show');

            $.ajax({
                url: "<?= site_url('master/bkk/detail-json'); ?>",
                type: "POST",
                data: { [tokenName]: tokenVal, id: id },
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
                    $('#d-input').html(`: <b>`+valOrDash(d.nama_opd)+`</b>`);
                    $('#span-id-info').html(`<small class="text-primary">[ ${d.nama_desa} ]</small> <small class="text-success">[ ${d.nama_kecamatan} ]</small>`);
                    // Tambah field lain/riwayat kalau perlu…
                },
                error: () => {
                    $('#modalDetail .modal-body').html('<div class="text-danger">Gagal memuat data.</div>');
                }
            });
        });

    });
    function confirmDelete(url) {
        // Menampilkan konfirmasi menggunakan SweetAlert2
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data ini akan dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika pengguna mengonfirmasi, lakukan penghapusan dengan mengarahkan ke URL
                window.location.href = url;
            } else {
                // Jika dibatalkan, tidak melakukan apa-apa
                return false;
            }
        });

        // Mengembalikan false untuk menghentikan aksi default (karena penghapusan akan dilakukan setelah konfirmasi)
        return false;
    }

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