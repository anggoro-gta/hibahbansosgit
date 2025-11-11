<!-- Navbar -->
<nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
    <div class="container">
        <a href="#" class="navbar-brand">
            <img src="<?= base_url(); ?>/assets/dist/img/logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">Hibah Bansos</span>
        </a>

        <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse order-3" id="navbarCollapse">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="/" class="nav-link">Login</a>
                </li>                            
            </ul>
            
        </div>

        <!-- Right navbar links -->
        <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">           
                        
            <li class="nav-item">
                <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                    <i class="fas fa-th-large"></i>
                </a>
            </li>
        </ul>
    </div>
</nav>
<!-- /.navbar -->
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