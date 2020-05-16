<div class="container">
	<?php

	use system\request\LinkHandler;
	use system\steam\Steam;

	if ( $this->items ) {
		$vote = [
			-1 => 'down',
			1  => 'up',
		];
		/** @var \data\notification\Notification $notification */
		foreach ( $this->objects as $notification ) {
			$build = new \data\build\Build($notification->fk_build);
			$linkToBuild = LinkHandler::getInstance()->getLink('Build', ['object' => $build]);
			$buildName = $this->escapeHtml($build->name);

			if ( $notification->data == 1 ) {
				$appearance = 'alert-success';
			}
			elseif ( $notification->data == -1 ) {
				$appearance = 'alert-danger';
			}
			else {
				$appearance = 'alert-info';
			}

			if ( $notification->type == 2 ) {
				$link = $linkToBuild;
			}
			else {
				$link = $linkToBuild.'&comments#'.$notification->fk_comment;
			}

			if ( $notification->type == 1 ) {
				$message = '<u>'.Steam::getInstance()->getDisplayName($notification->data).'</u> wrote a comment on your build: <u>'.$buildName.'</u>';
			}
			elseif ( $notification->type == 2 ) {
				$message = 'Your build: <u>'.$buildName.'</u> got voted <u>'.$vote[$notification->data].'</u>';
			}
			elseif ( $notification->type == 3 ) {
				$message = 'Your comment on the build: <u>'.$buildName.'</u> got voted <u>'.$vote[$notification->data].'</u>';
			}
			elseif ( $notification->type == 4 ) {
				$message = '<u>'.Steam::getInstance()->getDisplayName($notification->data).'</u> also wrote a comment on the build: <u>'.$buildName.'</u>';
			}
			else {
				$message = 'error';
			}

			echo '
<div class="alert '.$appearance.'">
    '.$notification->date.': <a href="'.$link.'" class="alert-link">'.$message.'</a>.
</div>
';
		}

		$this->renderPages([
			'controller' => 'NotificationList',
			'url'        => 'pageNo=%d&sortField='.$this->sortField.'&sortOrder='.$this->sortOrder,
			'print'      => true,
		]);
	}
	else {
		echo 'No notifications.';
	}
	?>
</div>