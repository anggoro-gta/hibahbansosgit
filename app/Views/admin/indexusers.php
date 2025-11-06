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
                                        <th>id</th>
                                        <th>username</th>
                                        <th>nama</th>
                                        <th>aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $hitungusers = count($data_users) ?>
                                    <?php for ($i = 0; $i < $hitungusers; $i++) { ?>
                                        <tr>
                                            <td><?= $i + 1; ?></td>
                                            <td><?= $data_users[$i]['id']; ?></td>
                                            <td><?= $data_users[$i]['username']; ?></td>
                                            <td><?= $data_users[$i]['fullname']; ?></td>
                                            <td>
                                                <form action="/gantipasswordbyadmin" method="get" enctype="multipart/form-data">
                                                    <?= csrf_field(); ?>
                                                    <input type="hidden" class="form-control" name="iddata" id="iddata" value="<?= $data_users[$i]['id']; ?>">
                                                    <input type="hidden" class="form-control" name="fullname" id="fullname" value="<?= $data_users[$i]['fullname']; ?>">
                                                    <button type="submit" class="btn btn-secondary"><i class="fas fa-undo"></i> reset password</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>no</th>
                                        <th>id</th>
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
    $(function() {
        $("#example1").DataTable({
            // "lengthChange": true,
            "responsive": true,
            "autoWidth": false,
            "ordering": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "lengthMenu": [
                [30, 40, 50, -1],
                [30, 40, 50, "All"]
            ]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });

    const lisetting = document.querySelector('.lisetting');
    const ahrefsetting = document.querySelector('.ahrefsetting');
    const ahrefsettingusers = document.querySelector('.ahrefsettingusers');

    lisetting.classList.add("menu-open");
    ahrefsetting.classList.add("active");
    ahrefsettingusers.classList.add("active");
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