<?= $this->extend('layouts/template'); ?>

<?= $this->section('content'); ?>
<style>
  #preview-grid { gap:.5rem; }
  #preview-grid .thumb { width:100px; }
  #preview-grid .thumb img { cursor:pointer; }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Detail Usulan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('usulan/data'); ?>">Data Usulan</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
          <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <?= esc(session()->getFlashdata('success')) ?>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php endif; ?>

          <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <?= esc(session()->getFlashdata('error')) ?>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php endif; ?>
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title text-primary"><i class="fas fa-edit"></i> <?= $row['id_usulan'] ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12 col-sm-4">
                                            <div class="info-box bg-light">
                                                <div class="info-box-content">
                                                    <span class="info-box-text text-center text-muted">Kecamatan</span>
                                                    <span class="info-box-number text-center text-muted mb-0"><?= $row['kecamatan'] ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="info-box bg-light">
                                                <div class="info-box-content">
                                                    <span class="info-box-text text-center text-muted">Kelurahan</span>
                                                    <span class="info-box-number text-center text-muted mb-0"><?= $row['kelurahan'] ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="info-box bg-light">
                                                <div class="info-box-content">
                                                    <span class="info-box-text text-center text-muted">Anggaran</span>
                                                    <span class="info-box-number text-center text-muted mb-0">Rp <?= number_format($row['perkiraan_anggaran'], 0, ',', '.') ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-muted">
                                        <p><b class="d-block">Usulan</b>
                                            <?= $row['masalah'] ?>
                                        </p>
                                        <p><b class="d-block">Lokasi</b>
                                            <?= $row['alamat'] ?>
                                        </p>
                                        <p><b class="d-block">Program</b>
                                            <?= $row['nama_program'] ?? '-' ?>
                                        </p>
                                        <p><b class="d-block">Kegiatan</b>
                                            <?= $row['nama_kegiatan'] ?? '-' ?>
                                        </p>
                                        <p><b class="d-block">Sub Kegiatan</b>
                                            <?= $row['nama_sub_kegiatan'] ?? '-' ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <div class="col-md-4">
                  <?php 
                    if($is_opd){
                  ?>
                    <div class="card">
                        <form action="<?= site_url('usulan/update-status') ?>" method="post" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id_usulan_musren" value="<?= $row['id'] ?>">
                            <input type="hidden" name="id_riwayat" value="<?= !empty($riwayat) ? $riwayat['id'] : ''?>">
                            <div class="card-body">
                                <div class="form-group">
                                    <?php
                                        $status = !empty($riwayat) ? $riwayat['status'] : '';
                                    ?>
                                    <label>Status Usulan <sup class="text-danger">*</sup></label>
                                    <?php
                                      if($row['verifikasi']==0){
                                    ?>
                                    <select class="form-control" name="status" id="status" required>
                                        <option value="" selected disabled>Pilih Status Usulan</option>
                                        <option value="1" <?= $status=='1' ? 'selected' : '' ?>>Sudah</option>
                                        <option value="0" <?= $status=='0' ? 'selected' : '' ?>>Belum</option>
                                    </select>
                                    <?php
                                      }else{
                                    ?>
                                    <input type="text" class="form-control" value="<?= $status=='1' ? 'Sudah' : 'Belum' ?>" disabled>
                                    <?php
                                      }
                                    ?>
                                </div>

                                <!-- Wrap Upload: disembunyikan dulu -->
                                <div id="wrap-foto" class="form-group" style="display:<?= $status=='1' ? '' : 'none'?>;">
                                    <label>Foto Pendukung <sup class="text-danger">(Maksimal 3 Foto)</sup></label>

                                    <!-- daftar input-file dinamis -->
                                    <div id="file-list"></div>

                                    <!-- tombol tambah input -->
                                    <?php
                                      if($row['verifikasi']==0){
                                    ?>
                                    <button type="button" id="btn-add-foto" class="btn btn-success btn-sm mt-2">
                                        <i class="fa fa-plus"></i> Tambah Foto
                                    </button>
                                    <?php
                                      }
                                    ?>

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
                                            
                                            <?php
                                              if($row['verifikasi']==0){
                                            ?>
                                            <!-- tombol hapus -->
                                            <button type="button"
                                                    class="btn btn-sm btn-danger position-absolute btn-del-old"
                                                    data-doc-id="<?= esc($f['id']) ?>"
                                                    style="right:4px; top:4px; padding:2px 6px;">
                                              <i class="fa fa-trash"></i>
                                            </button>
                                            <?php
                                              }
                                            ?>
                                          </div>
                                        <?php endforeach; ?>
                                      <?php endif; ?>
                                    </div>
                                    <?php
                                      if($row['verifikasi']==0){
                                    ?>
                                    <small class="text-muted d-block mt-1">Format: JPG/PNG, maksimal 3 foto.</small>
                                    <?php
                                      }
                                    ?>
                                </div>
                            </div>
                            <?php
                              if($row['verifikasi']==0){
                            ?>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                            <?php
                              }
                            ?>
                        </form>
                    </div>
                  <?php
                    }else{
                      if(!empty($riwayat)){
                  ?>
                    <div class="card">
                      <div class="card-body">
                          <div class="form-group">
                              <?php
                                  $status = !empty($riwayat) ? $riwayat['status'] : '';
                              ?>
                              <label>Status Usulan</label>
                              <br><span class="badge <?= $status=='1' ? 'badge-success' : 'badge-danger' ?> text-sm"><?= $status=='1' ? 'Sudah' : 'Belum' ?></span>
                          </div>
                          <hr>
                          <!-- Wrap Upload: disembunyikan dulu -->
                          <div id="wrap-foto" class="form-group" style="display:<?= $status=='1' ? '' : 'none'?>;">
                              <label>Foto Pendukung</label>
                              <?php
                                if(count($foto)==0){
                              ?>
                              <br><span class="text-muted"><i>Tidak ada foto</i></span>
                              <?php
                                }
                              ?>

                              <!-- preview grid -->
                              <div id="preview-grid" class="d-flex flex-wrap gap-2 ">
                                <?php if (!empty($foto)): ?>
                                  <?php foreach ($foto as $f): ?>
                                    <div class="thumb position-relative mr-2 mb-2"
                                        data-id="old-<?= esc($f['id']) ?>" style="width:100px;">
                                      <img src="<?= base_url($f['url_name']) ?>"
                                          class="img-thumbnail w-100"
                                          alt="preview"
                                          data-name="<?= esc($f['originale_name'] ?? $f['file_name']) ?>">
                                    </div>
                                  <?php endforeach; ?>
                                <?php endif; ?>
                              </div>
                          </div>
                      </div>
                    </div>
                  <?php
                      }
                    }
                  ?>

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

<?= $this->section('javascriptkhusus'); ?>
<script>
    const ahrefusulan = document.querySelector('.ahref-usulan');

    ahrefusulan.classList.add('active');
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
    // contoh: reload saat ganti tahun
    $('#years').on('change', function () {
        window.location.href = "<?= site_url('usulan') ?>";
    });
});
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
               name="foto[]" accept="image/*" data-id="${idx}" required>
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
      alert('File harus gambar (JPG/PNG).');
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

  // Validasi saat submit: jika status=1 minimal ada 1 foto
  $('form').on('submit', function(e){
    if ($('#status').val() === '1') {
      // const filled = $('.input-foto').filter(function(){ return this.files && this.files.length; }).length;
      // if (filled === 0) {
      //   e.preventDefault();
      //   alert('Mohon unggah minimal 1 foto (maksimal 3).');
      // }
    }
  });
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
      url: "<?= site_url('usulan/foto-delete') ?>",
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



<?= $this->endSection(); ?>