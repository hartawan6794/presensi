<!DOCTYPE html>
<!-- Created By CodingNepal -->
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Popup Login Form Design | CodingNepal</title>
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
                    <label for="username">username</label>
                    <input type="text" id="username" placeholder="Please enter your username">
                </div>
                <div class="data">
                    <label for="password">Password</label>
                    <input type="password" id="pass" placeholder="Please enter your passowrd">
                </div>
                <div class="btn">
                    <div class="inner"></div>
                    <button type="button" id="btn-login">login</button>
                </div>
                <div class="signup-link">
                    Not a member? <a href="#">Signup now</a>
                </div>
            </form>
        </div>
    </div>
    <script src="<?= base_url() ?>/asset/js/jquery-3.6.0.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="<?= base_url('asset/js/sweetalert2.all.min.js') ?>"></script>

    <script>
        $("#username, #password").keydown(function(event) {
            var username = $('#username').val();
            var pass = $('#pass').val();
            if (event.which == 13) { // Tombol "Enter" memiliki kode 13
                event.preventDefault(); // Mencegah pengiriman formulir
                login(username, pass);
            }
        });
        $('#btn-login').click(function() {
            // event.preventDefault();
            var username = $('#username').val();
            var pass = $('#pass').val();

            // console.log(username, ' ', pass)

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
                        })
                    }
                }

            })
        }
    </script>
</body>

</html>