<?php
    session_start();
    if (isset($_SESSION["user_name"])) {
        header('Location: ../');
    }
?>
<!DOCTYPE html>
<html dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
    <title>psuarea - login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Sarabun:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
        rel="stylesheet">
    <link href="../dist/css/style.css" rel="stylesheet">
    <link href="../../assets/sweetalert2/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../../assets/nprogress/nprogress.css">

    <script src="../assets/jquery/dist/jquery.min.js "></script>
    <script src="../../assets/sweetalert2/sweetalert2.min.js"></script>
    <script src="../../assets/nprogress/nprogress.js"></script>
    <script src="script.js "></script>
</head>

<body>
    <div class="main-wrapper">
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>
        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center position-relative"
            style="background:url(../../img/auth-bg.jpg) no-repeat center center;">
            <div class="auth-box row">
                <div class="col-lg-7 col-md-5 modal-bg-img" style="background-image: url(../../img/2149048977.jpg);">
                </div>
                <div class="col-lg-5 col-md-7 bg-white">
                    <div class="p-3">
                        <div class="text-center">
                            <img src="../../img/icon.png" alt="wrapkit" width="100%">
                        </div>
                        <h2 class="mt-3 text-center">เข้าสู่ระบบ</h2>
                        <p class="text-center">สำหรับผู้ดูแลระบบเท่านั้น</p>
                        <form class="mt-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-dark" for="uname">บัญชีผู้ใช้</label>
                                        <input class="form-control" id="user_name" type="text"
                                            placeholder="enter your username">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-dark" for="pwd">รหัสผ่าน</label>
                                        <input class="form-control" id="user_password" type="password"
                                            placeholder="enter your password">
                                    </div>
                                </div>
                                <div class="col-lg-12 text-center">
                                    <button type="button" class="btn btn-block btn-dark" id="login">เข้าสู่ระบบ</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../assets/popper.js/dist/umd/popper.min.js "></script>
    <script src="../assets/bootstrap/dist/js/bootstrap.min.js "></script>
    <script>
    $(".preloader ").fadeOut();
    </script>
</body>

</html>