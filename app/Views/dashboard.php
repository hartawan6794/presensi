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
      <div class="row">
        <div class="chart-responsive" style="height:50vh;">
          <canvas id="pieChart"  height="40vw"></canvas>
        </div>
      </div>
      <!-- <canvas id="doughnutChart" width="400" height="400"></canvas> -->
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
<script>
  $(function() {
    // Data untuk chart doughnut

    var data = {
      labels: ['Sudah input', 'Belum input', 'Libur'],
      datasets: [{
        label: 'My First Dataset',
        data: [300, 50, 100],
        backgroundColor: [
          'rgb(255, 99, 132)',
          'rgb(54, 162, 235)',
          'rgb(255, 205, 86)'
        ],
        hoverOffset: 4
      }]
    };


    // Konfigurasi chart
    var config = {
      type: 'doughnut',
      data: data,
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'left',
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
    };

    // Inisialisasi chart
    var myChart = new Chart(
      document.getElementById('pieChart'),
      config
    );


    // Mengatur ulang ukuran canvas
    $('#pieChart').attr('width', '300');
    $('#pieChart').attr('height', '300');
  })
</script>
<?= $this->endSection() ?>