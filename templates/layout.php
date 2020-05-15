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
	use system\Core;
	use system\steam\Steam;

	/*if ( $page == 'map' ) {
		echo '
        <script src="assets/js/html2canvas.js"></script>
        <script src="assets/js/jquery-ui.js"></script>
        <script src="assets/js/jQueryRotate.js"></script>
        <script src="assets/js/ckeditor/ckeditor.js"></script>
        ';
	}
	elseif ( $page == 'home' ) {
		echo '<link href="/assets/css/full-width-pics.css" rel="stylesheet">';
	}
	elseif ( $page == 'maps' ) {
		echo '<script src="assets/js/scroll-top.js"></script>';
	}
	elseif ( $page == 'list' ) {
		echo '<script type="text/javascript" src="assets/js/jquery.flexdatalist.min.js"></script>';
		echo '<link href="assets/css/jquery.flexdatalist.min.css" rel="stylesheet">';
	}*/
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
	<!-- Navigation -->
	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse"
				        data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="..">DDA Builder</a>
			</div>
			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<?php
					if ( isset($_SESSION['steamid']) ) {
						echo '
                    <li>
                        <a href="?page=maps">Create</a>
                    </li>
                ';
					}
					?>
					<li>
                    <a href="?page=list">List</a>
                </li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
                <li>
                    <?php
                    if ( !isset($_SESSION['steamid']) ) {
	                    echo '<div class="navbar-brand" style="margin-top:-8px";><a href="'.BASE_URL.'?page=login">Login to Create or Vote on Builds:</a> ';
	                    echo Steam::getInstance()->getLoginButton(Steam::BUTTON_STYLE_RECTANGLE);
	                    echo '</div>';
                    }
                    else {
	                    echo '<li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'.Core::getUser()->displayName.'<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="?page=myBuilds">My Builds</a>
                                    </li>
                                    <li>
                                        <a href="?page=notifications">Notifications</a>
                                    </li>
                                    <li>
                                        <a href="?page=logout">Logout</a>
                                    </li>
                                </ul>
                            </li>';
                    }
                    ?>
                </li>
				</ul>
			</div>
			<!-- /.navbar-collapse -->
		</div>
		<!-- /.container -->
	</nav>
	<!-- /Navigation -->
	<?php
	if ( Core::getUser()->steamID ) {
		$newNotifications = count(Notifications::getUnreadNotificationsForUser(Core::getUser()->steamID));
		if ( $newNotifications ) {
			echo '
        <div class="container">
            <div class="row text-middle">
                <div class="col-md-7">
                </div>
                <div class="col-md-5">
                    <div class="alert alert-success">
                        Hello '.Utility::getSteamName($_SESSION['steamid']).' you have: <a href="notifications.php" class="alert-link">'.$newNotifications.' unread notifications</a>.
                    </div>
                </div>
            </div>
        </div>
        ';
		}
	}
	?>
	<!-- Content Section -->
	<section>
		<?php echo $this->content; ?>
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