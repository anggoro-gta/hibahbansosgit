<?= $this->extend('layouts/template'); ?>

<?= $this->section('content'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Home</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Halaman Utama</li>
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
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3><?= $counttotalusulan; ?></h3>

                                <p>Total Usulan</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3><?= $countakomodir; ?></h3>

                                <p>Diakomodir</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-android-checkbox-outline"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->

                    <!-- <div class="col-lg-3 col-6">                        
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>44</h3>

                                <p>User Registrations</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div> -->

                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3><?= $counttdkakomodir; ?></h3>

                                <p>Tdk Diakomodir</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-close-circled"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3><?= $countblmproses; ?></h3>

                                <p>Blm Proses</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-refresh"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">Distribusi Usulan</h3>
                                <!-- <a href="javascript:void(0);">View Report</a> -->
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex">
                                <p class="d-flex flex-column">
                                    <span class="text-bold text-lg">1xxx</span>
                                    <span>Total usulan</span>
                                </p>
                                <!-- <p class="ml-auto d-flex flex-column text-right">
                                    <span class="text-success">
                                        <i class="fas fa-arrow-up"></i> 33.1%
                                    </span>
                                    <span class="text-muted">Since last month</span>
                                </p> -->
                            </div>
                            <!-- /.d-flex -->

                            <div class="position-relative mb-4">
                                <canvas id="sales-chart" height="200"></canvas>
                            </div>

                            <div class="d-flex flex-row justify-content-end">
                                <span class="mr-2">
                                    <i class="fas fa-square text-primary"></i> usulan
                                </span>

                                <!-- <span>
                                    <i class="fas fa-square text-gray"></i> Last year
                                </span> -->
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <!-- About Me Box -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">About Me</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <strong><i class="fas fa-book mr-1"></i> Bappeda</strong>

                                <p class="text-muted">
                                    Badan Perencanaan Pembangunan Daerah Kabupaten Kediri.
                                </p>

                                <hr>

                                <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>

                                <p class="text-muted">Jalan Soekarno-Hatta Nomor 1 Kediri.</p>

                                <hr>

                                <strong><i class="fas fa-phone-alt mr-1"></i> Phone</strong>

                                <p class="text-muted">(0354) 689995.</p>

                                <hr>

                                <strong><i class="fas fa-globe mr-1"></i> Website</strong>

                                <p class="text-muted">bappeda.kedirikab.go.id</p>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-header p-2">
                                <ul class="nav nav-pills">
                                    <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Gallery</a></li>
                                </ul>
                            </div><!-- /.card-header -->
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="active tab-pane" id="activity">
                                        <!-- Post -->
                                        <div class="post">
                                            <div class="user-block">
                                                <img class="img-circle img-bordered-sm" src="../../assets/dist/img/galery.jpg" alt="User Image">
                                                <span class="username">
                                                    <a href="#">Photos</a>
                                            </div>
                                            <!-- /.user-block -->
                                            <div class="row mb-3">
                                                <div class="col-sm-6">
                                                    <img class="img-fluid" src="../../assets/dist/img/photo1.png" alt="Photo">
                                                </div>
                                                <!-- /.col -->
                                                <div class="col-sm-6">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <img class="img-fluid mb-3" src="../../assets/dist/img/photo2.png" alt="Photo">
                                                            <img class="img-fluid" src="../../assets/dist/img/photo3.jpg" alt="Photo">
                                                        </div>
                                                        <!-- /.col -->
                                                        <div class="col-sm-6">
                                                            <img class="img-fluid mb-3" src="../../assets/dist/img/photo4.jpg" alt="Photo">
                                                            <img class="img-fluid" src="../../assets/dist/img/photo1.png" alt="Photo">
                                                        </div>
                                                        <!-- /.col -->
                                                    </div>
                                                    <!-- /.row -->
                                                </div>
                                                <!-- /.col -->
                                            </div>
                                            <!-- /.row -->
                                        </div>
                                        <!-- /.post -->
                                    </div>
                                    <!-- /.tab-pane -->
                                </div>
                                <!-- /.tab-content -->
                            </div><!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
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
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?= $this->endSection(); ?>

<?= $this->section('javascriptkhusus'); ?>
<script>
    const lidashboard = document.querySelector('.li-dashboard');
    const ahrefdashboard = document.querySelector('.ahref-dashboard');
    const ahrefhome = document.querySelector('.ahref-home');

    lidashboard.classList.add("menu-open");
    ahrefdashboard.classList.add('active');
    ahrefhome.classList.add("active");
</script>

<script>
    var ticksStyle = {
        fontColor: '#495057',
        fontStyle: 'bold'
    }

    var mode = 'index'
    var intersect = true

    var labelsdata = ['mojo', 'kras', 'ngadiluwih', 'kandat', 'wates', 'ngancar', 'puncu', 'plosoklaten', 'gurah', 'pagu', 'gampengrejo', 'grogol', 'papar', 'purwoasri', 'plemahan', 'pare', 'kepung', 'kandangan', 'tarokan', 'kunjang', 'banyakan', 'ringinrejo', 'kayenkidul', 'ngasem', 'badas', 'semen'];
    var datavalue = [10, 15, 9, 9, 10, 12, 15, 1, 8, 2, 14, 16, 2, 11, 5, 9, 3, 5, 9, 11, 21, 6, 7, 9, 14, 12, 4];

    var $salesChart = $('#sales-chart')
    // eslint-disable-next-line no-unused-vars
    var salesChart = new Chart($salesChart, {
        type: 'bar',
        data: {
            labels: labelsdata,
            datasets: [{
                backgroundColor: '#007bff',
                borderColor: '#007bff',
                data: datavalue
            }]
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                mode: mode,
                intersect: intersect
            },
            hover: {
                mode: mode,
                intersect: intersect
            },
            legend: {
                display: false
            },
            scales: {
                yAxes: [{
                    // display: false,
                    gridLines: {
                        display: true,
                        lineWidth: '4px',
                        color: 'rgba(0, 0, 0, .2)',
                        zeroLineColor: 'transparent'
                    },
                    ticks: $.extend({
                        beginAtZero: true,

                        // Include a dollar sign in the ticks
                        callback: function(value) {
                            if (value >= 1000) {
                                value /= 1000
                                value += 'k'
                            }

                            return '$' + value
                        }
                    }, ticksStyle)
                }],
                xAxes: [{
                    display: true,
                    gridLines: {
                        display: false
                    },
                    ticks: ticksStyle
                }]
            }
        }
    })
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