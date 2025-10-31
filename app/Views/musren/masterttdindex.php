<?= $this->extend('layouts/template'); ?>

<?= $this->section('content'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Master TTD (<?= $nama_bidang; ?>)</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Data</a></li>
                        <li class="breadcrumb-item active">Master</li>
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
                        <div class="card-header">
                            <a href="/inputttdmaster"><button type="button" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Tambah Data</button></a>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>no</th>
                                        <th>Jenis</th>
                                        <th>Nama</th>
                                        <th>Instansi</th>
                                        <th>NIP</th>
                                        <th>ttd</th>
                                        <th width="150px">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $hitungmasterttd = count($master_ttd); ?>
                                    <?php for ($i = 0; $i < $hitungmasterttd; $i++) : ?>
                                        <tr>
                                            <td><?= $i + 1; ?></td>
                                            <td><?= $master_ttd[$i]['jenis']; ?></td>
                                            <td><?= $master_ttd[$i]['nama_penandatangan']; ?></td>
                                            <td><?= $master_ttd[$i]['instansi']; ?></td>
                                            <td><?= $master_ttd[$i]['nip']; ?></td>
                                            <td><img src="<?= base_url() . $master_ttd[$i]['link_image']; ?>" height="90" width="120" alt=""></td>
                                            <td>
                                                <button onclick="showdelete('<?= $master_ttd[$i]['id']; ?>')" type="button" class="btn btn-block btn-danger"><i class="fas fa-minus-circle"></i> Delete</button>
                                            </td>
                                        </tr>
                                    <?php endfor; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>no</th>
                                        <th>Jenis</th>
                                        <th>Nama</th>
                                        <th>Instansi</th>
                                        <th>NIP</th>
                                        <th>ttd</th>
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
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    <div class="modal fade" id="modal-add">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Data</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="card-header">
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" class="form-control" name="nama_add" id="nama_add" placeholder="Isikan Namanya" value="" required>
                    </div>
                    <div class="form-group">
                        <label>Instansi</label>
                        <input type="text" class="form-control" name="instansi_add" id="instansi_add" placeholder="Isikan Instansinya" value="" required>
                    </div>
                    <input type="hidden" name="id_mus" id="id_mus" value="">
                    <button type="submit" class="simpanbtnadd btn btn-primary"><i class="fa fa-plus-circle"></i> Simpan</button>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

</div>
<!-- /.content-wrapper -->

<!-- Back to top button -->
<button type="button" class="btn btn-danger btn-floating btn-lg" id="btn-back-to-top">
    <i class="fas fa-arrow-up"></i>
</button>

<?= $this->endSection(); ?>

<?= $this->section('javascriptkhusus'); ?>
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

    const limaster = document.querySelector('.limaster');
    const ahrefmaster = document.querySelector('.ahrefmaster');
    const ahrefmasterttd = document.querySelector('.ahrefmasterttd');

    limaster.classList.add("menu-open");
    ahrefmaster.classList.add("active");
    ahrefmasterttd.classList.add("active");
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