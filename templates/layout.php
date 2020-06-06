<!doctype html>
<html lang="en">
<head>
	<title><?php if ( !empty($this->pageTitle) ) {
			echo $this->escapeHtml($this->pageTitle).' - ';
		} ?> DDA Builds</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="icon" type="image/png" href="assets/images/tower/crystal_core.png">

	<!-- Bootstrap Core CSS -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/css/font-awesome.min.css" rel="stylesheet">
	<link href="assets/css/main<?php echo !DEBUG_MODE ? '.min' : ''; ?>.css" rel="stylesheet">

	<!-- jQuery Version 1.11.1 -->
	<script src="assets/js/jquery.js"></script>
	<script src="assets/js/core.js"></script>
	<?php

	use system\Core;
	use system\request\LinkHandler;
	use system\steam\Steam;

	if ( Core::getUser()->steamID ) {
		echo '<script src="assets/js/like.js"></script>';
	}

	// TODO move to controller (form/page) and build min js/css files
	if ( $this->templateName === 'buildAdd' ) {
		echo '<script src="assets/js/html2canvas.js"></script>
        <script src="assets/js/jquery-ui.js"></script>
        <script src="assets/js/jQueryRotate.js"></script>
        <script src="assets/js/ckeditor/ckeditor.js"></script>';
	}
	elseif ( $this->templateName === 'index' ) {
		echo '<link href="/assets/css/full-width-pics.css" rel="stylesheet">';
	}
	elseif ( $this->templateName === 'buildAddSelect' ) {
		echo '<script src="assets/js/scroll-top.js"></script>';
	}
	elseif ( $this->templateName === 'bugReportAdd' ) {
		echo '<script src="assets/js/ckeditor/ckeditor.js"></script>';
	}
	elseif ( $this->templateName == 'buildList' ) {
		echo '<script type="text/javascript" src="assets/js/jquery.flexdatalist.min.js"></script>';
		echo '<link href="assets/css/jquery.flexdatalist.min.css" rel="stylesheet">';
	}
	?>
	<!-- Bootstrap Core JavaScript -->
	<script src="assets/js/bootstrap.min.js"></script>
	<script>
		if (CKEDITOR) {
			CKEDITOR.timestamp = '2020-06-01';
		}
	</script>
</head>
<body class="<?php echo 'tpl'.ucfirst($this->templateName) ?>">
	<!-- Navigation -->
	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse"
				        data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="/">DDA Builder</a>
			</div>
			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li><a href="<?php echo LinkHandler::getInstance()->getLink('BuildList') ?>">List</a></li>
					<?php
					if ( Core::getUser()->steamID ) {
						echo '<li><a href="'.LinkHandler::getInstance()->getLink('BuildAddSelect').'">Create</a></li>';
						echo '<li><a href="'.LinkHandler::getInstance()->getLink('BugReportAdd').'">Report Bug</a></li>';
						if ( Core::getUser()->isMaintainer() ) {
							echo '<li><a href="'.LinkHandler::getInstance()->getLink('BugReportList').'">Bug Reports</a></li>';
						}
					}
					?>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li>
						<a class="pointer" onClick="Core.DarkMode.toggle()">
							<i class="fa fa-moon-o darkSymbol"></i>
							<span class="label label-danger betaLabel">Beta</span>
						</a>
					</li>
                    <?php
                    if ( !Core::getUser()->steamID ) {
	                    $loginLink = LinkHandler::getInstance()->getLink('Login');
	                    echo '<div class="navbar-brand" style="margin-top:-8px";><a href="'.$loginLink.'">Login to Create or Vote on Builds: </a>';
	                    echo '<a href="'.$loginLink.'">'.Steam::getInstance()->getLoginButton(Steam::BUTTON_STYLE_RECTANGLE).'</a>';
	                    echo '</div>';
                    }
                    else {
	                    $notifications = Core::getUser()->getUnreadNotifications();

	                    echo '<li class="notificationBell">
							<a href="'.LinkHandler::getInstance()->getLink('NotificationList').'">
							<i class="fa fa-bell'.($notifications === 0 ? '-o' : '').'"></i>
							'.($notifications ? '<span class="badge badge-danger">'.$notifications.'</span>' : '').'
							</a>
						</li>
						<li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'.Core::getUser()->name.'<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="'.LinkHandler::getInstance()->getLink('MyBuildList').'">My Builds</a>
                                </li>
                                <li>
                                    <a href="'.LinkHandler::getInstance()->getLink('MyFavoriteBuildList').'">My Favorite Builds</a>
                                </li>
                                <li>
                                    <a href="'.LinkHandler::getInstance()->getLink('MyLikedBuildList').'">My Liked Builds</a>
                                </li>
                                <li>
                                    <a href="'.LinkHandler::getInstance()->getLink('MyBugReportList').'">My Bug Reports</a>
                                </li>
                                <li>
                                    <a href="'.LinkHandler::getInstance()->getLink('Logout').'">Logout</a>
                                </li>
                            </ul>
                        </li>';
                    }
                    ?>
				</ul>
			</div>
			<!-- /.navbar-collapse -->
		</div>
		<!-- /.container -->
	</nav>
	<!-- /Navigation -->

	<div id="pageContainer">
		<!-- Content Section -->
		<section id="main">
			<?php echo $this->content; ?>
		</section>
		<footer id="footer" class="navbar navbar-inverse navbar-footer">
			<div class="container">
				<ul class="nav navbar-nav">
					<li><a href="<?php echo LinkHandler::getInstance()->getLink('Changelog') ?>">Changelog</a></li>
				</ul>
				<?php if ( DEBUG_MODE ) { ?>
					<div class="navbar-right navbar-text pointer" data-toggle="modal" data-target="#debugModal">
						Execution time: <?php echo round(microtime(true) - APPLICATION_START, 2); ?>s | Queries: <?php echo Core::getDB()->getQueryCount(); ?> | Steam Requests: <?php echo Steam::getInstance()->getRequests(); ?>
					</div>

					<div id="debugModal" class="modal fade" tabindex="-1" role="dialog">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title">Log</h4>
								</div>
								<div class="modal-body">
									<ul style="list-style:none;padding:0;margin:0;">
										<?php
										foreach ( Core::getDB()->getQueries() as $query ) {
											echo '<li>'.$query['query'].'</li>';
										}
										?>
									</ul>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								</div>
							</div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->
				<?php } ?>
			</div>
		</footer>
	</div>

	<div id="loadingSpinner" style="display:none;">
		<div class="loadingSpinner">
			<span class="fa fa-4x fa-spinner fa-spin"></span> <span>Loading ...</span>
		</div>
		<div class="pageBackdrop"></div>
	</div>

	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-39334248-36"></script>
	<script>
		window.dataLayer = window.dataLayer || [];

		function gtag() {dataLayer.push(arguments);}

		gtag('js', new Date());
		gtag('config', 'UA-39334248-36');
	</script>
	<!-- JAVASCRIPT_RELOCATE_POSITION -->
</body>
</html>