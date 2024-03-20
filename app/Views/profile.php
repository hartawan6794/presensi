<?= $this->extend("layout/master") ?>

<?= $this->section("content") ?>

<div class="col-md6">
    <div class="card ">
        <div class="card-header">
            <div class="col-12">
                <a href="<?= base_url('/') ?>" class="btn btn-info float-end btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>
            </div>
        </div>
        <div class="card-body box-profile">
            <div class="text-center">
                <img class="user-image img-circle shadow" style="height: 300px; width: 300px" src="<?= session()->get('img_user') ? base_url('/img/user/' . session()->get('img_user')) : base_url('/asset/img/user.jpg') ?>" alt="User profile picture">
            </div>
            <h3 class="profile-username text-center"><?= session()->get('nama_lengkap') ?></h3>
            <p class="text-muted text-center"><?= session()->get('jabatan') ?></p>
            <!-- <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a> -->
            <div class="text-center">
                <button type="button" class="btn btn-info" id="btn-ubah-profile"><i class="fa fa-user-circle"></i> Ganti Photo Profile</button>
                <button type="button" class="btn btn-dark" id="btn-ubah-password"><i class="fa fa-key"></i> Ganti Password</button>
            </div>
        </div>

    </div>
</div>

<!-- ADD modal content -->
<div id="profile-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="text-center bg-info p-3" id="model-header">
                <h4 class="modal-title text-white" id="info-header-modalLabel">Ganti Photo Profile</h4>
            </div>
            <div class="modal-body">
                <form id="data-form" class="pl-3 pr-3">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label for="photo" class="col-form-label"> Photo: </label>
                                <input type="file" id="photo" name="photo" class="form-control" placeholder="Nama Lengkap">
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-center">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-success mr-2" id="form-btn"><?= lang("Simpan") ?></button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?= lang("Batal") ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- /ADD modal content -->

<div id="password-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="text-center bg-info p-3" id="model-header">
                <h4 class="modal-title text-white" id="info-header-modalLabel">Ganti Password</h4>
            </div>
            <div class="modal-body">
                <form id="password-form" class="pl-3 pr-3">
                    <div class="row">
                        <div class="form-group mb-3" id="form-password">
                            <label for="password" class="col-form-label"> Password: <span class="text-danger">*</span> </label>
                            <div class="input-group">
                                <input type="password" id="password" name="password" class="form-control" placeholder="Password">
                                <!-- <div class="input-group-append"> -->
                                <span class="input-group-text" id="togglePassword">
                                    <i class="fas fa-eye" id="eyeIcon"></i>
                                </span>
                                <!-- </div> -->
                            </div>
                        </div>
                        <div class="form-group mb-3" id="form-password-konfirmasi">
                            <label for="konfirmasi-password" class="col-form-label"> Password: <span class="text-danger">*</span> </label>
                            <div class="input-group">
                                <input type="password" id="konfirmasi-password" name="konfirmasi-password" class="form-control" placeholder="Password">
                                <!-- <div class="input-group-append"> -->
                                <span class="input-group-text" id="toggleKonfirmasiPassword">
                                    <i class="fas fa-eye" id="eyeIconKonfirmasi"></i>
                                </span>
                                <!-- </div> -->
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-center">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-success mr-2" id="form-btn"><?= lang("Simpan") ?></button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?= lang("Batal") ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<?= $this->endSection() ?>
<!-- /.content -->


