<?php

session_start();

include 'config/app.php';

// check apakah tmbol login ditekan
if (isset($_POST['login'])) {
    // ambil input username dan password
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    // secret key
    $secret_key = "6LdKBt4nAAAAAIDbh6oyUN1iJNe3GA-xbxi7h1A0";

    $verifikasi = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $_POST['g-recaptcha-response']);

    $response = json_decode($verifikasi);

    if ($response->success) {
        // check username
        $result = mysqli_query($db, "SELECT * FROM akun WHERE username = '$username'");

        // jika ada usernya
        if (mysqli_num_rows($result) == 1) {
            // check passwordnya
            $hasil = mysqli_fetch_assoc($result);

            if (password_verify($password, $hasil['password'])) {
                // set session
                $_SESSION['login']      = true;
                $_SESSION['id_akun']    = $hasil['id_akun'];
                $_SESSION['nama']       = $hasil['nama'];
                $_SESSION['username']   = $hasil['username'];
                $_SESSION['email']      = $hasil['email'];
                $_SESSION['level']      = $hasil['level'];

                // jika login benar arahkan ke file index.php
                header("Location: index.php");
                exit;
            } else {

                // jika username/password salah
                $error = true;
            }
            
        }
    } else {
        // jika recaptcha tidak valid
        $errorRecaptcha = true;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="assets-template/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="assets-template/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="assets-template/dist/css/adminlte.min.css">
    <!-- Favicons -->
    <link rel="icon" href="assets-template/img/bootstrap-logo.svg">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <div class="text-center">
                <img class="mb-4" src="assets/img/bootstrap-logo.svg" alt="" width="72" height="57">
                <a href="#"><b>Admin</b>LTE</a>
            </div>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Masukan username dan password</p>

                <?php if (isset($error)) : ?>
                    <div class="alert alert-danger text-center">
                        <b>Username/Password SALAH</b>
                    </div>
                <?php endif; ?>

                <?php if (isset($errorRecaptcha)) : ?>
                    <div class="alert alert-danger text-center">
                        <b>Recaptcha tidak valid</b>
                    </div>
                <?php endif; ?>

                <form action="" method="post">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="username" placeholder="Username..." required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="password" placeholder="Password..." required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="g-recaptcha" data-sitekey="6LdKBt4nAAAAAO6LoQkRw-yCWHFcsWH7wXNAZnLW"></div>
                    </div>

                    <div class="row">
                        <div class="col-8">
                        </div>

                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" name="login" class="btn btn-primary btn-block">Masuk</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="assets-template/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="assets-template/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="assets-template/dist/js/adminlte.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js"></script>
</body>

</html>