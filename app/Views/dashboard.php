<?= $this->extend("layout/master") ?>

<?= $this->section("content") ?>

<div class="row">
  <h1 class="mb-3">Selamat Datang, <?= session()->get('nama_lengkap') ?></h1>
</div>

<div class="row">
  <div class="card col-md-8 m-0">
    <div class="card-header">
      <!-- <h1>test</h1> -->
      <h1>Proses Preperensi<img src="<?= base_url('/img/spaceman.png') ?>" alt="space_man" style="width: 60px;"></h1>

    </div>
    <div class="card-body">
      <table id="data_table" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Bulan</th>
            <th>Progress</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
  <div class="card col-md-4 m-0">
    <div class="card-header">
    </div>
  </div>

</div>


<!-- Main content -->

<!-- /.card -->

<?= $this->endSection() ?>
<!-- /.content -->

<!-- page script -->
<?= $this->section("pageScript") ?>

<?= $this->endSection() ?>