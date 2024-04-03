<?= $this->extend("layout/master") ?>

<?= $this->section("content") ?>

<div class="row">
  <h1 class="mb-3">Selamat Datang, <?= session()->get('nama_lengkap') ?></h1>
</div>

<div class="row">
  <div class="card col-md-8 m-0">
    <div class="card-header">
      <!-- <h1>test</h1> -->
      <h1>Proses Preperensi Bulan <?= $bulan ?> <img src="<?= base_url('/img/spaceman.png') ?>" alt="space_man" style="width: 60px;"></h1>

    </div>
    <div class="card-body">
      <div class="row">
        <div class="chart-responsive" style="height:60vh;">
          <?php if (session()->get('role') != 'admin') : ?>
          <canvas id="pieChart" height="40vw"></canvas>
        <?php endif ?>
        </div>
      </div>
      <!-- <canvas id="doughnutChart" width="400" height="400"></canvas> -->
    </div>
  </div>
  <div class="card col-md-4 m-0">
    <div class="card-header">
      <?php if (session()->get('role') == 'admin') : ?>
        <button type="button" class="btn float-end btn-success" onclick="save()" id="btn-tambah-presensi" title="<?= lang("Tambah") ?>"> <i class="fa fa-plus"></i> <?= lang('Tambah') ?></button>
      <?php endif ?>
      <h1>Agenda Bulan <?= $bulan ?> </h1>
    </div>
    <div class="card-body">
      <table id="data_table" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Tanggal</th>
            <th>Agenda</th>

            <th></th>
          </tr>
        </thead>
      </table>
    </div>
  </div>

</div>


<!-- Main content -->

<!-- /.card -->

<!-- ADD modal content -->
<div id="data-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="text-center bg-info p-3" id="model-header">
        <h4 class="modal-title text-white" id="info-header-modalLabel"></h4>
      </div>
      <div class="modal-body">
        <form id="data-form" class="pl-3 pr-3">
          <div class="row">
            <input type="hidden" id="id_agenda" name="id_agenda" class="form-control" placeholder="Id user" maxlength="4" required>
          </div>
          <div class="row">
            <!-- Input pertama: kalender untuk memilih tanggal pada bulan saat ini -->
            <div class="col-md-12">
              <div class="form-group">
                <label for="datepicker">Pilih Tanggal</label>
                <input type="text" id="datepicker" name="tanggal" class="form-control" placeholder="Pilih Tanggal" autocomplete="off" required readonly>
              </div>
            </div>
            <!-- Input kedua: text area -->
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="ket_agenda">Keterangan Agenda</label>
                <textarea id="ket_agenda" name="ket_agenda" class="form-control" rows="3" placeholder="Masukkan teks di sini" required></textarea>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="form-group text-center">
              <div class="btn-group">
                <button type="submit" class="btn btn-success mr-2" id="form-btn"><?= lang("Simpan") ?></button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?= lang("Batal") ?></button>
              </div>
            </div>
          </div>

        </form>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
<!-- /ADD modal content -->

<?= $this->endSection() ?>
<!-- /.content -->

