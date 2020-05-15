<?php
require_once 'config.php';
require 'steamauth/steamauth.php';

define('LIB_DIR', __DIR__.'/lib/');

spl_autoload_register(function ($class_name) {
	include LIB_DIR.'classes/'.$class_name.'.php';
});

if ( isset($_SESSION['steamid']) ) {
	include('steamauth/userInfo.php');
}

$page = isset($_GET['page']) ? $_GET['page'] : 'home';
// fallback page -> home
if ( stristr('/', $page) || stristr('..', $page) || !file_exists('./lib/pages/'.$page.'.php') ) {
	$page = 'home';
}

if ( !empty($_GET['action']) ) {
	$action = $_GET['action'];
	if ( stristr('/', $page) || stristr('..', $page) || !file_exists('./lib/actions/'.$action.'.php') ) {
		die(json_encode(['error' => 'invalid actions']));
	}

	require_once(LIB_DIR.'actions/'.$action.'.php');
	exit;
}

?>
<!doctype html>
<html lang="en">
<head>
	<title>DDA Builds</title>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="icon" type="image/png" href="assets/images/tower/crystal_core.png">

	<!-- Bootstrap Core CSS -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/css/chakratos.css" rel="stylesheet">
	<link href="assets/css/font-awesome.min.css" rel="stylesheet">

	<!-- jQuery Version 1.11.1 -->
	<script src="assets/js/jquery.js"></script>
	<?php
	// TODO re-add with $page
	if ( $page == 'map' ) {
		echo '
        <script src="assets/js/html2canvas.js"></script>
        <script src="assets/js/jquery-ui.js"></script>
        <script src="assets/js/jQueryRotate.js"></script>
        <script src="assets/js/ckeditor/ckeditor.js"></script>
        ';
	}
	elseif ( $_SERVER['SCRIPT_NAME'] == 'home' ) {
		echo '<link href="assets/css/full-width-pics.css" rel="stylesheet">';
	}
	elseif ( $_SERVER['SCRIPT_NAME'] == 'maps' ) {
		echo '<script src="assets/js/scroll-top.js"></script>';
	}
	elseif ( $_SERVER['SCRIPT_NAME'] == 'list' ) {
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
</head>
<body>
	<?php include 'navbar.php'; ?>
	<!-- Full Width Image Header with Logo -->
	<!-- Image backgrounds are set within the full-width-pics.css file. -->
	<header id="headerimage" class="image-bg-fluid-height">
		<!-- <img class="img-responsive img-center" src="images/tower/crystal_core.png" alt="">-->
	</header>

	<!-- Content Section -->
	<section>
		<?php
		require_once('./lib/pages/'.$page.'.php');
		?>
	</section>

	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-39334248-36"></script>
	<script>
		window.dataLayer = window.dataLayer || [];

		function gtag() {dataLayer.push(arguments);}

		gtag('js', new Date());

		gtag('config', 'UA-39334248-36');
	</script>
</body>
</html>
