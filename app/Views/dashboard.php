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
          <canvas id="pieChart" height="40vw"></canvas>
        </div>
      </div>
      <!-- <canvas id="doughnutChart" width="400" height="400"></canvas> -->
    </div>
  </div>
  <div class="card col-md-4 m-0">
    <div class="card-header">
      <h1>Agenda Bulan <?= $bulan ?> </h1>
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

    fetchData()

  })

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