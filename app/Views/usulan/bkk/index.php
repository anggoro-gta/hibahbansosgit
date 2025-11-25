<?= $this->extend('layouts/template'); ?>

<?= $this->section('content'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Usulan BKK</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Usulan</a></li>
                        <li class="breadcrumb-item active">BKK</li>
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
                        <?php if (in_groups(['useropd'])) : ?>
                        <div class="card-header">
                            <a href="<?= base_url('usulan/bkk/create') ?>" class="btn btn-sm btn-success"><i class="fa fa-plus mr-2"></i>Tambah</a>
                        </div>
                        <?php endif; ?>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Desa</th>
                                        <th>APBD <?= $_SESSION['years'] ?></th>
                                        <th>Perubahan PERBUP 1 <?= $_SESSION['years'] ?></th>
                                        <th>Perubahan PERBUP 2 <?= $_SESSION['years'] ?></th>
                                        <th>P-APBD <?= $_SESSION['years'] ?></th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Desa</th>
                                        <th>APBD <?= $_SESSION['years'] ?></th>
                                        <th>Perubahan PERBUP 1 <?= $_SESSION['years'] ?></th>
                                        <th>Perubahan PERBUP 2 <?= $_SESSION['years'] ?></th>
                                        <th>P-APBD <?= $_SESSION['years'] ?></th>
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
    const liparent = document.querySelector('.liinputadmin');
    const ahrefparent = document.querySelector('.ahrefinputadmin');
    const ahrefchild = document.querySelector('.ahref-usulan-bkk');

    liparent.classList.add("menu-open");
    ahrefparent.classList.add('active');
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
                url: "<?= site_url('usulan/bkk/datatable'); ?>",
                type: "POST",
                data: d => {
                    d["<?= csrf_token() ?>"] = "<?= csrf_hash() ?>";
                }
            },
            columns: [
                { data: null, render: (d,t,r,meta) => meta.row + 1 + +$('#example1').DataTable().page.info().start },
                { data: 'nama_desa', defaultContent: '-' },
                { data: 'apbd', className:'text-right', render: (data,type) => {
                    if (type === 'display') return Number(data || 0).toLocaleString('id-ID');
                        return data;
                    } 
                },
                { data: 'perubahan_perbup_1', className:'text-right', render: (data,type) => {
                    if (type === 'display') return Number(data || 0).toLocaleString('id-ID');
                        return data;
                    } 
                },
                { data: 'perubahan_perbup_2', className:'text-right', render: (data,type) => {
                    if (type === 'display') return Number(data || 0).toLocaleString('id-ID');
                        return data;
                    } 
                },
                { data: 'papbd', className:'text-right', render: (data,type) => {
                    if (type === 'display') return Number(data || 0).toLocaleString('id-ID');
                        return data;
                    } 
                },
                { data: 'action', orderable:false, searchable:false, className:'text-center' }
            ]
        });

        // contoh: reload saat ganti tahun
        // $('#years').on('change', () => table.ajax.reload(null, false));

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