<?php

use system\steam\Steam;
use system\util\StringUtil;

/** @var \data\comment\Comment $comment */
$comment = $this->comment;
?>
<div id="comment-<?php echo $comment->getObjectID(); ?>" class="panel post panel-shadow">
	<div style="display:flex;">
		<div>
			<img src="<?php echo Steam::getInstance()->getAvatarMedium($comment->steamid); ?>" class="img-circle avatar" alt="user profile image" />
		</div>
		<div style="margin-left: 15px;">
			<a href="http://steamcommunity.com/profiles/<?php echo $comment->steamid; ?>"><?php echo Steam::getInstance()->getDisplayName($comment->steamid); ?></a><br />
			<small class="text-muted time"><?php echo $comment->getDate(); ?></small>
		</div>
	</div>
	<div class="post-description">
		<p><?php echo StringUtil::removeInsecureHtml($comment->comment); ?></p>
		<div class="stats">
			<a href="#" class="btn btn-default btn-disabled js-vote"><i class="fa fa-thumbs-up icon"></i> <span>???</span></a>
			<a href="#" class="btn btn-default btn-disabled js-vote"><i class="fa fa-thumbs-down icon"></i> <span>???</span></a>
		</div>
	</div>
</div>
