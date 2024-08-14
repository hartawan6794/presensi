<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">

	<!-- <link rel="stylesheet" href="css/icomoon/style.css"> -->
	<link rel="stylesheet" href="<?= base_url('asset/css/icomoon/style.css') ?>">

	<!-- <link rel="stylesheet" href="css/owl.carousel.min.css"> -->
	<link rel="stylesheet" href="<?= base_url('asset/css/owl.carousel.min.css') ?>">


	<!-- Theme style -->
	<link rel="stylesheet" href="<?= base_url('asset/css/login.min.css') ?>">

	<!-- Style -->
	<!-- <link rel="stylesheet" href="css/style.css"> -->
	<link rel="stylesheet" href="<?= base_url('asset/css/style.css') ?>">
	<!-- <link rel="stylesheet" href="css/style.css"> -->

	<title>Signup Aplikasi Presensi</title>

	<style>
		#jabatan {
			background-color: #edf2f5;
		}
	</style>
</head>
<div class="content">
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<img src="<?= base_url('asset/img/undraw_welcome_re_h3d9.svg') ?>" alt="Image" class="img-fluid">
			</div>
			<div class="col-md-6 contents">
				<div class="row justify-content-center">
					<div class="col-md-8">
						<div class="mb-4">
							<h3>Registered Form</h3>
							<p class="mb-4">Masukan data anda ke dalam form di bawah ini</p>
						</div>
						<form id="signup-form">
							<div class="form-group first mb-2">
								<label for="pengguna">Nama Lengkap <span class="text-danger">*</span></label>
								<input type="text"
									class="form-control"
									id="pengguna" name="pengguna">
							</div>
							<div class="form-group first mb-2">
								<label for="username">Nama Pengguna <span class="text-danger">*</span></label>
								<input type="text" id="username" name="username"
									class="form-control">
							</div>
							<div class="form-group first mb-2">
								<label for="pass">Kata Sandi <span class="text-danger">*</span></label>
								<input type="password" id="pass" name="pass"
									class="form-control">
							</div>
							<div class="form-group first mb-2">
								<label for="jabatan">Jabatan <span class="text-danger">*</span></label>
								<select name="jabatan" class="form-control" id="jabatan">
									<option value="">--Pilih Jabatan--</option>
									<?php foreach ($dataJab as $data) :
										if ($data->jabatan != 'Admin') : ?>
											<option value="<?= $data->id_jabatan ?>"><?= $data->jabatan ?></option>
										<?php endif; ?>
									<?php endforeach; ?>

								</select>
							</div>


							<button type="button" id="btn-signup" class="btn btn-block btn-primary">Daftar</button>
							<!-- 
                                <span class="d-block text-left my-4 text-muted">&mdash; or login with &mdash;</span>

                                <div class="social-login">
                                    <a href="#" class="facebook">
                                        <span class="icon-facebook mr-3"></span>
                                    </a>
                                    <a href="#" class="twitter">
                                        <span class="icon-twitter mr-3"></span>
                                    </a>
                                    <a href="#" class="google">
                                        <span class="icon-google mr-3"></span>
                                    </a>
                                </div> -->
						</form>
					</div>
				</div>

			</div>

		</div>
	</div>
</div>


<!-- jQuery -->
<script src="<?= base_url('asset/js/jquery-3.6.0.min.js') ?>"></script>
<script src="<?= base_url('asset/js/popper.min.js') ?>"></script>

<!-- <script src="js/popper.min.js"></script> -->
<!-- AdminLTE App -->
<script src=" <?= base_url('asset/js/adminlte.min.js') ?>"></script>
<script src=" <?= base_url('asset/js/main.js') ?>"></script>
<!-- <script src="js/main.js"></script> -->
<!-- SweetAlert2 -->
<script src="<?= base_url('asset/js/sweetalert2.all.min.js') ?>"></script>

<script>
	$('#btn-signup').on('click', function(e) {
		e.preventDefault()

		// Serialize form data
		var formData = $('#signup-form').serialize();

		console.log(formData)
		$.ajax({
			// fixBug get url from global function only
			// get global variable is bug!
			url: '<?= base_url($controller . "/registered") ?>',
			type: 'post',
			data: formData,
			dataType: 'json',
			beforeSend: function() {
				$('#btn-signup').html('<i class="fa fa-spinner fa-spin"></i>');
			},
			success: function(response) {
				// console.log(response);
				if (response.success === true) {
					Swal.fire({
						icon: 'success',
						title: 'Selamat',
						text: 'Berhasil daftar sebagai '
					}).then((result) => {
						if (result.value) {
							window.location = "<?= base_url('/login') ?>"
						}
					})
				} else {
					if (response.messages instanceof Object) {
						$.each(response.messages, function(index, value) {
							var ele = $("#" + index);
							// console.log(response.messages[index])
							Swal.fire({
								toast: false,
								position: 'bottom-end',
								icon: 'error',
								title: response.messages[index],
								showConfirmButton: false,
								timer: 3000
							}).then((result) => {
								$('#btn-signup').html('Daftar');
							})
						});
					} else {
						Swal.fire({
							toast: false,
							position: 'bottom-end',
							icon: 'error',
							title: response.messages,
							showConfirmButton: false,
							timer: 3000
						}).then((result) => {
							$('#btn-signup').html('Daftar');
						})
					}
				}

			}
		})


		// registered(pengguna, username, pass, selectedValue);
	})

	function registered(pengguna, username, pass, selectedValue) {
		$.ajax({
			// fixBug get url from global function only
			// get global variable is bug!
			url: '<?= base_url($controller . "/registered") ?>',
			type: 'post',
			data: {
				pengguna: pengguna,
				username: username,
				password: pass,
				valueJab: selectedValue
			},
			cache: false,
			dataType: 'json',
			beforeSend: function() {
				$('#btn-signup').html('<i class="fa fa-spinner fa-spin"></i>');
			},
			success: function(response) {
				// console.log(response);
				if (response.success === true) {
					Swal.fire({
						icon: 'success',
						title: 'Selamat',
						text: 'Berhasil daftar sebagai '
					}).then((result) => {
						if (result.value) {
							window.location = "<?= base_url('/login') ?>"
						}
					})
				} else {
					if (response.messages instanceof Object) {
						$.each(response.messages, function(index, value) {
							var ele = $("#" + index);
							// console.log(response.messages[index])
							Swal.fire({
								toast: false,
								position: 'bottom-end',
								icon: 'error',
								title: response.messages[index],
								showConfirmButton: false,
								timer: 3000
							}).then((result) => {
								$('#btn-signup').html('Daftar');
							})
						});
					} else {
						Swal.fire({
							toast: false,
							position: 'bottom-end',
							icon: 'error',
							title: response.messages,
							showConfirmButton: false,
							timer: 3000
						}).then((result) => {
							$('#btn-signup').html('Daftar');
						})
					}
				}

			}
		})

	}
</script>
</body>

</html>