<?= $this->extend('layouts/template'); ?>

<?= $this->section('content'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Users</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Data</a></li>
                        <li class="breadcrumb-item active">Users</li>
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
                        <!-- <div class="card-header">
                            <a href="/entrytujuanpd/tambahtujuanpd"><button type="submit" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Tambah Data Tujuan Perangkat Daerah</button></a>
                        </div> -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>no</th>
                                        <th>username</th>
                                        <th>nama</th>
                                        <th>aksi</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>no</th>
                                        <th>username</th>
                                        <th>nama</th>
                                        <th>aksi</th>
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


<?= $this->endSection(); ?>

<?= $this->section('javascriptkhusus'); ?>
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
    const lisetting = document.querySelector('.lisetting');
    const ahrefsetting = document.querySelector('.ahrefsetting');
    const ahrefsettingusers = document.querySelector('.ahrefsettingusers');

    lisetting.classList.add("menu-open");
    ahrefsetting.classList.add("active");
    ahrefsettingusers.classList.add("active");
</script>

<script>
    $(function() {
        const BASE = "<?= site_url() ?>"; // utk link di kolom Action

        // kolom wajib (tanpa Action)
        const columns = [{
                data: null,
                render: (d, t, r, meta) => meta.row + 1
            },
            {
                data: 'username',
                defaultContent: '-'
            },
            {
                data: 'nama',
                defaultContent: '-'
            }
        ];


        columns.push({
            data: null,
            orderable: false,
            searchable: false,
            className: 'text-center',
            render: r => `                
                <a href="${BASE}gantipasswordbyadmin/${r.id}" 
                    class="btn btn-sm btn-secondary mb-1" title="reset password">
                    <i class="fa fa-undo"></i>
                </a>`
        });

        const table = $("#example1").DataTable({
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
            responsive: true,
            autoWidth: false,
            ordering: true,
            lengthMenu: [
                [30, 40, 50, 100, -1],
                [30, 40, 50, 100, "All"]
            ],
            processing: true,
            serverSide: false,
            ajax: {
                url: "<?= site_url('setting/users/datatable'); ?>",
                type: "POST",
                data: d => {
                    d["<?= csrf_token() ?>"] = "<?= csrf_hash() ?>";
                },
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

<?php if (session()->getFlashdata('pesan') == 'updatepass') : ?>
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
                title: 'Password berhasil dirubah'
            });
        });
    </script>
<?php endif; ?>

<?= $this->endSection(); ?>