<?php
require 'steamauth/steamauth.php';
if (isset($_SESSION['steamid'])) {
    include('steamauth/userInfo.php');
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>DDA Builds</title>
    <link rel="icon" type="image/png" href="assets/images/tower/crystal_core.png">

    <!-- Bootstrap Core CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/chakratos.css" rel="stylesheet">
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">

    <!-- jQuery Version 1.11.1 -->
    <script src="assets/js/jquery.js"></script>
    <?php
    if ($_SERVER['SCRIPT_NAME'] == '/map.php') {
        echo '
        <script src="assets/js/html2canvas.js"></script>
        <script src="assets/js/jquery-ui.js"></script>
        <script src="assets/js/jQueryRotate.js"></script>
        <script src="assets/js/ckeditor/ckeditor.js"></script>
        ';
    } else if ($_SERVER['SCRIPT_NAME'] == '/index.php') {
        echo '<link href="assets/css/full-width-pics.css" rel="stylesheet">';
    } else if ($_SERVER['SCRIPT_NAME'] == '/maps.php') {
        echo '<script src="assets/js/scroll-top.js"></script>';
    } else if ($_SERVER['SCRIPT_NAME'] == '/list.php') {
        echo '<script type="text/javascript" src="assets/js/jquery.flexdatalist.min.js"></script>';
        echo '<link href="assets/css/jquery.flexdatalist.min.css" rel="stylesheet">';
    }
    ?>
    <!-- Bootstrap Core JavaScript -->
    <script src="assets/js/bootstrap.min.js"></script>

    <!-- Custom CSS -->
    <style>
        body {
            padding-top: 70px;
            /* Required padding for .navbar-fixed-top. Remove if using .navbar-static-top. Change if height of navigation changes. */
        }
    </style>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-39334248-36"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-39334248-36');
</script>

</head>
