<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-sm-inline-block">
            <?php
            $thn = null;
            if (isset($_SESSION['years'])) {
                $thn = $_SESSION['years'];
            }
            ?>
            <select class="form-control" id="years">
                <option value="" selected disabled>Tahun Anggaran</option>
                <?php musren_years_dropdown($thn); ?>
            </select>
        </li>
        <!-- <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">Contact</a>
        </li> -->
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                <i class="fas fa-th-large"></i>
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="<?= base_url(); ?>/assets/dist/img/logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">hibah bansos</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= base_url(); ?>/assets/dist/img/<?= user()->user_image; ?>" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?= user()->username; ?></a>
            </div>
        </div>

        <!-- Sidebar year-->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <?php if (isset($_SESSION['years'])) { ?>
                    <a href="#" class="d-block">Tahun Anggaran <?= $_SESSION['years']; ?></a>
                <?php } else { ?>
                    <a href="#" class="d-block">Home</a>
                <?php } ?>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <li class="nav-item li-dashboard">
                    <a href="#" class="nav-link ahref-dashboard">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/" class="nav-link ahref-home">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Home</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/gantipassword" class="nav-link ahref-gantipassword">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Ganti Password</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <?php if (in_groups(['admin', 'useropd'])) : ?>
                    <?php if (in_groups('admin')) : ?>
                        <li class="nav-item lisetting">
                            <a href="#" class="nav-link ahrefsetting">
                                <i class="nav-icon fas fa-cog"></i>
                                <p>
                                    Setting
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="/indexusers" class="nav-link ahrefsettingusers">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>users</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item limaster">
                        <a href="#" class="nav-link ahrefmaster">
                            <i class="nav-icon fas fa-database"></i>
                            <p>
                                Master
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url('master/hibah'); ?>" class="nav-link ahref-master-hibah">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Hibah</p>
                                </a>
                            </li>
                        </ul>

                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url('master/bansos'); ?>" class="nav-link ahref-master-bansos">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Bansos</p>
                                </a>
                            </li>
                        </ul>

                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url('master/bkk'); ?>" class="nav-link ahref-master-bkk">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>BKK</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <?php if (in_groups('useropd')) : ?>
                        <li class="nav-item liinputadmin">
                            <a href="#" class="nav-link ahrefinputadmin">
                                <i class="nav-icon fas fa-edit"></i>
                                <p>
                                    Input
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= base_url('usulan/hibah'); ?>" class="nav-link ahref-usulan-hibah">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Usulan Hibah</p>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= base_url('usulan/bansos'); ?>" class="nav-link ahref-usulan-bansos">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Usulan Bansos</p>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= base_url('usulan/bkk'); ?>" class="nav-link ahref-usulan-bkk">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Usulan BKK</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <?php if (in_groups('admin')) : ?>
                        <li class="nav-item liviewadmin">
                            <a href="#" class="nav-link ahrefviewadmin">
                                <i class="nav-icon fas fa-eye"></i>
                                <p>
                                    View
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= base_url('usulan/hibah'); ?>" class="nav-link ahref-view-usulan-hibah">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Usulan Hibah</p>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= base_url('usulan/bansos'); ?>" class="nav-link ahref-view-usulan-bansos">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Usulan Bansos</p>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= base_url('usulan/bkk'); ?>" class="nav-link ahref-view-usulan-bkk">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Usulan BKK</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <!-- <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-print"></i>
                            <p>
                                Cetak
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/printbamusrenbang" class="nav-link" target="_blank">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Print BA</p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/printlampiranba" class="nav-link" target="_blank">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Print Lampiran BA</p>
                                </a>
                            </li>
                        </ul>
                    </li> -->
                <?php endif; ?>

                <?php if (in_groups(['useropd', 'userkec'])) : ?>
                    <!-- <li class="nav-item">
                        <a href="<?= base_url('usulan/data'); ?>" class="nav-link ahref-usulan">
                            <i class="nav-icon fas fa-list"></i>
                            <p>
                                Data Usulan
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('usulan/laporan'); ?>" class="nav-link ahref-usulan-laporan">
                            <i class="nav-icon fas fa-file"></i>
                            <p>
                                Data laporan
                            </p>
                        </a>
                    </li> -->
                <?php endif; ?>

                <?php if (in_groups('bidangadmin')) : ?>
                    <li class="nav-item limaster">
                        <a href="#" class="nav-link ahrefmaster">
                            <i class="nav-icon fas fa-database"></i>
                            <p>
                                Master
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/masterttd" class="nav-link ahrefmasterttd">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Master ttd</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item liformbidangadmin">
                        <a href="#" class="nav-link ahrefformbidangdamin">
                            <i class="nav-icon fas fa-edit"></i>
                            <p>
                                Forms
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <!-- <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/penandatanganan" class="nav-link ahrefpenandatanganan">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Entri Penandatanganan</p>
                                </a>
                            </li>
                        </ul> -->
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/entryusulan" class="nav-link ahrefbidangadmin">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Proses Usulan</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-print"></i>
                            <p>
                                Cetak
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/printbamusrenbang" class="nav-link" target="_blank">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Print BA</p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/printlampiranba" class="nav-link" target="_blank">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Print Lampiran BA</p>
                                </a>
                            </li>
                        </ul>
                    </li> -->
                <?php endif; ?>

                <li class="nav-item">
                    <a href="<?= base_url('logout'); ?>" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>
                            log out
                            <!-- <i class="fas fa-angle-left right"></i> -->
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
<script>
    (function() {
        const $years = document.getElementById('years');
        if (!$years) return;

        const url = "<?= site_url('home/saveyears'); ?>";
        const csrfName = "<?= csrf_token() ?>";
        let csrfHash = "<?= csrf_hash() ?>";

        $years.addEventListener('change', function() {
            const year = this.value;
            if (!year) return;

            const form = new FormData();
            form.append('year', year);
            form.append(csrfName, csrfHash);

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: form
                })
                .then(r => r.json())
                .then(json => {
                    if (!json.ok) {
                        alert(json.msg || 'Gagal menyimpan tahun');
                        return;
                    }
                    if (json.csrf) csrfHash = json.csrf; // update token kalau rotate
                    location.reload(); // biar semua bagian baca session baru
                })
                .catch(() => alert('Koneksi gagal'));
        });
    })();
</script>