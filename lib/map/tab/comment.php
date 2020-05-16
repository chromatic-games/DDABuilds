<?php
use system\steam\Steam;
?>
<div id="<?php echo $comment->getID(); ?>" class="container">
    <div class="row">
        <div class="col-sm-8">
            <div class="panel panel-white post panel-shadow">
                <div class="post-heading">
                    <div class="pull-left image">
                        <img src="<?php echo Steam::getInstance()->getAvatarMedium($comment->getData('steamid')); ?>"
                             class="img-circle avatar"
                             alt="user profile image">
                    </div>
                    <div class="pull-left meta">
                        <div class="title h5">
                            <a href="http://steamcommunity.com/profiles/<?php echo $comment->getData('steamid'); ?>"><?php echo Steam::getInstance()->getDisplayName($comment->getData('steamid')); ?></a>
                        </div>
                        <h6 class="text-muted time"><?php echo date('d F Y', strtotime($comment->getData('date'))); ?></h6>
                    </div>
                </div>
                <div class="post-description">
                    <p><?php echo htmlspecialchars($comment->getData('comment')); ?></p>
                    <div class="stats">
                        <a href="#" id="upvote<?php echo $comment->getID(); ?>" commentid="<?php echo $comment->getID(); ?>"
                           class="btn btn-default stat-item js-upvote <?php echo $up;?>">
                            <i class="fa fa-thumbs-up icon"></i>
                            <span id="upvotetext<?php echo $comment->getID(); ?>">
                                <?php echo $comment->getData('positivevotes'); ?></span>
                        </a>
                        <a href="#" id="downvote<?php echo $comment->getID(); ?>" commentid="<?php echo $comment->getID(); ?>"
                           class="btn btn-default stat-item js-downvote <?php echo $down;?>">
                            <i class="fa fa-thumbs-down icon"></i>
                            <span id="downvotetext<?php echo $comment->getID(); ?>">
                                <?php echo $comment->getData('negativevotes'); ?></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>