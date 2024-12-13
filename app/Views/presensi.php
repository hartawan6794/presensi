<?= $this->extend("layout/master") ?>

<?= $this->section("content") ?>

<?php
helper("settings");
?>

<style type="text/css">
  .preloader {
    position: fixed;
    display: none;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: rgba(255, 255, 255, 0.7);
    /* background-color: #fff; */
  }

  .preloader .loading {
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    font: 14px arial;
  }
</style>

<div class="preloader">
  <div class="loading">
    <img src="<?= base_url('/img/spaceman.png') ?>" width="80">
    <p class="mt-2">Harap Tunggu</p>
  </div>
</div>
<!-- Main content -->
<div class="card">
  <div class="card-header">
    <div class="row">
      <div class="col-9 mt-2">
        <h3 class="card-title menu-judul">Presensi Bulan </h3>
      </div>
      <div class="col-3">
        <button type="button" style="display: none;" class="btn float-end btn-success" onclick="save()" id="btn-tambah-presensi" title="<?= lang("Tambah") ?>"> <i class="fa fa-plus"></i> <?= lang('Tambah') ?></button>
      </div>
    </div>
  </div>
  <!-- Tambahkan tombol bulan di sini -->
  <div class="card-body">
    <div class="btn-group">
      <?php
      foreach ($bulan->bulan as $nomor => $b) { ?>
        <div class="card">
          <button type="button" class="btn btn-info" onclick="selectMonth('<?= $nomor ?>', '<?= $b ?>')"><?= $nomor ?><p><?= $b ?></p></button>
        </div>
      <?php } ?>
    </div>

    <div class="col-md-3">
      <!-- <button type="text" id></button> -->
      <button type="button" class="btn btn-warning" style="display: none;" onclick="cetak()" id="btn-cetak-presensi" title="<?= lang("Cetak") ?>"> <i class="fa fa-print"></i> <?= lang('Cetak') ?></button>
    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body">
    <table id="data_table" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>No</th>
          <th>Hari</th>
          <th>Tanggal Presensi</th>
          <th>Keterangan</th>
          <th></th>
        </tr>
      </thead>
    </table>
  </div>
  <!-- /.card-body -->
</div>
<!-- /.card -->

<!-- /Main content -->

<!-- ADD modal content -->
<div id="data-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-md">
    <div class="modal-content">
      <div class="text-center bg-info p-3" id="model-header">
        <h4 class="modal-title text-white" id="info-header-modalLabel"></h4>
      </div>
      <div class="modal-body">
        <form id="data-form" class="pl-3 pr-3">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="bulan" class="col-form-label"> Bulan: </label>
                <select name="bulan" id="bulan" class="form-control">
                  <option value="">--Pilih Bulan--</option>
                  <?php foreach ($bulan->bulan as $nomor => $b) : ?>
                    <option value="<?= $nomor ?>"><?= $b ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group text-center">
            <div class="btn-group">
              <button type="submit" class="btn btn-success mr-2" id="form-btn"><?= lang("Simpan") ?></button>
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?= lang("Batal") ?></button>
            </div>
          </div>
        </form>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
<!-- /ADD modal content -->

<!-- EDIT modal content -->
<div id="data-modal-edit" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-md">
    <div class="modal-content">
      <div class="text-center bg-info p-3" id="model-header">
        <h4 class="modal-title text-white" id="info-header-modalLabel"></h4>
      </div>
      <div class="modal-body">
        <form id="edit-form" class="pl-3 pr-3">
          <div class="row">
            <input type="hidden" id="id_presensi" name="id_presensi" class="form-control" placeholder="Id presensi" maxlength="4" required>
          </div>
          <div class="row">

            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="keterangan" class="col-form-label"> Keterangan: </label>
                <!-- <input type="text" id="keterangan" name="keterangan" class="form-control" placeholder="Keterangan" minlength="0" maxlength="255"> -->
                <textarea name="keterangan" id="keterangan" class="form-control" cols="30" rows="10"></textarea>
              </div>
            </div>
          </div>

          <div class="form-group text-center">
            <div class="btn-group">
              <button type="submit" class="btn btn-success mr-2" id="form-btn-edit"><?= lang("Simpan") ?></button>
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?= lang("Batal") ?></button>
            </div>
          </div>
        </form>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
<!-- /EDIT modal content -->



