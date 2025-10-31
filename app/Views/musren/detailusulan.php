<?= $this->extend('layouts/template'); ?>

<?= $this->section('content'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>VERIFIKASI <?= $nama_kecamatan; ?> (<?= $nama_bidang; ?>)</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Data</a></li>
                        <li class="breadcrumb-item active">Usulan Musrenbang</li>
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
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>no</th>
                                        <th>id usulan</th>
                                        <th>Kecamatan</th>
                                        <th>Kamus Usulan</th>
                                        <th>Permasalahan</th>
                                        <th>Alamat</th>
                                        <th>OPD Tujuan</th>
                                        <th>anggaran</th>
                                        <th>volume</th>
                                        <th>Status</th>
                                        <th>Catatan</th>
                                        <th width="120px">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $hitungusul = count($data_usulan); ?>
                                    <?php for ($i = 0; $i < $hitungusul; $i++) : ?>
                                        <?php if ($data_usulan[$i]['prior'] == 1): ?>
                                            <tr>
                                                <td><?= $i + 1; ?></td>
                                                <td><?= $data_usulan[$i]['id_usulan']; ?></td>
                                                <td><?= $data_usulan[$i]['kecamatan']; ?></td>
                                                <td><?= $data_usulan[$i]['kamus_usulan']; ?></td>
                                                <td><?= $data_usulan[$i]['masalah']; ?></td>
                                                <td><?= $data_usulan[$i]['alamat']; ?></td>
                                                <td><?= $data_usulan[$i]['opd_tujuan']; ?></td>
                                                <td><?= $data_usulan[$i]['perkiraan_anggaran']; ?></td>
                                                <td><?= $data_usulan[$i]['volume']; ?></td>
                                                <?php if ($data_usulan[$i]['status'] == 0) { ?>
                                                    <td>Tidak Diakomodir</td>
                                                <?php } else if ($data_usulan[$i]['status'] == 1) { ?>
                                                    <td>Diakomodir</td>
                                                <?php } else if ($data_usulan[$i]['status'] == 99) { ?>
                                                    <td>Belum Diproses</td>
                                                <?php } ?>
                                                <td><?= $data_usulan[$i]['catatan']; ?></td>
                                                <td>
                                                    <button onclick="showdetail('<?= $data_usulan[$i]['id']; ?>')" type="button" class="btn btn-block btn-warning"><i class="far fa-edit"></i> Verifikasi</button>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>no</th>
                                        <th>id usulan</th>
                                        <th>Kecamatan</th>
                                        <th>Kamus Usulan</th>
                                        <th>Permasalahan</th>
                                        <th>Alamat</th>
                                        <th>OPD Tujuan</th>
                                        <th>anggaran</th>
                                        <th>volume</th>
                                        <th>Status</th>
                                        <th>Catatan</th>
                                        <th width="120px">Aksi</th>
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
        <div class="card-footer">
            <a href="/entryusulan"><button type="button" class="btn btn-secondary"><i class="fas fa-arrow-circle-left"></i> Kembali</button></a>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    <div class="modal fade" id="modal-edit">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Detail Data</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="card-header">

                    <div class="form-group">
                        <label>Status Usulan</label>
                        <select class="form-control select2 selectstatusverifikasi" name="status_usulan" id="status_usulan" style="width: 100%;" required>
                        </select>
                    </div>
                    <!-- <div class="form-group">
                        <label>Status</label>
                        <input type="text" class="form-control" name="status" id="status" placeholder="Status Diakomodir/Tdk Diakomodir" value="" required>
                    </div> -->
                    <div class="form-group">
                        <label>Perkiraan Anggaran</label>
                        <input type="number" class="form-control" name="perkiraan_anggaran" id="perkiraan_anggaran" placeholder="Perkiraan Anggaran" value="" required>
                    </div>
                    <div class="form-group">
                        <label>Volume</label>
                        <input type="text" class="form-control" name="volume" id="volume" placeholder="Volume" value="" required>
                    </div>
                    <div class="form-group">
                        <label>Catatan</label>
                        <input type="text" class="form-control" name="catatan" id="catatan" placeholder="Catatan" value="" required>
                    </div>
                    <input type="hidden" name="id_mus" id="id_mus" value="">
                    <button type="submit" class="simpanbtn btn btn-primary"><i class="fa fa-plus-circle"></i> Simpan</button>
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
    const url = window.location.origin;
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

    const liformbidangadmin = document.querySelector('.liformbidangadmin');
    const ahrefformbidangdamin = document.querySelector('.ahrefformbidangdamin');
    const ahrefbidangadmin = document.querySelector('.ahrefbidangadmin');

    liformbidangadmin.classList.add("menu-open");
    ahrefformbidangdamin.classList.add("active");
    ahrefbidangadmin.classList.add("active");

    // SELECT2 STATUS USULAN
    $('#status_usulan').select2({
        placeholder: "Pilih Status",
        ajax: {
            url: url + "/entryusulanmusren/apigetstatususulan",
            dataType: 'json',
            delay: 250,
            data: function(data) {
                return {
                    searchTerm: data.term
                };
            },
            processResults: function(data) {
                return {
                    results: data.data
                };
            },
            cache: true
        }
    });
</script>

<script>
    function showdetail(id) {
        const url = window.location.origin;

        // const status = document.getElementById("status");
        const catatan = document.getElementById("catatan");
        const idmus = document.getElementById("id_mus");
        const perkiraan_anggaran = document.getElementById("perkiraan_anggaran");
        const volume = document.getElementById("volume");

        const formData = {
            id_data: id,
        };

        $.ajax({
            type: "POST",
            url: url + "/entryusulanmusren/apigetdatamusren",
            data: formData,
            dataType: "json",
            headers: {
                "Access-Control-Allow-Origin": "*",
                "Access-Control-Allow-Methods": "POST"
            },
        }).done(function(data) {
            if (data.getdatamusren[0]['status'] == 0) {
                document.getElementById("status_usulan").innerHTML = "<option value='0'>" + "Tidak Diakomodir" + "</option>";
            } else if (data.getdatamusren[0]['status'] == 1) {
                document.getElementById("status_usulan").innerHTML = "<option value='1'>" + "Diakomodir" + "</option>";
            } else if (data.getdatamusren[0]['status'] == 99) {
                document.getElementById("status_usulan").innerHTML = "<option value='99'>" + "Belum Diproses" + "</option>";
            }
            // status.value = data.getdatamusren[0]['status'];
            catatan.value = data.getdatamusren[0]['catatan'];
            idmus.value = id;
            perkiraan_anggaran.value = data.getdatamusren[0]['perkiraan_anggaran'];
            volume.value = data.getdatamusren[0]['volume'];
        });

        $('#modal-edit').modal('show');
    }
</script>

<script>
    $(document).on('click', '.simpanbtn', function(e) {
        e.preventDefault();

        const url = window.location.origin;
        const id = document.getElementById("id_mus").value;
        // const status = document.getElementById("status").value;
        const status = document.getElementById("status_usulan").value;
        const catatan = document.getElementById("catatan").value;
        const perkiraan_anggaran = document.getElementById("perkiraan_anggaran").value;
        const volume = document.getElementById("volume").value;

        const formData = {
            id_data: id,
            status_data: status,
            catatan_data: catatan,
            perkiraan_anggaran_data: perkiraan_anggaran,
            volume_data: volume,
        };

        $.ajax({
            type: "POST",
            url: url + "/entryusulanmusren/updatestatususulan",
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
                        title: 'Gagal menyimpan karena catatan kosong'
                    });
                });
                $('#example1').load(location.href + " #example1");
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
                        title: 'Berhasil menyimpan data'
                    });
                });
                $('#example1').load(location.href + " #example1");
            }
        });
        $('#modal-edit').modal('hide');
    });
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

<?= $this->endSection(); ?>