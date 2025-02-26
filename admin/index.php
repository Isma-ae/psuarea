<?php
    session_start();
    if (!isset($_SESSION["user_name"])) {
        header('Location: ./login');
    }
    $page = isset($_GET['p']) ? $_GET['p']:'home';
?>
<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="../img/images.png">
    <title>psuarea - <?php echo $page;?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Sarabun:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/select2/css/select2.min.css">
    <link rel="stylesheet" href="assets/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <link href="dist/css/style.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/prism/prism.css">
    <link href="../assets/sweetalert2/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../assets/nprogress/nprogress.css">

    <script src="assets/jquery/dist/jquery.min.js"></script>
    <script src="../assets/sweetalert2/sweetalert2.min.js"></script>
    <script src="../assets/nprogress/nprogress.js"></script>
    <script src="assets/tinymce/tinymce.min.js"></script>
    <script src="assets/myscript.js"></script>
</head>

<body>
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
        <?php include('master/header.php');?>
        <?php include('master/sidebar.php');?>
        <div class="page-wrapper">
            <?php include('pages/'.$page.'/view.php'); ?>
            <?php include('master/footer.php');?>
        </div>
    </div>
    <script src="assets/popper.js/dist/umd/popper.min.js"></script>
    <script src="assets/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="dist/js/app-style-switcher.js"></script>
    <script src="dist/js/feather.min.js"></script>
    <script src="assets/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="assets/sparkline/sparkline.js"></script>
    <script src="dist/js/sidebarmenu.js"></script>
    <script src="dist/js/custom.min.js"></script>
    <script src="assets/prism/prism.js"></script>
    <script src="assets/select2/js/select2.full.min.js"></script>
</body>

</html>