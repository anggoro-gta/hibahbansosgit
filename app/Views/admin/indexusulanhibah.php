<?= $this->extend('layouts/template'); ?>

<?= $this->section('content'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Hibah</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Master</a></li>
                        <li class="breadcrumb-item active">Hibah</li>
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
                                <a href="/inputttdmaster"><button type="button" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Tambah usulan</button></a>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>no</th>
                                            <th>Kab</th>
                                            <th>Kec</th>
                                            <th>Desa</th>
                                            <th>anggaran</th>
                                            <th width="150px">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $countusulanhibah = count($usulanhibah); ?>
                                        <?php for ($i = 0; $i < $countusulanhibah; $i++) : ?>
                                            <tr>
                                                <td><?= $i + 1; ?></td>
                                                <td><?= $usulanhibah[$i]['nama_kabupaten']; ?></td>
                                                <td><?= $usulanhibah[$i]['nama_kecamatan']; ?></td>
                                                <td><?= $usulanhibah[$i]['nama_desa']; ?></td>
                                                <td><?= $usulanhibah[$i]['anggaran']; ?></td>                                                
                                                <td>
                                                    <a href="/detailusulan/<?= $usulanhibah[$i]['id']; ?>"><button type="button" class="btn btn-block btn-info"><i class="fa fa-check"></i> edit</button></a>
                                                    <button onclick="showdelete('<?= $usulanhibah[$i]['id']; ?>')" type="button" class="btn btn-block btn-danger"><i class="fas fa-minus-circle"></i> Delete</button>
                                                </td>
                                            </tr>
                                        <?php endfor; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>no</th>
                                            <th>Kab</th>
                                            <th>Kec</th>
                                            <th>Desa</th>
                                            <th>anggaran</th>
                                            <th width="150px">Aksi</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- <div class="card-header">
                            <a href="/entryusulan"><button type="button" class="btn btn-secondary"><i class="fas fa-arrow-circle-left"></i> Kembali</button></a>
                        </div> -->
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
        </div>

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

    const liinputadmin = document.querySelector('.liinputadmin');
    const ahrefinputadmin = document.querySelector('.ahrefinputadmin');
    const ahrefusulanhibahadmin = document.querySelector('.ahrefusulanhibahadmin');

    liinputadmin.classList.add("menu-open");
    ahrefinputadmin.classList.add("active");
    ahrefusulanhibahadmin.classList.add("active");
</script>

<script>
    function showdelete(id) {
        const formData = {
            send_id: id,
        };

        const url = window.location.origin;
        if (confirm("Apakah yakin menghapus data ini?")) {
            $.ajax({
                type: "POST",
                url: url + "/deletemasterttd",
                data: formData,
                dataType: "json",
                headers: {
                    "Access-Control-Allow-Origin": "*",
                    "Access-Control-Allow-Methods": "POST"
                },
            }).done(function(data) {
                if (data.status_update == "kosong") {
                    $(function() {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 10000
                        });
                        Toast.fire({
                            icon: 'error',
                            title: 'Gagal hapus data'
                        });
                    });
                } else if (data.status_update == "berhasil") {
                    $(function() {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 10000
                        });
                        Toast.fire({
                            icon: 'success',
                            title: 'HAPUS DATA success'
                        });
                    });
                }
            });
        }
        //$('#example1').load(location.href + " #example1");
        setTimeout(function() {
            location.reload();
        }, 1500);
    }
</script>

<?= $this->endSection(); ?>