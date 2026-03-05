<?= $this->extend('layouts/template'); ?>

<?= $this->section('content'); ?>
<style>
    .uppercase {
        text-transform: uppercase;
    }
</style>

<style>
  #preview-grid { gap:.5rem; }
  #preview-grid .thumb { width:100px; }
  #preview-grid .thumb img { cursor:pointer; }
</style>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<!-- Geocoder CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css"/>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= $button ?> Usulan BKK</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Usulan</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('usulan/bkk') ?>">BKK</a></li>
                        <li class="breadcrumb-item active"><?= $button ?></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="<?= $button == 'Tambah' ? 'col-12' : 'col-12' ?>">
                    <div class="card">
                        <form id="<?= $button == 'Tambah' ? 'form-tambah' : 'form-edit' ?>" method="POST" action="<?= $url ?>" enctype="multipart/form-data">
                            <?php if ($button == 'Tambah') : ?>
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <select class="form-control select2" name="kode_opd" id="kode_opd">
                                                <option value="all">Semua OPD</option>
                                                <?php foreach ($ref_opd as $item) : ?>
                                                <option value="<?= $item['kode_opd'] ?>"><?= $item['nama_opd'] ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            <?php endif ?>
                            <div class="card-body">
                                <?php if ($button == 'Tambah') : ?>
                                    <input type="hidden" name="selected_ids" id="selected_ids">
                                    <table id="example1" class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th class="text-center" width="5%">
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input" type="checkbox" id="customCheckboxAll" value="all">
                                                        <label for="customCheckboxAll" class="custom-control-label">&nbsp;</label>
                                                    </div>
                                                </th>
                                                <th class="text-center">Nama Desa</th>
                                                <th class="text-center">OPD</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                <?php else : ?>
                                    <input type="hidden" class="form-control" name="id" id="id" required value="<?= $id ?>">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="form-group col-md-2">
                                                    <label>Tahun Usulan <sup class="text-danger">*</sup></label>
                                                    <input type="text" class="form-control uppercase" name="tahun" id="tahun" value="<?= $tahun ?>" readonly>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Nama Desa <sup class="text-danger">*</sup></label>
                                                    <input type="text" class="form-control" name="nama_desa" id="nama_desa" value="<?= $nama_desa ?>" readonly>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label>Nama Kabupaten <sup class="text-danger">*</sup></label>
                                                    <input type="text" class="form-control" name="nama_kabupaten" id="nama_kabupaten" readonly value="<?= $nama_kabupaten ?>">
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label>Nama Kecamatan <sup class="text-danger">*</sup></label>
                                                    <input type="text" class="form-control" name="nama_kecamatan" id="nama_kecamatan" readonly value="<?= $nama_kecamatan ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label>APBD</label>
                                                    <input type="text" class="form-control nominal" name="apbd" id="apbd" value="<?= $apbd > 0 ? number_format($apbd, 0, ',', '.') : '' ?>">
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label>Perubahan PERBUP 1</label>
                                                    <input type="text" class="form-control nominal" name="perubahan_perbup_1" id="perubahan_perbup_1" value="<?= $perubahan_perbup_1 > 0 ? number_format($perubahan_perbup_1, 0, ',', '.') : '' ?>">
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label>Perubahan PERBUP 2</label>
                                                    <input type="text" class="form-control nominal" name="perubahan_perbup_2" id="perubahan_perbup_2" value="<?= $perubahan_perbup_2 > 0 ? number_format($perubahan_perbup_2, 0, ',', '.') : '' ?>">
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label>P-APBD</label>
                                                    <input type="text" class="form-control nominal" name="papbd" id="papbd" value="<?= $papbd > 0 ? number_format($papbd, 0, ',', '.') : '' ?>">
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label>Nama Program BKK</label>
                                                    <input type="text" class="form-control" name="nama_program_bkk" id="nama_program_bkk" value="<?= $nama_program_bkk ?? '' ?>">
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label>Keterangan</label>
                                                    <input type="text" class="form-control" name="keterangan" id="keterangan" value="<?= $keterangan ?? '' ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div id="wrap-foto" class="form-group">
                                                <label>Foto Pendukung <sup class="text-danger">(Maksimal 3 Foto)</sup></label>

                                                <!-- daftar input-file dinamis -->
                                                <div id="file-list"></div>

                                                <!-- tombol tambah input -->
                                                
                                                <button type="button" id="btn-add-foto" class="btn btn-success btn-sm mt-2">
                                                    <i class="fa fa-plus"></i> Tambah Foto
                                                </button>

                                                <!-- preview grid -->
                                                <div id="preview-grid" class="d-flex flex-wrap gap-2 mt-3">
                                                <?php if (!empty($foto)): ?>
                                                    <?php foreach ($foto as $f): ?>
                                                    <div class="thumb position-relative mr-2 mb-2"
                                                        data-id="old-<?= esc($f['id']) ?>" style="width:100px;">
                                                        <img src="<?= base_url($f['url_name']) ?>"
                                                            class="img-thumbnail w-100"
                                                            alt="preview"
                                                            data-name="<?= esc($f['originale_name'] ?? $f['file_name']) ?>">
                                                        <!-- tombol hapus -->
                                                        <button type="button"
                                                                class="btn btn-sm btn-danger position-absolute btn-del-old"
                                                                data-doc-id="<?= esc($f['id']) ?>"
                                                                style="right:4px; top:4px; padding:2px 6px;">
                                                        <i class="fa fa-trash"></i>
                                                        </button>
                                                    </div>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <label>Latitude</label>
                                                    <input type="text" class="form-control" name="lat" id="lat" value="<?= $latitude ?? '' ?>">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Longitude</label>
                                                    <input type="text" class="form-control" name="lng" id="lng" value="<?= $longitude ?? '' ?>">
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label>&nbsp;</label>
                                                    <button type="button" class="btn btn-info btn-block" id="btn-open-maps" <?= !empty($latitude) ? '' : (!empty($longitude) ? '' : 'disabled') ?> ><i class="fas fa-map mr-1"></i> Lihat Peta</button>
                                                </div>
                                            </div>
                                            <div id="map" style="height:380px;border-radius:12px;"></div>
                                        </div>
                                    </div>
                                <?php endif ?>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer text-right">
                                <a href="<?= base_url('usulan/bkk') ?>" class="btn btn-secondary">Batal</a>
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </form>
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