<?= $this->endSection() ?>
<!-- /.content -->


<!-- page script -->
<?= $this->section("pageScript") ?>
<script>
  // dataTables
  var bulanValue = '<?= $bulan_ini ?>';
  var bulan = bulanValue.split(',');

  $('.card-title.menu-judul').text('Presensi Bulan ' + bulan[1])
  // bulanValue.split(',')
  $(function() {
    var table = $('#data_table').removeAttr('width').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": false,
      "processing": true,
      // "serverSide": true,
      "autoWidth": false,
      "scrollY": '45vh',
      "scrollX": true,
      "scrollCollapse": false,
      "responsive": false,
      "ajax": {
        "url": '<?php echo base_url($controller . "/getSelectedMonth") ?>',
        "type": "POST",
        "dataType": "json",
        "data": {
          bulan: bulan[0]
        },
        async: "true"
      },
      "columnDefs": [{
        "targets": -1, // Aksi pada kolom terakhir
        "data": null,
        "defaultContent": "<button class='btn btn-sm btn-outline-primary rounded-pill edit-btn'><i class='fa-solid fa-pen-to-square me-1'></i>Edit</button>"
      }],
      "initComplete": function(settings, json) {
        // Check if the DataTable has data
        if (table.data().count() === 0) {
          $('#btn-tambah-presensi').show();
          $('#btn-cetak-presensi').hide();
          // You can perform actions for an empty table here
        } else {
          $('#btn-tambah-presensi').hide();
          $('#btn-cetak-presensi').show();
          // You can perform actions when the table has data
        }
      }
    });
    // Tambahkan event handler untuk tombol edit
    $('#data_table tbody').on('click', 'button.edit-btn', function() {
      var data = table.row($(this).parents('tr')).data();
      editData(data); // Fungsi untuk meng-handle pengeditan
    });
  });

  var urlController = '';
  var submitText = '';
  var month = '<?= date('m') ?>';

  function getUrl() {
    return urlController;
  }

  function getSubmitText() {
    return submitText;
  }

  function getMonth() {
    return month;
  }

  function editData(data) {
    // Ambil data dari baris yang dipilih dan lakukan proses pengeditan
    // Tampilkan formulir kustom atau gunakan modul lain sesuai kebutuhan
    // Misalnya, Anda dapat menggunakan modal Bootstrap untuk formulir pengeditan
    // Tampilkan formulir pengeditan atau ambil tindakan lain sesuai kebutuhan

    urlController = '<?= base_url($controller . "/edit") ?>';
    submitText = '<?= lang("Perbarui") ?>';

    $('#data-modal-edit #model-header').removeClass('bg-success').addClass('bg-info');
    $("#data-modal-edit #info-header-modalLabel").text('<?= lang("Ubah") ?>');
    $("#form-btn-edit").text(submitText);
    // $('#data-modal').modal('show');
    $('#data-modal-edit').modal('show');
    //insert data to form
    $("#data-modal-edit #edit-form #id_presensi").val(data[4]);
    // $("#data-modal-edit #data-form #id_user").val(response.id_user);
    // $("#data-form #tgl_presensi").val(response.tgl_presensi);
    $("#data-modal-edit #edit-form #keterangan").val(data[3]);

    //insert data to form
    // $("#notif-form #id_pembayaran").val(id_pembayaran);
    $("#data-modal-edit").validate({
      submitHandler: function(form) {

        var form = $('#edit-form');
        $(".text-danger").remove();
        $.ajax({
          url: getUrl(),
          type: 'post',
          data: form.serialize(),
          cache: false,
          dataType: 'json',
          beforeSend: function() {
            $('#form-btn-edit').html('<i class="fa fa-spinner fa-spin"></i>');
          },
          success: function(response) {
            if (response.success === true) {
              Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: response.messages,
                showConfirmButton: false,
                timer: 1500
              }).then(function() {
                selectMonth(getMonth())
                $('#data-modal-edit').modal('hide');
              })
            } else {
              if (response.messages instanceof Object) {
                $.each(response.messages, function(index, value) {
                  var ele = $("#" + index);
                  ele.closest('.form-control')
                    .removeClass('is-invalid')
                    .removeClass('is-valid')
                    .addClass(value.length > 0 ? 'is-invalid' : 'is-valid');
                  ele.after('<div class="invalid-feedback">' + response.messages[index] + '</div>');
                });
              } else {
                Swal.fire({
                  toast: false,
                  position: 'bottom-end',
                  icon: 'error',
                  title: response.messages,
                  showConfirmButton: false,
                  timer: 3000
                })

              }
            }
            // $('#form-btn').html(getSubmitText());
          }
        });
        // Lakukan validasi dan pengiriman formulir ke server di sini
        // Contoh: Anda bisa menggunakan Ajax untuk mengirim data ke server
      }
    });
  }

  function selectMonth(nomor, b) {
    // Reload DataTable sebelum memanggil AJAX
    month = nomor;
    $('.card-title.menu-judul').text('Presensi Bulan ' + b)
    $.ajax({
      url: '<?php echo base_url($controller . "/getSelectedMonth") ?>',
      type: 'post',
      data: {
        bulan: nomor
      },
      dataType: 'json',
      beforeSend: function() {
        $(".preloader").fadeIn();
      },
      success: function(response) {
        $(".preloader").fadeOut();
        $('#data_table').DataTable().clear();
        if (response.data.length > 0) {
          $('#data_table').DataTable().rows.add(response.data).draw(false);
          $('#btn-tambah-presensi').hide();
          $('#btn-cetak-presensi').show();
        } else {
          $('#btn-tambah-presensi').show();
          $('#btn-cetak-presensi').hide();
          $('#data_table').DataTable().clear().draw();
        }
      }
    });
    // });
  }

  function save() {
    // reset the form 

    Swal.fire({
      title: "<?= lang("Tambah") ?>",
      text: "<?= lang("Tambah presensi bulan ini ?") ?>",
      icon: 'info',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: '<?= lang("Konfirmasi") ?>',
      cancelButtonText: '<?= lang("Batal") ?>'
    }).then((result) => {

      if (result.value) {
        $.ajax({
          url: '<?php echo base_url($controller . "/add") ?>',
          type: 'post',
          data: {
            bulan: month
          },
          dataType: 'json',
          beforeSend: function() {
            $(".preloader").fadeIn();
          },
          success: function(response) {
            $(".preloader").fadeOut();
            if (response.success === true) {
              Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: response.messages,
                showConfirmButton: false,
                timer: 1500
              }).then(function() {
                // $('#data_table').DataTable().ajax.reload(null, false).draw(false);
                selectMonth(month)
              })
            } else {
              Swal.fire({
                toast: false,
                position: 'bottom-end',
                icon: 'error',
                title: response.messages,
                showConfirmButton: false,
                timer: 3000
              })
            }
          }
        });
      }
    })

  }

  function remove(id_presensi) {
    Swal.fire({
      title: "<?= lang("Hapus") ?>",
      text: "<?= lang("Yakin ingin menghapus ?") ?>",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: '<?= lang("Konfirmasi") ?>',
      cancelButtonText: '<?= lang("Batal") ?>'
    }).then((result) => {

      if (result.value) {
        $.ajax({
          url: '<?php echo base_url($controller . "/remove") ?>',
          type: 'post',
          data: {
            id_presensi: id_presensi
          },
          dataType: 'json',
          success: function(response) {

            if (response.success === true) {
              Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: response.messages,
                showConfirmButton: false,
                timer: 1500
              }).then(function() {
                $('#data_table').DataTable().ajax.reload(null, false).draw(false);
              })
            } else {
              Swal.fire({
                toast: false,
                position: 'bottom-end',
                icon: 'error',
                title: response.messages,
                showConfirmButton: false,
                timer: 3000
              })
            }
          }
        });
      }
    })
  }

  function cetak() {
    var bulan = getMonth()
    $.ajax({
      url: '<?php echo base_url("laporan/cetak") ?>',
      type: 'post',
      data: {
        bulan: bulan
      },
      dataType: 'json',
      cache: false,
      success: function(response) {
        if (response.success === true) {
            // Create a link element
            var link = document.createElement('a');
            link.href = response.filePath; // The URL of the PDF file
            link.target = '_blank'; // Open in a new tab
            link.click();
          } else {
            alert(response.message);
          }
        },
        statusCode: {
          500: function() {
            // Handle error 500 if needed
            alert('Terjadi kesalahan internal server. Silakan coba lagi nanti.');
          }
        }
    });
  }
</script>


<?= $this->endSection() ?>