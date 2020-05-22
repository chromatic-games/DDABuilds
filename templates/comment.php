<?php

use system\Core;
use system\steam\Steam;
use system\util\StringUtil;

/** @var \data\comment\Comment $comment */
$comment = $this->comment;
?>
<div id="comment-<?php echo $comment->getObjectID(); ?>" class="panel post panel-shadow jsObject" data-type="comment" data-id="<?php echo $comment->getObjectID(); ?>">
	<div style="display:flex;">
		<div>
			<img src="<?php echo Steam::getInstance()->getAvatarMedium($comment->steamid); ?>" class="img-circle avatar" alt="user profile image" />
		</div>
		<div style="margin-left: 15px;">
			<a href="http://steamcommunity.com/profiles/<?php echo $comment->steamid; ?>"><?php echo Steam::getInstance()->getDisplayName($comment->steamid); ?></a><br />
			<small class="text-muted time"><?php echo $comment->getDate(); ?></small>
		</div>
	</div>
	<div class="post-description marginTop">
		<?php echo StringUtil::removeInsecureHtml($comment->comment); ?>
		<div class="stats marginTop">
			<button class="btn btn-<?php echo $comment->likeValue === 1 ? 'success' : 'default'; ?> jsVote" data-type="like" data-count="<?php echo $comment->likes ?>"<?php echo $comment->steamid === Core::getUser()->steamID ? ' disabled' : ''; ?>>
				<i class="fa fa-thumbs-up icon"></i> <span class="likeValue"><?php echo $this->number($comment->likes) ?></span>
			</button>
			<button class="btn btn-<?php echo $comment->likeValue === -1 ? 'danger' : 'default'; ?> jsVote" data-type="dislike" data-count="<?php echo $comment->dislikes ?>"<?php echo $comment->steamid === Core::getUser()->steamID ? ' disabled' : ''; ?>>
				<i class="fa fa-thumbs-down icon"></i> <span class="likeValue"><?php echo $this->number($comment->dislikes) ?></span>
			</button>
		</div>
	</div>
</div>