<!-- Modal Preview Foto -->
<div class="modal fade" id="modalPreview" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content bg-dark">
      <div class="modal-body p-0 position-relative">
        <button type="button" class="close text-white position-absolute" data-dismiss="modal"
                style="right:.75rem; top:.5rem; z-index:10; opacity:.9;">&times;</button>

        <!-- Navigasi -->
        <button type="button" class="btn btn-light position-absolute prev-btn" style="left:.5rem; top:50%; transform:translateY(-50%); z-index:10;">&#8249;</button>
        <button type="button" class="btn btn-light position-absolute next-btn" style="right:.5rem; top:50%; transform:translateY(-50%); z-index:10;">&#8250;</button>

        <!-- Gambar besar -->
        <div class="w-100 d-flex justify-content-center align-items-center" style="min-height:70vh;">
          <img id="previewLarge" src="" alt="preview" style="max-width:100%; max-height:90vh; object-fit:contain;">
        </div>
      </div>
      <div class="modal-footer justify-content-between">
            <small id="previewName" class="text-truncate"></small>
            <a id="btnDownload" class="btn btn-outline-light" download>Unduh</a>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('javascriptkhusus'); ?><!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- Geocoder JS -->
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
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

    const old_kecamatan = '<?= $kecamatan ?? '' ?>';
    const old_desa = '<?= $desa ?? '' ?>';
    const old_kegiatan = '<?= $kegiatan ?? '' ?>';
    const old_sub_kegiatan = '<?= $sub_kegiatan ?? '' ?>';

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
        $('.select2').select2();
        
        const MAX_CHECK = 100;
        // tempat nyimpen id yang sudah dipilih, lintas halaman
        const selectedIds = new Set();

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
            pageLength: 10,
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?= site_url('usulan/bkk/layak-usulan-json'); ?>",
                type: "POST",
                data: function (d) {
                    d.kode_opd = $('#kode_opd').val(); // kirim kode_opd ke server
                }
            },
            columns: [
                { // checkbox
                    data: 'id',
                    orderable: false,
                    className: 'text-center',
                    render: function (data, type, row) {
                        return `
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input child-check"
                                        type="checkbox"
                                        id="customCheckbox${data}"
                                        value="${data}">
                                <label for="customCheckbox${data}"
                                        class="custom-control-label">&nbsp;</label>
                            </div>`;
                    }
                },
                { data: 'nama_desa' },
                { data: 'nama_opd' }
            ],
            columnDefs: [
                { orderable: false, targets: 0 }
            ],
            order: [[1, 'asc']],
            // ini penting: setiap tabel digambar ulang, kita sync checkbox
            drawCallback: function () {
                syncCheckboxes();
            }
        });

        $('#kode_opd').on('change', function () {
            selectedIds.clear();      // biasanya lebih aman di-clear saat ganti OPD
            $('#customCheckboxAll').prop('checked', false);
            table.ajax.reload();
        });

        function syncCheckboxes() {
            $('#example1 tbody tr').each(function () {
                const $chk = $(this).find('.child-check');
                const id = $chk.val();

                $chk.prop('checked', selectedIds.has(id));

                if (selectedIds.size >= MAX_CHECK && !selectedIds.has(id)) {
                    $chk.prop('disabled', true);
                } else {
                    $chk.prop('disabled', false);
                }
            });
        }

        // pertama kali jalan
        syncCheckboxes();

        // klik satuan
        $('#example1').on('change', '.child-check', function() {
            const id = $(this).val();

            if (this.checked) {
                if (selectedIds.size >= MAX_CHECK) {
                    // batalin
                    this.checked = false;
                    Swal.fire({
                        title: 'Peringatan',
                        text: 'Maksimal hanya boleh memilih ' + MAX_CHECK + ' data.',
                        icon: 'info', // Menampilkan icon info
                        confirmButtonText: 'OK' // Tombol konfirmasi
                    });
                    return;
                }
                selectedIds.add(id);
            } else {
                selectedIds.delete(id);
            }

            syncCheckboxes();
        });

        // klik select-all halaman aktif
        $('#customCheckboxAll').on('change', function() {
            const mauCheck = this.checked;

            if (mauCheck) {
                // centang yang ada di halaman ini, tapi hormati batas
                $('#example1 .child-check').each(function() {
                    const id = $(this).val();
                    if (!selectedIds.has(id) && selectedIds.size < MAX_CHECK) {
                        selectedIds.add(id);
                    }
                });
            } else {
                // uncheck semua yang kelihatan
                $('#example1 .child-check').each(function() {
                    const id = $(this).val();
                    selectedIds.delete(id);
                });
            }

            syncCheckboxes();
        });

        $('#form-tambah').on('submit', function(e) {
            if (selectedIds.size === 0) {
                Swal.fire({
                    title: 'Peringatan',
                    text: 'Pilih minimal 1 data dulu.',
                    icon: 'info', // Menampilkan icon info
                    confirmButtonText: 'OK' // Tombol konfirmasi
                });
                e.preventDefault();
                return;
            }
            // masukkan ke hidden input sebagai JSON atau csv
            $('#selected_ids').val(JSON.stringify(Array.from(selectedIds)));
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('.nominal').on('keyup', function() {
            var angka = $(this).val();
            if (angka === '' || angka === '0' || angka === '0.00') {
                // $(this).val('0');
                return;
            }
            $(this).val(formatRibuan(angka));
        });

        function replaceAngka(angka) {
            let number_string = angka.replace(/[^,\d]/g, '').toString();
            // kalau isinya cuma nol semua, kembalikan '0'
            if (/^0+$/.test(number_string)) {
                return '0';
            }
            return number_string;
        }

        function formatRibuan(angka) {
            // var number_string = angka.replace(/[^,\d]/g, '').toString(),
            var number_string = replaceAngka(angka)
            split = number_string.split(','),
                sisa = split[0].length % 3,
                angka_hasil = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                angka_hasil += separator + ribuan.join('.');
            }

            angka_hasil = split[1] != undefined ? angka_hasil + ',' + split[1] : angka_hasil;
            return angka_hasil;
        }
    })
