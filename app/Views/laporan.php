<?= $this->extend("layout/master") ?>

<?= $this->section("content") ?>

<!-- /.content -->
<!-- Main content -->
<div class="card">
  <div class="card-header">
    <div class="row">
      <div class="col-12 mt-2">
        <h2 class="card-title">Filter Laporan Presensi</h2>
      </div>
      <!-- <div class="col-3">
        <button type="button" class="btn float-end btn-success" onclick="save()" title="<?= lang("Tambah") ?>"> <i class="fa fa-plus"></i> <?= lang('Tambah') ?></button>
      </div> -->
    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body">
    <div class="row">
      <form id="data-form-search" class="pl-3 pr-3">
        <!-- <div class="row">
            <input type="hidden" id="id_presensi" name="id_presensi" class="form-control" placeholder="Id presensi" maxlength="4" required>
          </div> -->
        <div class="row">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="bulan" class="col-form-label"> Pilih Pegawai: </label>
                  <select name="user" id="user" class="form-control">
                    <option value="">--Pilih Pegawai--</option>
                    <?php foreach ($users as $user) : ?>
                      <option value="<?= $user->id_user ?>"><?= $user->nama_lengkap ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="bulan" class="col-form-label"> Pilih Bulan: </label>
                  <select name="bulan" id="bulan" class="form-control">
                    <option value="">--Pilih Bulan--</option>
                    <?php foreach ($months->bulan as $nomor => $b) : ?>
                      <option value="<?= $nomor ?>"><?= $b ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            </div>

          </div>
        </div>

        <div class="form-group text-left">
          <div class="btn-group">
            <button type="button" class="btn btn-success mr-2" id="form-btn"><i class="fa fa-search"></i><?= lang(" Cari") ?></button>
            <!-- <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?= lang("Batal") ?></button> -->
          </div>
        </div>
      </form>
    </div>
  </div>
  <!-- /.card-body -->
</div>
<!-- /.card -->

<div class="card">
  <div class="card-header">
    <div class="row">
      <div class="col-12">
        <button type="button" class="btn float-end btn-warning " title="<?= lang("Print Pdf") ?>"> <i class="fa fa-print"></i> <?= lang('Print Pdf') ?></button>
        <!-- <button type="button" class="btn float-end btn-success mx-1" title="<?= lang("Tambah") ?>"> <i class="fa fa-plus"></i> <?= lang('Tambah') ?></button> -->
      </div>
    </div>
  </div>
  <div class="card-body">
    <div class="row">
      <table id="data_table" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>No</th>
            <th>Pengguna</th>
            <th>Tanggal Presensi</th>
            <th>Keterangan</th>
            <!-- <th></th> -->
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<!-- page script -->
<?= $this->section("pageScript") ?>

<script>
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
      "scrollY": false,
      "scrollX": true,
      "scrollCollapse": false,
      "responsive": false
    });

    $('#form-btn').on('click', function(e) {
      var id_user = $('#user').val();
      var bulan = $('#bulan').val();

      if (id_user === undefined || id_user <= 0 || bulan === undefined || bulan <= 0)
        alert('Harap memilih data pegawai dan bulan terlebih dahulu')
      else {
        // Mengambil data form
        var formData = $('#data-form-search').serialize();
        // console.log(data)
        urlController = '<?= base_url($controller . "/show") ?>';

        $.ajax({
          url: getUrl(),
          type: 'post',
          data: formData,
          cache: false,
          dataType: 'json',
          success: function(response) {
            // console.log(response.data);
            // if (response.status) {

            $('#data_table').DataTable().clear();
            if (response.data.length > 0) {
              $('#data_table').DataTable().rows.add(response.data).draw(false);
            } else {
              alert('Data belum di tambahkan')
            }
            // }else{
            //   alert('Data belum di tambahkan')
            // }
          }
        });
      }



    })

  })

  function getUrl() {
    return urlController;
  }
</script>


<?= $this->endSection() ?>