<!-- page script -->
<?= $this->section("pageScript") ?>
<script>
    $(function() {

        $('#btn-ubah-profile').on('click', function() {
            $('#profile-modal').modal('show');

            $.validator.setDefaults({
                highlight: function(element) {
                    $(element).addClass('is-invalid').removeClass('is-valid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid').addClass('is-valid');
                },
                errorElement: 'div ',
                errorClass: 'invalid-feedback',
                errorPlacement: function(error, element) {
                    if (element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    } else if ($(element).is('.select')) {
                        element.next().after(error);
                    } else if (element.hasClass('select2')) {
                        //error.insertAfter(element);
                        error.insertAfter(element.next());
                    } else if (element.hasClass('selectpicker')) {
                        error.insertAfter(element.next());
                    } else {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function(form) {
                    // var form = $('#data-form');
                    $(".text-danger").remove();
                    $.ajax({
                        // fixBug get url from global function only
                        // get global variable is bug!
                        url: '<?= base_url($controller . "/ubahProfile") ?>',
                        type: 'post',
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        cache: false,
                        dataType: 'json',
                        beforeSend: function() {
                            $('#form-btn').html('<i class="fa fa-spinner fa-spin"></i>');
                        },
                        success: function(response) {
                            if (response.success === true) {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: response.messages,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(function() {

                                    $('#profile-modal').modal('hide');

                                    Swal.fire({
                                        title: "<?= lang("Informasi") ?>",
                                        text: "<?= lang("Harap login kembali untuk memperbarui profile anda") ?>",
                                        icon: 'warning'
                                    })
                                })
                            } else {
                                if (response.messages instanceof Object) {
                                    $.each(response.messages, function(index, value) {
                                        var ele = $("#" + index);
                                        ele.closest('.form-control')
                                            .removeClass('is-invalid')
                                            .removeClass('is-valid')
                                            .addClass(value.length > 0 ? 'is-invalid' : 'is-valid');
                                        ele.after('<div class="invalid-feedback">' + response.messages[index] + '</div>');
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
                            $('#form-btn').html(getSubmitText());
                        }
                    });
                    return false;
                }
            });

            $('#data-form').validate({

                //insert data-form to database

            });
        })

        $('#btn-ubah-password').on('click', function() {

            $('#password-modal').modal('show');

            $.validator.setDefaults({
                highlight: function(element) {
                    $(element).addClass('is-invalid').removeClass('is-valid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid').addClass('is-valid');
                },
                errorElement: 'div ',
                errorClass: 'invalid-feedback',
                errorPlacement: function(error, element) {
                    if (element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    } else if ($(element).is('.select')) {
                        element.next().after(error);
                    } else if (element.hasClass('select2')) {
                        //error.insertAfter(element);
                        error.insertAfter(element.next());
                    } else if (element.hasClass('selectpicker')) {
                        error.insertAfter(element.next());
                    } else {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function(form) {
                    var form = $('#password-form');
                    $(".text-danger").remove();
                    $.ajax({
                        // fixBug get url from global function only
                        // get global variable is bug!
                        url: '<?= base_url($controller . "/ubahPassword") ?>',
                        type: 'post',
                        data: form.serialize(),
                        cache: false,
                        dataType: 'json',
                        beforeSend: function() {
                            $('#form-btn').html('<i class="fa fa-spinner fa-spin"></i>');
                        },
                        success: function(response) {
                            if (response.success === true) {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: response.messages,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(function() {

                                    $('#password-modal').modal('hide');
                                })
                            } else {
                                if (response.messages instanceof Object) {
                                    $.each(response.messages, function(index, value) {
                                        var ele = $("#" + index);
                                        ele.closest('.form-control')
                                            .removeClass('is-invalid')
                                            .removeClass('is-valid')
                                            .addClass(value.length > 0 ? 'is-invalid' : 'is-valid');
                                        ele.after('<div class="invalid-feedback">' + response.messages[index] + '</div>');
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
                            $('#form-btn').html(getSubmitText());
                        }
                    });
                    return false;
                }
            });

            $('#password-form').validate({

                //insert data-form to database

            });
        })

        // Fungsi untuk menampilkan atau menyembunyikan password
        $("#togglePassword").click(function() {
            var passwordInput = $("#password");
            var eyeIcon = $("#eyeIcon");

            if (passwordInput.attr("type") === "password") {
                passwordInput.attr("type", "text");
                eyeIcon.removeClass("fa-eye").addClass("fa-eye-slash");
            } else {
                passwordInput.attr("type", "password");
                eyeIcon.removeClass("fa-eye-slash").addClass("fa-eye");
            }
        });

        // Fungsi untuk menampilkan atau menyembunyikan password
        $("#toggleKonfirmasiPassword").click(function() {
            var passwordInput = $("#konfirmasi-password");
            var eyeIcon = $("#eyeIconKonfirmasi");

            if (passwordInput.attr("type") === "password") {
                passwordInput.attr("type", "text");
                eyeIcon.removeClass("fa-eye").addClass("fa-eye-slash");
            } else {
                passwordInput.attr("type", "password");
                eyeIcon.removeClass("fa-eye-slash").addClass("fa-eye");
            }
        });
    })
</script>


<?= $this->endSection() ?>