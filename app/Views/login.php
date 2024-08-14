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

    <title>Login Aplikasi Presensi</title>
</head>

<body>



    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <img src="<?= base_url('asset/img/undraw_remotely_2j6y.svg')?>" alt="Image" class="img-fluid">
                </div>
                <div class="col-md-6 contents">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="mb-4">
                                <h3>Login Form</h3>
                                <p class="mb-4">Masukan username dan password saudara</p>
                            </div>
                            <form >
                                <div class="form-group first">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" id="username">

                                </div>
                                <div class="form-group last mb-4">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password">
                                </div>

                                <div class="d-flex mb-5 align-items-center">
                                    <label class="control control--checkbox mb-0"><span class="caption">Remember me</span>
                                        <input type="checkbox" checked="checked" />
                                        <div class="control__indicator"></div>
                                    </label>
                                    <span class="ml-auto"><a href="<?= base_url("/registered") ?>" class="forgot-pass">Daftar akun</a></span>
                                </div>

                                <button type="button" id="btn-login" class="btn btn-block btn-primary">login</button>
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
    $("#username, #password").keydown(function(event) {
        var username = $('#username').val();
        var pass = $('#password').val();
        if (event.which == 13) { // Tombol "Enter" memiliki kode 13
            event.preventDefault(); // Mencegah pengiriman formulir
            login(username, pass);
        }
    });
    $('#btn-login').click(function() {
        // event.preventDefault();
        var username = $('#username').val();
        var pass = $('#password').val();

        console.log(username, ' ', pass)

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