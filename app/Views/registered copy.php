<!DOCTYPE html>
<!-- Created By CodingNepal -->
<html lang="en" dir="ltr">

<head>
	<meta charset="utf-8">
	<title>Signup Form</title>
	<link rel="stylesheet" href="<?= base_url('asset/css/css.css') ?>">
	<link rel="stylesheet" href="<?= base_url('asset/plugins/fontawesome-free/css/all.min.css') ?>" />

	<link rel="stylesheet" href="<?= base_url('asset/css/adminlte.min.css') ?>">
</head>

<body>
	<div class="center">
		<div class="container col-md-3">
			<!-- <label for="show" class="close-btn fas fa-times" title="close"></label> -->
			<div class="text">
				Signup Form
			</div>
			<form id="signup-form">
				<div class="data">
					<label for="pengguna">Nama Lengkap <span class="text-danger">*</span></label>
					<input type="text" id="pengguna" name="pengguna" placeholder="Masukan nama lengkap">
				</div>
				<div class="data">
					<label for="username">Nama Pengguna <span class="text-danger">*</span></label>
					<input type="text" id="username" name="username" placeholder="Masukan nama pengguna">
				</div>
				<div class="data">
					<label for="pass">Kata Sandi <span class="text-danger">*</span></label>
					<input type="password" id="pass" name="pass" placeholder="Masukan kata sandi">
				</div>
				<div class="data form-group">
					<label for="jabatan" class="col-form-label">Jabatan <span class="text-danger">*</span></label>
					<select name="jabatan" class="form-control" name="jabatan" id="jabatan">
						<option value="">--Pilih Jabatan--</option>
						<?php foreach ($dataJab as $data) :
							if ($data->jabatan != 'Admin') : ?>
								<option value="<?= $data->id_jabatan ?>"><?= $data->jabatan ?></option>
							<?php endif; ?>

						<?php endforeach; ?>

					</select>
				</div>
				<div class="data">
					<label for="photo">Photo </label>
					<input type="file" id="photo" name="photo" placeholder="Masukan kata sandi">
					<p class="text-small">NB : Form yang bertanda <span class="text-danger">*</span> wajib diisi</p>
				</div>
				<div class="btn">
					<div class="inner"></div>
					<button type="button" id="btn-signup">Daftar</button>
				</div>
				<div class="signup-link">
					Sudah punya akun? <a href="<?= site_url('/login') ?>">Login</a>
				</div>
			</form>
		</div>
	</div>
	<script src="<?= base_url() ?>/asset/js/jquery-3.6.0.min.js"></script>
	<!-- SweetAlert2 -->
	<script src="<?= base_url('asset/js/sweetalert2.all.min.js') ?>"></script>

	<script>
		$('#btn-signup').click(function() {

			var formData = new FormData($('#signup-form')[0]);
			// // event.preventDefault();
			// var pengguna = $('#pengguna').val();
			// var username = $('#username').val();
			// var pass = $('#pass').val();
			// // var konfpassword = $('#konfpassword').val();
			// var selectedValue = $('#jabatan').val();

			$.ajax({
				// fixBug get url from global function only
				// get global variable is bug!
				url: '<?= base_url($controller . "/registered") ?>',
				type: 'post',
				data: formData,
				dataType: 'json',
				processData: false,
				contentType: false,
				beforeSend: function() {
					$('#btn-signup').html('<i class="fa fa-spinner fa-spin"></i>');
				},
				success: function(response) {
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