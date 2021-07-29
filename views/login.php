<?php
include __DIR__ . '/layouts/header.php';
?>
<div class="row justify-content-center">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <h1 class="text-center mb-4">Đăng nhập Admin</h1>
        <div class="input-group mb-3">
            <span class="input-group-text"><i class="bi bi-person"></i></span>
            <input id="username" type="text" class="form-control" placeholder="Username">
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text"><i class="bi bi-key"></i></span>
            <input id="password" type="password" class="form-control" placeholder="Mật khẩu">
        </div>
        <div class="d-flex justify-content-center mt-5 mb-3">
            <button id="login" class="btn btn-danger"><h4>Đăng nhập</h4></button>
        </div>
    </div>
</div>
<!--Message modal-->
<div class="modal fade" id="message">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Thông báo</div>
            </div>
            <div class="modal-body" id="message-body">
                Sai tên đăng nhập hoặc mật khẩu
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
<!--End of message modal-->
<script>
    $('#login').click(function () {
        username = $('#username').val();
        password = $('#password').val();

        data = {
            username: username,
            password: password,
        }

        $.post('index.php?action=login&method=login', data).done(function(data) {
            if (data == false) {
                $('#message').modal('show');
            } else {
                window.location.href = 'index.php?action=phone&method=index';
            }
        });
    });
</script>
<?php
include __DIR__ . '/layouts/footer.php';
?>