<!-- page script -->
<?= $this->section("pageScript") ?>
<script>
  $(function() {
    // Data untuk chart doughnut

    var table = $('#data_table').removeAttr('width').DataTable({
      "paging": false,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "scrollY": '45vh',
      "scrollX": true,
      "scrollCollapse": false,
      "responsive": false,
      "ajax": {
        "url": '<?php echo base_url($controller . "/getAgenda") ?>',
        "type": "POST",
        "dataType": "json",
        async: "true"
      }
    });
    fetchData()

    // Menginisialisasi datepicker
    $('#datepicker').datepicker({
      format: 'yyyy-mm-dd',
      startDate: '<?= date("Y-m-01") ?>', // Mulai dari awal bulan saat ini
      endDate: '<?= date("Y-m-t") ?>', // Sampai akhir bulan saat ini
      autoclose: true
    });

  })

  var urlController = '';
  var submitText = '';

  function getUrl() {
    return urlController;
  }

  function getSubmitText() {
    return submitText;
  }

  function save(id_user) {
    // reset the form 
    $("#data-form")[0].reset();
    $(".form-control").removeClass('is-invalid').removeClass('is-valid');
    if (typeof id_user === 'undefined' || id_user < 1) { //add
      urlController = '<?= base_url($controller . "/addAgenda") ?>';
      submitText = '<?= lang("Simpan") ?>';
      $('#model-header').removeClass('bg-info').addClass('bg-success');
      $("#info-header-modalLabel").text('<?= lang("Tambah") ?>');
      $("#form-btn").text(submitText);
      $('#data-modal').modal('show');
    } else { //edit
      urlController = '<?= base_url($controller . "/edit") ?>';
      submitText = '<?= lang("Perbarui") ?>';
      $.ajax({
        url: '<?php echo base_url($controller . "/getOne") ?>',
        type: 'post',
        data: {
          id_user: id_user
        },
        dataType: 'json',
        success: function(response) {
          $('#model-header').removeClass('bg-success').addClass('bg-info');
          $("#info-header-modalLabel").text('<?= lang("Ubah") ?>');
          $("#form-btn").text(submitText);
          $('#data-modal').modal('show');
          //insert data to form
          $("#data-form #id_user").val(response.id_user);
        }
      });
    }
    $.validator.setDefaults({
      highlight: function(element) {
        $(element).addClass('is-invalid').removeClass('is-valid');
      },
      unhighlight: function(element) {
        $(element).removeClass('is-invalid').addClass('is-valid');
      },
      errorElement: 'div ',
      errorClass: 'invalid-feedback',
      errorPlacement: function(error, element) {
        if (element.parent('.input-group').length) {
          error.insertAfter(element.parent());
        } else if ($(element).is('.select')) {
          element.next().after(error);
        } else if (element.hasClass('select2')) {
          //error.insertAfter(element);
          error.insertAfter(element.next());
        } else if (element.hasClass('selectpicker')) {
          error.insertAfter(element.next());
        } else {
          error.insertAfter(element);
        }
      },
      submitHandler: function(form) {
        // var form = $('#data-form');
        $(".text-danger").remove();
        $.ajax({
          // fixBug get url from global function only
          // get global variable is bug!
          url: getUrl(),
          type: 'post',
          data: new FormData(form),
          processData: false,
          contentType: false,
          cache: false,
          dataType: 'json',
          beforeSend: function() {
            $('#form-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
                $('#data_table').DataTable().ajax.reload(null, false).draw(false);
                $('#data-modal').modal('hide');
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
            $('#form-btn').html(getSubmitText());
          }
        });
        return false;
      }
    });

    $('#data-form').validate({

      //insert data-form to database

    });
  }

  function remove(id_user) {
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
            id_user: id_user
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



  function fetchData() {

    $.ajax({
      url: '<?= base_url("home/data") ?>', // Ganti dengan URL yang sesuai
      method: 'GET',
      success: function(data) {
        // console.log(data.belum)
        // console.log(data.terisi)
        // console.log(data.cuti)
        createChart(data)
      }
    });
  }
  // Fungsi untuk membuat grafik menggunakan Chart.js
  function createChart(data) {

    var ctx = document.getElementById('pieChart');
    var myChart = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ['Belum input', 'Sudah input', 'Libur'],
        datasets: [{
          // label: ['Belum input', 'Sudah input', 'Libur'],
          data: [data.belum, data.terisi, data.cuti],
          backgroundColor: [
            'rgb(255, 99, 132)',
            'rgb(54, 162, 235)',
            'rgb(255, 205, 86)'
          ],
          hoverOffset: 4
        }],
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'right',
            align: 'center'
          },
          customCanvasBackgroundImage: { // plugin yang Anda definisikan sebelumnya
            beforeDraw: (chart) => {
              const image = new Image();
              image.src = '<?= base_url('/img/spaceman.png') ?>';
              $(image).on('load', function() {
                const ctx = chart.ctx;
                const {
                  top,
                  left,
                  width,
                  height
                } = chart.chartArea;
                const x = left + width / 2 - image.width / 2;
                const y = top + height / 2 - image.height / 2;
                ctx.drawImage(this, x, y);
                chart.update();
              });
            }
          }
        }
      },
    });
  }
</script>
<?= $this->endSection() ?>