</script>
<script>
(function(){
  const MAX_FILES = 3;
  const wrapFoto   = $('#wrap-foto');
  const fileList   = $('#file-list');
  const previewGrid= $('#preview-grid');
  const btnAdd     = $('#btn-add-foto');
  let existingCount = <?= json_encode(isset($foto) ? count($foto) : 0) ?>;
  if ($('#status').val() === '1') $('#wrap-foto').show();
  if (existingCount >= 3) $('#btn-add-foto').prop('disabled', true);

  // Tampil/sembunyi sesuai status
  $('#status').on('change', function () {
    if (this.value === '1') {
      wrapFoto.slideDown();
    } else {
      clearAllFiles();
      wrapFoto.slideUp();
    }
  });

  // Tambah input-file (max 3)
  btnAdd.on('click', function () {
    addInput();
  });

  function addInput(){
    const count = $('.file-item').length;
    if (count >= MAX_FILES) return;

    const idx = Date.now(); // id unik
    const html = `
      <div class="input-group mb-2 file-item" data-id="${idx}">
        <input type="file" class="form-control input-foto" 
               name="foto[]" accept=".jpg,.jpeg,.png" data-id="${idx}" required>
        <div class="input-group-append">
          <button type="button" class="btn btn-danger btn-remove" title="Hapus">
            <i class="fa fa-trash"></i>
          </button>
        </div>
      </div>`;
    fileList.append(html);
    updateAddButton();
  }

  // Hapus satu input + preview terkait
  $(document).on('click', '.btn-remove', function(){
    const wrap = $(this).closest('.file-item');
    const id   = wrap.data('id');
    // hapus preview yg terkait input ini
    previewGrid.find(`.thumb[data-id="${id}"]`).remove();
    wrap.remove();
    updateAddButton();
  });

  // Saat pilih file -> buat/replace preview untuk input itu
  $(document).on('change', '.input-foto', function(e){
    const file = this.files && this.files[0];
    const id   = $(this).data('id');

    // hapus preview lama utk input ini
    previewGrid.find(`.thumb[data-id="${id}"]`).remove();

    if (!file) { updateAddButton(); return; }
    if (!file.type.startsWith('image/')) {
      alert('File harus gambar (JPG/PNG/JPEG).');
      this.value = '';
      updateAddButton();
      return;
    }

    // Batasi total file terpilih (jumlah input yang berisi file)
    const filled = $('.input-foto').filter(function(){ return this.files && this.files.length; }).length;
    if (filled > MAX_FILES) {
      alert('Maksimal 3 foto.');
      this.value = '';
      updateAddButton();
      return;
    }

    const url = URL.createObjectURL(file);
    const thumb = `
      <div class="thumb mr-2 mb-2" data-id="${id}" style="width:100px;">
        <img src="${url}" class="img-thumbnail w-100" alt="preview">
      </div>`;
    previewGrid.append(thumb);
    updateAddButton();
  });

  function updateAddButton(){
    // disable tombol tambah jika sudah 3 input atau 3 file terpilih
    const totalInputs = $('.file-item').length;
    const filled = $('.input-foto').filter(function(){ return this.files && this.files.length; }).length;
    btnAdd.prop('disabled', (totalInputs + existingCount) >= MAX_FILES || (filled + existingCount) >= MAX_FILES);
  }

  function clearAllFiles(){
    fileList.empty();
    previewGrid.empty();
    updateAddButton();
  }

  let currentIndex = -1;

  // Klik thumbnail -> buka modal
  $(document).on('click', '#preview-grid .thumb img', function(){
    const $imgs = $('#preview-grid .thumb img');
    currentIndex = $imgs.index(this);
    showImageAt(currentIndex);
    $('#modalPreview').modal('show');
  });

  // Tombol navigasi
  $('#modalPreview .next-btn').on('click', function(){
    const total = $('#preview-grid .thumb img').length;
    if (total === 0) return;
    currentIndex = (currentIndex + 1) % total;
    showImageAt(currentIndex);
  });
  $('#modalPreview .prev-btn').on('click', function(){
    const total = $('#preview-grid .thumb img').length;
    if (total === 0) return;
    currentIndex = (currentIndex - 1 + total) % total;
    showImageAt(currentIndex);
  });

  // Navigasi keyboard saat modal terbuka
  $(document).on('keydown', function(e){
    if (!$('#modalPreview').hasClass('show')) return;
    if (e.key === 'ArrowRight') $('#modalPreview .next-btn').click();
    if (e.key === 'ArrowLeft')  $('#modalPreview .prev-btn').click();
    if (e.key === 'Escape')     $('#modalPreview').modal('hide');
  });

  // Utility: tampilkan gambar ke-i
  function showImageAt(i){
    const $imgs = $('#preview-grid .thumb img');
    if (i < 0 || i >= $imgs.length) return;
    const $img = $imgs.eq(i);

    const src  = $img.attr('src');            // objectURL dari thumbnail
    const name = $img.data('name') || '';     // diisi saat change input (di bawah)

    $('#previewLarge').attr('src', src);
    $('#previewName').text(name);
    $('#btnDownload').attr('href', src).attr('download', name || 'foto.jpg');
  }

  // Saat pilih file, simpan nama file ke data-name (tambahan kecil)
  $(document).on('change', '.input-foto', function(){
    const file = this.files && this.files[0];
    const id   = $(this).data('id');

    // (bagian preview lama tetap) ...
    if (file) {
      const url = URL.createObjectURL(file);

      // ganti/buat ulang thumb (kode lama sudah menambahkan <img>)
      // Tambahkan atribut data-name ke <img> agar modal bisa menampilkan nama file
      const $thumb = $('#preview-grid').find(`.thumb[data-id="${id}"] img`);
      $thumb.attr('src', url).attr('data-name', file.name);
    }
  });

  const tokenName = "<?= csrf_token() ?>";

  // hapus foto lama (thumbnail dengan .btn-del-old)
  $(document).on('click', '.btn-del-old', function () {
    if (!confirm('Hapus foto ini?')) return;

    const $btn  = $(this);
    const docId = $btn.data('doc-id');

    // disable biar gak dobel klik
    $btn.prop('disabled', true);

    $.ajax({
      url: "<?= site_url('usulan/bkk/foto-delete') ?>",
      type: "POST",
      dataType: "json",
      data: {
        [tokenName]: $('meta[name="<?= csrf_token() ?>"]').attr('content'),
        doc_id: docId
      },
      success: function (res) {
        if (res.csrf) $('meta[name="<?= csrf_token() ?>"]').attr('content', res.csrf);

        if (!res || !res.ok) {
          alert(res && res.msg ? res.msg : 'Gagal menghapus foto.');
          $btn.prop('disabled', false);
          return;
        }

        // hapus thumbnail dari grid
        $btn.closest('.thumb').remove();

        // kurangi hitungan existing & re-enable tombol tambah bila perlu
        existingCount = Math.max(0, existingCount - 1);
        updateAddButton();
      },
      error: function () {
        alert('Terjadi kesalahan jaringan.');
        $btn.prop('disabled', false);
      }
    });
  });

})();
</script>
<script>
  const defaultLat = -7.8166;   // Kediri
  const defaultLng = 112.0110;  // Kediri

  const map = L.map('map').setView([defaultLat, defaultLng], 13);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap'
  }).addTo(map);

  let marker = null;

  const $lat = $('#lat');
  const $lng = $('#lng');
  const $btn = $('#btn-open-maps');

  function isValidLatLng(lat, lng) {
    lat = parseFloat(lat);
    lng = parseFloat(lng);
    if (Number.isNaN(lat) || Number.isNaN(lng)) return false;
    if (lat < -90 || lat > 90) return false;
    if (lng < -180 || lng > 180) return false;
    return true;
  }

  function setButtonEnabled(enabled) {
    $btn.prop('disabled', !enabled);
  }

  function clearPoint() {
    $lat.val('');
    $lng.val('');
    $('#span_lat').text('');
    $('#span_long').text('');
    // marker boleh dihapus atau biarkan terakhir; sesuai kebutuhan:
    if (marker) {
      map.removeLayer(marker);
      marker = null;
    }
    setButtonEnabled(false);
  }

  function setPoint(lat, lng, { pan = false, doReverse = false } = {}) {
    lat = parseFloat(lat);
    lng = parseFloat(lng);

    // isi field (tanpa enter kalau kosong)
    $lat.val(lat.toFixed(15));
    $lng.val(lng.toFixed(15));

    $('#span_lat').text(': ' + lat.toFixed(6));
    $('#span_long').text(': ' + lng.toFixed(6));

    if (!marker) {
      marker = L.marker([lat, lng], { draggable: true }).addTo(map);

      marker.on('dragend', async (e) => {
        const p = e.target.getLatLng();
        setPoint(p.lat, p.lng, { pan: false, doReverse: true });
      });
    } else {
      marker.setLatLng([lat, lng]);
    }

    if (pan) map.setView([lat, lng], 16);

    setButtonEnabled(true);

    if (doReverse) reverseGeocode(lat, lng);
  }

  // Klik peta => set marker + reverse geocode
  map.on('click', async (e) => {
    setPoint(e.latlng.lat, e.latlng.lng, { pan: false, doReverse: true });
  });

  // Geocoder
  const geocoder = L.Control.geocoder({ defaultMarkGeocode: false })
    .on('markgeocode', async function(e) {
      const center = e.geocode.center;
      map.setView(center, 17);
      setPoint(center.lat, center.lng, { pan: false, doReverse: false });
      $('#span_alamat').text(e.geocode.name || '');
      setButtonEnabled(true);
    })
    .addTo(map);

  async function reverseGeocode(lat, lng) {
    try {
      const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`;
      const res = await fetch(url, { headers: { 'Accept': 'application/json' }});
      const json = await res.json();
      $('#span_alamat').text(': ' + (json.display_name || ''));
    } catch (err) {
      console.warn('Reverse geocode gagal:', err);
    }
  }

  // === INPUT EVENT: user isi lat/lng => marker otomatis muncul + tombol enable ===
  function syncFromInputs() {
    const latVal = $lat.val().trim();
    const lngVal = $lng.val().trim();

    // kalau salah satu kosong => jangan isi marker, tombol disable
    if (latVal === '' || lngVal === '') {
      // kalau Anda ingin marker ikut hilang saat user hapus input:
      if (marker) { map.removeLayer(marker); marker = null; }
      setButtonEnabled(false);
      return;
    }

    // kalau tidak valid => disable juga (opsional)
    if (!isValidLatLng(latVal, lngVal)) {
      setButtonEnabled(false);
      return;
    }

    // valid => set marker
    setPoint(latVal, lngVal, { pan: false, doReverse: false });
  }

  // realtime saat user mengetik
  $lat.on('input', syncFromInputs);
  $lng.on('input', syncFromInputs);

  // === INIT dari DB (kalau edit data) ===
  const existingLat = "<?= $latitude ?>";
  const existingLng = "<?= $longitude ?>";

  if (isValidLatLng(existingLat, existingLng)) {
    setPoint(existingLat, existingLng, { pan: true, doReverse: false });
  } else {
    // kosong -> field jangan diisi & tombol disable
    clearPoint();
    // tetap biarkan map di Kediri
    map.setView([defaultLat, defaultLng], 13);
  }

  // === Tombol Lihat Peta (contoh aksi) ===
  // Sesuaikan: misal buka modal / scroll ke map / fokus map
  $btn.on('click', function() {
    map.invalidateSize();      // penting kalau map di dalam tab/modal
    if (marker) map.panTo(marker.getLatLng());
  });
</script>
<script>
  $('#btn-open-maps').on('click', function () {
    const lat = $('#lat').val().trim();
    const lng = $('#lng').val().trim();

    // validasi sederhana
    const latNum = parseFloat(lat);
    const lngNum = parseFloat(lng);
    if (isNaN(latNum) || isNaN(lngNum)) return;

    // Google Maps (pin di koordinat)
    const url = `https://www.google.com/maps?q=${latNum},${lngNum}`;

    // opsi A: buka tab baru
    window.open(url, '_blank');

    // opsi B: redirect di tab yang sama (pakai ini kalau mau)
    // window.location.href = url;
  });
</script>

<?= $this->endSection(); ?>