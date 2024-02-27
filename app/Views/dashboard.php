<?= $this->extend("layout/master") ?>

<?= $this->section("content") ?>

<div class="row">
    <h1 class="mb-3">Selamat Datang, <?= session()->get('nama_lengkap') ?></h1>
    <!-- <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= '$rumahsakit' ?></h3>
                <p>Rumah Sakit</p>
            </div>
            <div class="icon">
                <i class="nav-icon fa fa-stethoscope"></i>
            </div>
            <a href="<?= base_url('rumahsakit') ?>" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div> -->
</div>


<!-- Main content -->

<!-- /.card -->

<?= $this->endSection() ?>
<!-- /.content -->

<!-- page script -->
<?= $this->section("pageScript") ?>

<?= $this->endSection() ?>