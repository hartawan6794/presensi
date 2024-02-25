<?= $this->extend("layout/master") ?>

<?= $this->section("content") ?>

<div class="row">
    <h1 class="mb-3">Selamat Datang, <?= session()->get('nama_lengkap') ?></h1>
    <div class="col-lg-3 col-6">
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
    </div>

    <div class="col-lg-3 col-6">

        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= '$rsia' ?></h3>
                <p>Rumah Sakit Ibu dan Anak</p>
            </div>
            <div class="icon">
                <i class="nav-icon fa fa-hospital"></i>
            </div>
            <a href="<?= base_url('rsia') ?>" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">

        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?= '$klinik' ?></h3>
                <p>Klinik</p>
            </div>
            <div class="icon">
                <i class="nav-icon fa fa-user-md"></i>
            </div>
            <a href="<?= base_url('klinik') ?>" class="small-box-footer">Selangkapnya <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">

        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?= '$puskesmas' ?></h3>
                <p>Puskesmas</p>
            </div>
            <div class="icon">
                <i class="nav-icon fa fa-ambulance"></i>
            </div>
            <a href="<?= base_url('puskesmas') ?>" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
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