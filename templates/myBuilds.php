<?php

use system\request\LinkHandler;

include LIB_DIR.'myBuilds/myBuildsGetHandler.php';
?>

<div class="container">
	<div class="row">
		<div class="col-lg-12 text-center">
			<?php
			include LIB_DIR.'list/loadBuilds.php';
			$linkParams = ['pageNo' => $i,];
			if ( isset($_REQUEST['by']) ) {
				$linkParams['by'] = $_REQUEST['by'];
			}
			if ( isset($_REQUEST['order']) ) {
				$linkParams['order'] = $_REQUEST['order'];
			}
			?>
			<ul class="pagination">
				<?php
				$curSite = '';
				for ( $i = 1;$i <= $pages;$i++ ) {
					if ( $site == $i ) {
						$curSite = 'active';
					}

					echo '<li class="'.$curSite.'"><a href="'.LinkHandler::getInstance()->getLink('MyBuilds', $linkParams).'">'.$i.'</a></li>';
					$curSite = '';
				}
				?>
			</ul>
		</div>
	</div>
</div>
<script>
	$(document).ready(function () {
		var myUrl = window.location.href;

		$('#sortName').on('click', function (event) {
			event.preventDefault();
			addQSParm('by', 'name');
			if (GET.by == 'name' && GET.order == 'ASC') {
				addQSParm('order', 'DESC');
			}
			else {
				addQSParm('order', 'ASC');
			}

			window.location.replace(myUrl);
		});
		$('#sortMap').on('click', function (event) {
			event.preventDefault();
			addQSParm('by', 'map');
			if (GET.by == 'map' && GET.order == 'ASC') {
				addQSParm('order', 'DESC');
			}
			else {
				addQSParm('order', 'ASC');
			}
			window.location.replace(myUrl);
		});
		$('#sortDifficulty').on('click', function (event) {
			event.preventDefault();
			addQSParm('by', 'difficulty');
			if (GET.by == 'difficulty' && GET.order == 'ASC') {
				addQSParm('order', 'DESC');
			}
			else {
				addQSParm('order', 'ASC');
			}
			window.location.replace(myUrl);
		});
		$('#sortRating').on('click', function (event) {
			event.preventDefault();
			addQSParm('by', 'votes');
			if (GET.by == 'votes' && GET.order == 'DESC') {
				addQSParm('order', 'ASC');
			}
			else {
				addQSParm('order', 'DESC');
			}
			window.location.replace(myUrl);
		});
		$('#sortViews').on('click', function (event) {
			event.preventDefault();
			addQSParm('by', 'views');
			if (GET.by == 'views' && GET.order == 'DESC') {
				addQSParm('order', 'ASC');
			}
			else {
				addQSParm('order', 'DESC');
			}
			window.location.replace(myUrl);
		});
		$('#sortDate').on('click', function (event) {
			event.preventDefault();
			addQSParm('by', 'date');
			if (GET.by == 'date' && GET.order == 'DESC') {
				addQSParm('order', 'ASC');
			}
			else {
				addQSParm('order', 'DESC');
			}
			window.location.replace(myUrl);
		});
		$('#sortAuthor').on('click', function (event) {
			event.preventDefault();
			addQSParm('by', 'author');
			if (GET.by == 'author' && GET.order == 'ASC') {
				addQSParm('order', 'DESC');
			}
			else {
				addQSParm('order', 'ASC');
			}
			window.location.replace(myUrl);
		});
		var GET = new /**
		 * @return {string}
		 */
		function () {
			try {
				return JSON.parse('{"' + window.location.href.split('?')[1].split('&').join('", "').split('=').join('":"') + '"}');
			}
			catch (e) {
				return '';
			}
		};

		function addQSParm(name, value) {
			var re = new RegExp('([?&]' + name + '=)[^&]+', '');

			function add(sep) {
				myUrl += sep + name + '=' + encodeURIComponent(value);
			}

			function change() {
				myUrl = myUrl.replace(re, '$1' + encodeURIComponent(value));
			}

			if (myUrl.indexOf('?') === -1) {
				add('?');
			}
			else {
				if (re.test(myUrl)) {
					change();
				}
				else {
					add('&');
				}
			}
		}
	});
</script>