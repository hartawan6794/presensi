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
			<form>
				<div class="data">
					<label for="pengguna">Nama Lengkap</label>
					<input type="text" id="pengguna" placeholder="Please enter your full name">
				</div>
				<div class="data">
					<label for="username">Username</label>
					<input type="text" id="username" placeholder="Please enter your username">
				</div>
				<div class="data">
					<label for="pass">Password</label>
					<input type="password" id="pass" placeholder="Please enter your passowrd">
				</div>
				<!-- <div class="data">
					<label for="konfpassword">Konfirmasi Password</label>
					<input type="password" id="konfpassword" placeholder="Please enter your passowrd">
				</div> -->
				<div class="data form-group">
					<label for="jabatan" class="col-form-label">Jabatan</label>
					<select name="jabatan" class="form-control" id="jabatan">
						<option value="">--Pilih Jabatan--</option>
						<?php foreach ($dataJab as $data) :
							if ($data->jabatan != 'Admin') : ?>
								<option value="<?= $data->id_jabatan ?>"><?= $data->jabatan ?></option>
							<?php endif; ?>

						<?php endforeach; ?>

					</select>
					<!-- <input type="password" id="pass" placeholder="Please enter your passowrd"> -->
				</div>
				<div class="btn">
					<div class="inner"></div>
					<button type="button" id="btn-signup">Sign Up</button>
				</div>
				<div class="signup-link">
					Have a account? <a href="<?= site_url('/login') ?>">Login</a>
				</div>
			</form>
		</div>
	</div>
	<script src="<?= base_url() ?>/asset/js/jquery-3.6.0.min.js"></script>
	<!-- SweetAlert2 -->
	<script src="<?= base_url('asset/js/sweetalert2.all.min.js') ?>"></script>

	<script>
		// $("#username, #password").keydown(function(event) {
		//     var username = $('#username').val();
		//     var pass = $('#pass').val();
		//     if (event.which == 13) { // Tombol "Enter" memiliki kode 13
		//         event.preventDefault(); // Mencegah pengiriman formulir
		//         login(username, pass);
		//     }
		// });
		$('#btn-signup').click(function() {
			// event.preventDefault();
			var pengguna = $('#pengguna').val();
			var username = $('#username').val();
			var pass = $('#pass').val();
			// var konfpassword = $('#konfpassword').val();
			var selectedValue = $('#jabatan').val();

			// console.log(pengguna, ' ', selectedValue, ' ', username, ' ', pass)

			registered(pengguna, username, pass, selectedValue);
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
						console.log(response);
						if (response.success === true) {
							Swal.fire({
								icon: 'success',
								title: 'Selamat',
								text: 'Berhasil daftar sebagai asshole'
							}).then((result) => {
								if (result.value) {
									window.location = "<?= base_url('/login') ?>"
								}
							})
						} else {
							if (response.messages instanceof Object) {
								$.each(response.messages, function(index, value) {
									var ele = $("#" + index);
									console.log(response.messages[index])
									Swal.fire({
									toast: false,
									position: 'bottom-end',
									icon: 'error',
									title: response.messages[index],
									showConfirmButton: false,
									timer: 3000
								})
									// ele.closest('.form-control')
									// 	.removeClass('is-invalid')
									// 	.removeClass('is-valid')
									// 	.addClass(value.length > 0 ? 'is-invalid' : 'is-valid');
									// ele.after('<div class="invalid-feedback">' + response.messages[index] + '</div>');
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

					}
				})
				
			}
	</script>
</body>

</html>