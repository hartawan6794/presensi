      <!-- Main Sidebar Container -->
      <!--<aside class="main-sidebar sidebar-bg-dark sidebar-color-primary shadow">-->
      <?php helper('settings');
      $seg = segment()->uri->getSegment(1) ?>
      <aside class="main-sidebar sidebar-bg-dark  sidebar-color-primary shadow">
        <div class="brand-container">
          <a href="javascript:;" class="brand-link">
            <img src="<?= base_url('/asset/img/user.jpg') ?>" alt="GIS KESEHATAN" class="brand-image opacity-80 shadow">
            <span class="brand-text fw-light">Presensi</span>
          </a>
          <a class="pushmenu mx-1" data-lte-toggle="sidebar-mini" href="javascript:;" role="button"><i class="fas fa-angle-double-left"></i></a>
        </div>
        <!-- Sidebar -->
        <div class="sidebar">
          <nav class="mt-2">
            <!-- Sidebar Menu -->
            <ul class="nav nav-pills nav-sidebar flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
              <li class="nav-item">
                <a href="<?= base_url('home') ?>" class="nav-link <?= $seg == 'home' || $seg == '' ? 'active' : '' ?> ">
                  <i class="nav-icon fa fa-desktop"></i>
                  <p>
                    Dashboard
                  </p>
                </a>
              </li>
              <?php if (session()->get('role') == 'admin') : ?>
                <li class="nav-item">
                  <a href="<?= base_url('user') ?>" class="nav-link <?= $seg == 'user' ? 'active' : '' ?>">
                    <i class="nav-icon far fa-user"></i>
                    <p>Pengguna</p>
                  </a>
                </li>
              <?php endif ?>
              <?php if (session()->get('role') != 'admin') : ?>
                <li class="nav-item">
                  <a href="<?= base_url('presensi') ?>" class="nav-link <?= $seg == 'presensi' ? 'active' : '' ?>">
                    <i class="nav-icon fa fa-bars"></i>
                    <p>Presensi Tahunan</p>
                  </a>
                </li>
              <?php endif ?>
              <?php if (session()->get('role') == 'admin') : ?>
                <li class="nav-item">
                  <a href="<?= base_url('jabatan') ?>" class="nav-link <?= $seg == 'jabatan' ? 'active' : '' ?>">
                    <i class="nav-icon fa-solid fa-business-time"></i>
                    <p>Jabatan</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= base_url('laporan') ?>" class="nav-link <?= $seg == 'laporan' ? 'active' : '' ?>">
                    <i class="nav-icon fa fa-print"></i>
                    <p>Laporan Presensi</p>
                  </a>
                </li>
              <?php endif ?>

          </nav>
        </div>
        <!-- /.sidebar -->
      </aside>