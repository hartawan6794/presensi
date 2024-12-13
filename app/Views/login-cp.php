<!DOCTYPE html>
<!-- Created By CodingNepal -->
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Login Form</title>
    <link rel="stylesheet" href="<?= base_url('asset/css/css.css') ?>">
    <link rel="stylesheet" href="<?= base_url('asset/plugins/fontawesome-free/css/all.min.css') ?>" />
</head>

<body>
    <div class="center">
        <div class="container">
            <!-- <label for="show" class="close-btn fas fa-times" title="close"></label> -->
            <div class="text">
                Login Form
            </div>
            <form>
                <div class="data">
                    <label for="username">Nama Pengguna : </label>
                    <input type="text" id="username" placeholder="Masukan nama pengguna anda">
                </div>
                <div class="data">
                    <label for="sandi">Kata Sandi : </label>
                    <input type="password" id="sandi" placeholder="Masukan kata sandi anda">
                </div>
                <div class="btn">
                    <div class="inner"></div>
                    <button type="button" id="btn-login">login</button>
                </div>
                <div class="signup-link">
                    Belum punya akun? <a href="<?= base_url("/registered") ?>">Daftar sekarang</a>
                </div>
            </form>
        </div>
    </div>
    <script src="<?= base_url() ?>/asset/js/jquery-3.6.0.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="<?= base_url('asset/js/sweetalert2.all.min.js') ?>"></script>

    <script>
        $("#username, #sandi").keydown(function(event) {
            var username = $('#username').val();
            var pass = $('#sandi').val();
            if (event.which == 13) { // Tombol "Enter" memiliki kode 13
                event.preventDefault(); // Mencegah pengiriman formulir
                login(username, pass);
            }
        });
        $('#btn-login').click(function() {
            // event.preventDefault();
            var username = $('#username').val();
            var pass = $('#sandi').val();

            login(username, pass);
        })

        function login(username, pass) {
            $.ajax({
                // fixBug get url from global function only
                // get global variable is bug!
                url: '<?= base_url($controller . "/login") ?>',
                type: 'post',
                data: {
                    username: username,
                    password: pass
                },
                cache: false,
                dataType: 'json',
                beforeSend: function() {
                    $('#btn-login').html('<i class="fa fa-spinner fa-spin"></i>');
                },
                success: function(response) {
                    if (response.success === true) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Selamat',
                            text: 'Login berhasil'
                        }).then((result) => {
                            if (result.value) {
                                window.location = "<?= site_url('/home') ?>"
                            }
                        })
                    } else {
                        Swal.fire({
                            toast: false,
                            position: 'bottom-end',
                            icon: 'error',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 3000
                        }).then((result) => {
                            $('#btn-login').html('Login')
                        })
                    }
                }

            })
        }
    </script>
</body>

</html>